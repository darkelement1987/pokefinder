<?php

include '../../includes.php';

    $query = "SELECT latitude as lat, longitude as lon, pokestop_id, name, image, UNIX_TIMESTAMP(CONVERT_TZ(incident_expiration, '+00:00', @@global.time_zone)) as stop, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as scanned, UNIX_TIMESTAMP(CONVERT_TZ(incident_start, '+00:00', @@global.time_zone)) as start, incident_grunt_type as type FROM pokestop WHERE incident_expiration > utc_timestamp() ORDER BY scanned desc;";
    $result = $conn->query($query);
    $rocket_name = json_decode(file_get_contents('https://raw.githubusercontent.com/whitewillem/PMSF/develop/static/data/grunttype.json'), true);
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $jsonfile = new stdClass();
    if($result && $result->num_rows >= 1 ) {
    $jsonfile->data = [];
    while ($row = $result->fetch_object() ) {
        if($row->name == NULL){$row->name='Unknown';}
        if($row->image == NULL){$row->image='/images/Unknown.png';}
        $row->rgender = $rocket_name[$row->type]['grunt'];
        $row->rtype = $rocket_name[$row->type]['type'];
       if (empty($rocket_name[$row->type]['type'])) {
            $row->rtype = 'Unknown';
        }
        
        if (empty($rocket_name[$row->type]['grunt'])){$row->rtype='Rocket';}
        if($row->type == '41'){$row->rgender = 'Cliff';}
        if($row->type == '42'){$row->rgender = 'Arlo';}
        if($row->type == '43'){$row->rgender = 'Sierra';}
        if($row->type == '44'){$row->rgender = 'Giovanni';}

        $row->secreward = $rocket_name[$row->type]['second_reward'];
        if(!empty($rocket_name[$row->type]['encounters']['first'])){$row->onefirst = $rocket_name[$row->type]['encounters']['first'];}
        if(!empty($rocket_name[$row->type]['encounters']['second'])){$row->onesecond = $rocket_name[$row->type]['encounters']['second'];}

                    if (is_array($row->onefirst) || is_array($row->onesecond) || is_array($row->onethird)) {
                        for($x = 0; $x <= 2; $x++){
                            if (!empty($row->onefirst[$x])) {
                                $row->{"firstname" . $x} = $mon_name[ltrim((str_replace("_00","",$row->onefirst[$x])), '0')]['name'];
                                $row->{"firstrow" . $x} = '<a href="index.php?page=seen&pokemon=' . ltrim((str_replace("_00","",$row->onefirst[$x])), '0') . '"><img src=' . monPicAjax('pokemon', ltrim((str_replace("_00","",$row->onefirst[$x])), '0'), '0') . ' height=42 width=42></a>';
                                } else { 
                                $row->{"firstrow" . $x} = '';
                                $row->{"firstname" . $x} = '';
                                };
                                };
                                for($x = 0; $x <= 2; $x++) {
                                    if (!empty($row->onesecond[$x])) {
                                        $row->{"secondname" . $x} = $mon_name[ltrim((str_replace("_00","",$row->onesecond[$x])), '0')]['name'];
                                        $row->{"secondrow" . $x} = '<a href=index.php?page=seen&pokemon=' . ltrim((str_replace("_00","",$row->onesecond[$x])), '0') . '><img src=' . monPicAjax('pokemon', ltrim((str_replace("_00","",$row->onesecond[$x])), '0'), '0') . ' height=42 width=42></a>';
                                        } else {
                                            $row->{"secondrow" . $x} = '';                                                
                                            $row->{"secondname" . $x} = '';
                                            };
                                            };
                                            }
                                            $row->hidden = '';
                                            $row->image='<img class=pic height=42 width=42 src=' . $row->image . '>';
                                            $row->stopname = '<a href=index.php?page=pokestops&pokestop=' . $row->pokestop_id. '>' . $row->name . '</a>';
                                            $row->stopnamehidden = '<a href=index.php?page=pokestops&pokestop=' . $row->pokestop_id. '>' . $row->name . '</a>';
                                            $row->rgender = '<img height=42 width=42 src=' . (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . $row->rgender . '.png><span class=genderhide>' . $row->rgender . '</span> <img height=42 width=42 src=' . (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/images/' . $row->rtype . '.png><span class=typehide>' . $row->rtype . '</span>';
                                            $row->stop = date($clock, $row->stop);
                                            if ($row->secreward == 'true'){
                                                $row->chance15 = '<span hidden>' . $row->secondname0 . $row->secondname1 . $row->secondname2 . '</span>' . $row->secondrow0 . $row->secondrow1 . $row->secondrow2;
                                                $row->chance85 = '<span hidden>' . $row->firstname0 . $row->firstname1 . $row->firstname2 . '</span>' . $row->firstrow0 . $row->firstrow1 . $row->firstrow2;
                                                $row->chance100 = '-';
                                            } else {
                                                $row->chance15 = '-';
                                                $row->chance85 = '-';
                                                $row->chance100 = '<span hidden>' . $row->firstname0 . $row->firstname1 . $row->firstname2 . '</span>' . $row->firstrow0 . $row->firstrow1 . $row->firstrow2;
                                            }
                                            $jsonfile->data[]  =  $row;
                                            }
                                            print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                            } else {
                                                echo '{"data":[]}';
                                                }
                                                ?>