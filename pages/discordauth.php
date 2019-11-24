<!DOCTYPE html>
<html lang="en">

<head>
    <title>DiscordAuthentication</title>
    <?php
    session_start();
    require ('../vendor/autoload.php');
    require '../config/config.php';
    require '../functions/functions.php';

      use RestCord\DiscordClient;
        $provider = new \Wohali\OAuth2\Client\Provider\Discord([
            'clientId' => $discordclientid,
            'clientSecret' => $discordclientsecret,
            'redirectUri' => $discordredirecturi
        ]);
    ?>
</head>

<?php

if (!empty($_GET['error'])) {
    header('Location: ../pages/discordnotallowed.php');
    exit;
}

// Check if the user has already authenticated
if (!empty($_SESSION['discordloggedin'])) {
    // Check if the token is still valid
    if (!empty($_SESSION['discordtoken'])) {
        // Check of the current token is still valid
            $token = $_SESSION['discordtoken'];

            if (!$token->hasExpired()) {
                // Token is valid. Done.
                header("Location: $discordredirect");
                exit;
            } else {
                // If not valid, check if the token can be renewed
                try {
                        // Token has expired, trying a refresh
                        $newAccessToken = $provider->getAccessToken('refresh_token', [
                            'refresh_token' => $token->getRefreshToken()
                        ]);
                        $_SESSION['discordtoken'] = $newAccessToken;
                    } catch (Exception $e) {
                        // If cannot be renewed, destroy everything from discord in session and re-authenticate
                        unset($_SESSION['discordloggedin']);
                        unset($_SESSION['discordallowed']);
                        unset($_SESSION['discordname']);
                        unset($_SESSION['discordcode']);
                        unset($_SESSION['oauth2state']);
                        unset($_SESSION['discordtoken']);
                
                        header("Location: $discordredirecturi");
                        exit;
                    }
                    // New token has been set, done.
                    exit;
            }
    } else {
        // user has no discordcode, destroy everything and refresh the page for a new request
        unset($_SESSION['discordloggedin']);
        unset($_SESSION['discordallowed']);
        unset($_SESSION['discordname']);
        unset($_SESSION['discordcode']);
        unset($_SESSION['oauth2state']);
        unset($_SESSION['discordtoken']);

        header("Location: $discordredirecturi");
        exit;
    }
} else { // Authenticate a new request


    if (!isset($_GET['code'])) {
        // Step 1. Get a new authorization code
        // Prepare everything for the session by unsetting all previous set tokens:
            unset($_SESSION['discordloggedin']);
            unset($_SESSION['discordallowed']);
            unset($_SESSION['discordname']);
            unset($_SESSION['discordcode']);
            unset($_SESSION['oauth2state']);
            unset($_SESSION['discordtoken']);

        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        
        header('Location: ' . $authUrl);

    // Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

        unset($_SESSION['discordloggedin']);
        unset($_SESSION['discordallowed']);
        unset($_SESSION['discordname']);
        unset($_SESSION['discordcode']);
        unset($_SESSION['oauth2state']);
        unset($_SESSION['discordtoken']);
        exit('Invalid state');

    } else { // Everything is in place! Save it in session

            // Step 2. Get an access token using the provided authorization code
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
            $discordtoken = $token;

            try {
                // use the gathered token in step two to get user info
                $user = $provider->getResourceOwner($token);

                // Convert the output to an array so we can set variables
                $discorduser = $user->toArray();

                // Set the variables!
                $discordusername = $discorduser['username'];
                $discorduserverified = $discorduser['verified'];
                $discorduserid = (int) $discorduser['id'];
                $discorduseravatar = $discorduser['avatar'];
                $discorduseremail = $discorduser['email'];
                $discordname = "$discordusername#" . $discorduser['discriminator'];

                if ($discordusername) { // If the oauth succeeded, discordusername should now be filled. We can continue to check
                    
                    // Now that we have all the user info from discord itself. We can check with our installed bot if this user has special roles
                        // Setup Restcord to ask our bot something
            
                        $discord = new DiscordClient(['token' => $discordbottoken]);
            
                        // Check which guild(server) we have to check something for, then get the user from the guild
                        $discordmember = $discord->guild->getGuildMember(['guild.id' => $discordguildid, 'user.id' => $discorduserid]);
            
                        // Convert from object to array
                        $discordmember = (array)$discordmember;
                        $discordmemberroles = (array)$discordmember['roles'];
            
                        // Check if the user is administrator
                        $discordadminid = explode(", ", $allowedrole);
                        $isdiscordadmin = in_array_any($discordadminid, $discordmemberroles);
            
                    // Do the final checks, after everything is done. This will determine the outcome of the discord auth
                        if ($isdiscordadmin) {
                            if ($discorduserverified) {
                                // User has been authenticated and is allowed (admin)
                                $_SESSION['discordloggedin'] = true;
                                $_SESSION['discordallowed'] = true;
                                $_SESSION['discordname'] = $discordname;
                                $_SESSION['discordtoken'] = $discordtoken;
                                $_SESSION['authdate'] = strtotime(date("Y-m-d"));
            
                                // Later, now we do it based on sessions: save / update the userinfo in the database
                                header("Location: $discordredirect");
                                exit;
                                
                            } else {
                                // User has been authenticated but is has not verified his email adress via discord.
                                $_SESSION['discordloggedin'] = false;
                                $_SESSION['discordallowed'] = false;
                                echo "<div class='alert alert-info' role='alert'>";
                                echo "Please verify your email on discord to use the 2 factor authentication on this website.</div>";

                                unset($_SESSION['discordloggedin']);
                                unset($_SESSION['discordallowed']);
                                unset($_SESSION['discordname']);
                                unset($_SESSION['discordcode']);
                                unset($_SESSION['oauth2state']);
                                unset($_SESSION['discordtoken']);
                                header("Refresh: 5; URL=$discordredirect");
                                exit;
                            }
                        } else {
                            // User has been authenticated but is not allowed on restricted parts of the site
                            $_SESSION['discordloggedin'] = true;
                            $_SESSION['discordallowed'] = false;
                            $_SESSION['discordname'] = $discordname;
                            $_SESSION['discordcode'] = $discordusercode;
                            $_SESSION['discordrefreshcode'] = $discordusercode;
                            $_SESSION['authdate'] = strtotime(date("Y-m-d"));
            
                            header("Location: $discordredirect");
                            exit;
                        }
            
                    } else {
                        echo "<div class='alert alert-info' role='error'>";
                        echo "2 factor authentication failed, please try again.</div>";
            
                        header("Refresh: 5; URL=$discordredirecturi");
                        unset($_SESSION['discordloggedin']);
                        unset($_SESSION['discordallowed']);
                        unset($_SESSION['discordname']);
                        unset($_SESSION['discordcode']);
                        unset($_SESSION['oauth2state']);
                        unset($_SESSION['discordtoken']);
                        exit;
                    }

            } catch (Exception $e) {
                // Failed to get user details
                echo "<div class='alert alert-info' role='error'>";
                echo "2 factor authentication failed, please try again.</div>";

                unset($_SESSION['discordloggedin']);
                unset($_SESSION['discordallowed']);
                unset($_SESSION['discordname']);
                unset($_SESSION['discordcode']);
                unset($_SESSION['oauth2state']);
                unset($_SESSION['discordtoken']);
                header("Refresh: 5; URL=$discordredirecturi");

                exit;
            }
        }
    }
    ?>
</html>