<?php
if (file_exists(__Dir__ . '/config/config.php')) {
include './includes.php';
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title><?= $title?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <link rel="icon" type="image/png" href="images/locamon.png"/>
        <script src="https://kit.fontawesome.com/155c09c805.js" crossorigin="anonymous"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/pokefinder.js"></script>
    </head>

    <body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
            <div class="topnav" id="myTopnav">
                <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark shadow-lg p-30 mb-30">
                    <a class="navbar-brand" href="index.php"><img src="images/locamon.png" alt="Logo" class="logo"> <?= $title?></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
            
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                        <span class="text-muted" id="menutitles"><b><u><h6>Pages</h6></u></b></span>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=pokefinder"><i class="fas fa-calculator"></i> Pokémon <span class="badge badge-secondary"><?= $moncount ?></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=rocket"><i class="fas fa-rocket"></i> Team Rocket <span class="badge badge-secondary"><?= $stopcount ?></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=quests"><i class="fas fa-shield-alt"></i> Quests <span class="badge badge-secondary"><?= $questcount ?></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=raids"><i class="fas fa-fist-raised"></i> Raids <span class="badge badge-secondary"><?= $raidcount ?></span></a>
                            </li>
                        </ul>
                        <?php if($socialon != false) {?>
                        <ul class="navbar-nav ml-auto">
                        <div class="dropdown-divider"></div>
                        <span class="text-muted" id="menutitles"><b><u><h6>Social</h6></u></b></span>
                        <?php if(!empty($discord)){ ?><li><a href="<?= $discord?>" class="nav-link"><i class="fab fa-discord"></i> <span class="social">Discord</span></a></li><?php }?>
                        <?php if(!empty($youtube)){ ?><li><a href="<?= $youtube?>" class="nav-link"><i class="fab fa-youtube"></i> <span class="social">Youtube</span></a></li><?php }?>
                        <?php if(!empty($whatsapp)){ ?><li><a href="<?= $whatsapp?>" class="nav-link"><i class="fab fa-whatsapp"></i> <span class="social">Whatsapp</span></a></li><?php }?>
                        <?php if(!empty($telegram)){ ?><li><a href="<?= $telegram?>" class="nav-link"><i class="fab fa-telegram"></i> <span class="social">Telegram</span></a></li><?php }?>
                        <?php if(!empty($facebook)){ ?><li><a href="<?= $facebook?>" class="nav-link"><i class="fab fa-facebook"></i> <span class="social">Facebook</span></a></li><?php }?>
                        <?php if(!empty($twitter)){ ?><li><a href="<?= $twitter?>" class="nav-link"><i class="fab fa-twitter"></i> <span class="social">Twitter</span></a></li><?php }?>
                        <?php if(!empty($instagram)){ ?><li><a href="<?= $instagram?>" class="nav-link"><i class="fab fa-instagram"></i> <span class="social">Instagram</span></a></li><?php }?>
                        <?php if(!empty($pinterest)){ ?><li><a href="<?= $pinterest?>" class="nav-link"><i class="fab fa-pinterest"></i> <span class="social">Pinterest</span></a></li><?php }?>
                        </ul>
                        <?php }?>
                    </div>
                </nav>
            </div>
            <div id="page-container">
                    <?php
            index();
            ?>
            </div>
		</div>
	</div>
</div>
    </body>

    </html>
    <?php } else { echo "Please fill out config.php.example and save as config.php";}?>