<?php
global $bdd;
include ('../sirh/config/connectdb.php');
include ('class_openeddays.php');

$user = 'dev';
$pass = 'dev';

$mysqli = new mysqli("localhost", $user, $pass, 'sirh');

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$mysqli->set_charset("utf8");
// On affiche chaque entrée une à une


$reponse = $bdd->query('SELECT * FROM applis_mailhistory ORDER BY date_incident DESC, Environnement ASC');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Statistiques SLA Applis, depuis le début de l'année</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel='stylesheet' id='style-css'  href='/sirh/css/style.css' type='text/css' media='all' />
    <!--[if lte IE 8]>
    <script src="/sirh/js/html5.js" type="text/javascript"></script>
    <![endif]-->


    <!-- stylesheets -->
    <!-- <link href="/assets/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="/assets/style.css" rel="stylesheet"> -->
    <link href="/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- scripts -->

    <!--[if lt IE 9 ]>
    <script src="/assets/js/html5shiv.min.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->

    <!--script src="/assets/js/	"></script-->
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>


    <script>
        $(function () {
            // #sidebar-toggle-button
            $('#sidebar-toggle-button').on('click', function () {
                $('#sidebar').toggleClass('sidebar-toggle');
                $('#page-content-wrapper').toggleClass('page-content-toggle');
                fireResize();
            });

            // sidebar collapse behavior
            $('#sidebar').on('show.bs.collapse', function () {
                $('#sidebar').find('.collapse.in').collapse('hide');
            });

            // To make current link active
            var pageURL = $(location).attr('href');
            var URLSplits = pageURL.split('/');

            //console.log(pageURL + "; " + URLSplits.length);
            //$(".sub-menu .collapse .in").removeClass("in");

            if (URLSplits.length === 5) {
                var routeURL = '/' + URLSplits[URLSplits.length - 2] + '/' + URLSplits[URLSplits.length - 1];
                var activeNestedList = $('.sub-menu > li > a[href="' + routeURL + '"]').parent();

                if (activeNestedList.length !== 0 && !activeNestedList.hasClass('active')) {
                    $('.sub-menu > li').removeClass('active');
                    activeNestedList.addClass('active');
                    activeNestedList.parent().addClass("in");
                }
            }

            function fireResize() {
                if (document.createEvent) { // W3C
                    var ev = document.createEvent('Event');
                    ev.initEvent('resize', true, true);
                    window.dispatchEvent(ev);
                }
                else { // IE
                    element = document.documentElement;
                    var event = document.createEventObject();
                    element.fireEvent("onresize", event);
                }
            }
        })
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</head>

<body onload="startPage()">
<div id="logo"><a href="./">
        <img src="/img/blank.gif" width="135" height="50"></a>
</div>
<div id="bandeau">
</div>
<h1>Statistiques SLA Applis, depuis le début de l'année</h1>
<div class="description">

</div>
<?php
include('/var/www/html/sirh/menu.php');
?>
<div class="table-wrap">
    <div style="margin-top: 5px;">
        <?php
        // include('/var/www/html/sirh/submenu.php');
        ?>
    </div>
    <table>
        <?php
        while ($donnees = $reponse->fetch()) {
            ?>
            <tr style="background-color:<?php
            switch($donnees['status']){
                case "green": /* Ok */
                    echo "rgba(90, 168, 90, 0.3)";
                    break;

                case "orange": /* Dégradé */
                    echo "rgba(255, 192, 77, 0.3)";
                    break;

                case "red": /* Inaccessible */
                    echo "rgba(255, 0, 0, 0.3)";
                    break;

                case "cyan": /* Maintenance */
                    echo "rgba(0, 155, 155, 0.3)";
                    break;

            }
            ?>;">
                <td><?php echo $donnees['date_incident'] ;?></td>
                <td><?php echo $donnees['Environnement'] ;?></td>

            </tr>

        <?php } ?>
    </table>
    <?php

    // déclaration du tableau des noms de jours en Français
    $m_fr[1] = "Janvier";
    $m_fr[2] = "Fevrier";
    $m_fr[3] = "Mars";
    $m_fr[4] = "Avril";
    $m_fr[5] = "Mai";
    $m_fr[6] = "Juin";
    $m_fr[7] = "Juillet";
    $m_fr[8] = "Aout";
    $m_fr[9] = "Septembre";
    $m_fr[10] = "Octobre";
    $m_fr[11] = "Novembre";
    $m_fr[12] = "Decembre";

    // récupération de la date du jour
    $aujourdhui = getdate();

    // récupération du mois en chiffre
    $mois = $aujourdhui['mon'];
    $moislong = $m_fr[$mois];
    $moislongUS = $aujourdhui['month'];

    // récupération de l'annee`
    $annee = $aujourdhui['year'];


    // stockage de la date complète dans la variable $dtfr
    $dtfr = "01 $moislong $annee";
    $dt = "01 $moislongUS $annee";

    $lastday = jourdansmois($mois, $annee);

    $ldfr = "$lastday $moislong $annee";
    $ld = "$lastday $moislongUS $annee";

    // echo $dtfr;
    echo "<br />";
    // echo $ldfr;
    echo "<br />";

    // retourne le résultat
    // return $dtfr;

    $nbjour = nb_jours("$dt","$ld");
    $indispo = 0 ;

    $nbminutes = $nbjour*16*60;
    echo "<tr>";
    echo "<td>$nbminutes min total ($nbjour jours ouvrés) en $moislong</td>";
    echo "<td>&nbsp;</td>";
    echo "<td><input type=text value=$indispo />min d'indispo en $moislong</td>";
    $percentDispo = ($nbminutes - $indispo) / $nbminutes ;
    $percentIndispo = $indispo / $nbminutes ;
    echo "</tr>";

    // echo $percent;
    ///////////////////////////////////

    $moisdernier = getdate(mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));

    $moisderniercourt = $moisdernier['mon'];
    $moisdernierlong = $m_fr[$moisderniercourt];
    $moisdernierlongUS = $moisdernier['month'];

    echo "<br />";
    echo "<hr />";
    // echo "<br />";

    // stockage de la date complète dans la variable $dtfr2
    $dtfr2 = "01 $moisdernierlong $annee";
    $dt2 = "01 $moisdernierlongUS $annee";

    $lastday2 = jourdansmois($moisderniercourt, $annee);

    $ldfr2 = "$lastday2 $moisdernierlong $annee";
    $ld2 = "$lastday2 $moisdernierlongUS $annee";

    // echo $dtfr2;
    // echo $ld2;
    // echo "<br />";
    // echo $ldfr2;
    // echo "<br />";

    // retourne le résultat
    // return $dtfr;

    $nbjour2 = nb_jours("$dt2","$ld2");

    $nbminutes2 = $nbjour2*16*60;

    $indispo2 = 110 ;

    echo "<tr>";
    echo "<td>$nbminutes2 min total ($nbjour2 jours ouvrés) en $moisdernierlong</td>";
    echo "<td>&nbsp;</td>";
    echo "<td><input type=text value=$indispo2 />min d'indispo en $moisdernierlong</td>";
    echo "<td>&nbsp;</td>";
    echo "<td>".($nbminutes2 - $indispo2)." min d'indispo en $moisdernierlong</td>";
    $percentDispo2 = ($nbminutes2 - $indispo2) / $nbminutes2 ;
    $percentIndispo2 = $indispo2 / $nbminutes2 ;
    echo "</tr>";

    // echo $percentIndispo2;
    echo "<br />";
    echo "<hr />";

    ?>
    <div id="chartContainer" style="height: 500px; width: 100%;"></div>

    <?php
    $dataPointsDispo = array(
        array("y" => 97, "label" => "Disponibilité"),
        array("y" => 99.6, "label" => "Disponibilité"),
        array("y" => ($percentDispo2*100), "label" => "Disponibilité"),
        array("y" => ($percentDispo*100), "label" => "Disponibilité")
    );
    $dataPointsIndispoPartielle = array(
        array("y" => 3, "label" => "Indisponibilité partielle"),
        array("y" => 0.4, "label" => "Indisponibilité partielle"),
        array("y" => ($percentIndispo2*100), "label" => "Indisponibilité partielle"),
        array("y" => ($percentIndispo*100), "label" => "Indisponibilité partielle")
    );
    $dataPointsIndispoMaintenance = array(
        array("y" => 0, "label" => "Indisponibilité pour maintenance"),
        array("y" => 0, "label" => "Indisponibilité pour maintenance"),
        array("y" => 0, "label" => "Indisponibilité pour maintenance"),
        array("y" => 0, "label" => "Indisponibilité pour maintenance")
    );
    $dataPointsIndispoTotale = array(
        array("y" => 0, "label" => "Indisponibilité totale"),
        array("y" => 0, "label" => "Indisponibilité totale"),
        array("y" => 0, "label" => "Indisponibilité totale"),
        array("y" => 0, "label" => "Indisponibilité totale")
    );
    $dataPointsName = array(
        array("y" => 0, "label" => "Janvier"),
        array("y" => 0, "label" => "Février"),
        array("y" => 0, "label" => "Mars"),
        array("y" => 0, "label" => "Avril")
    );
    ?>

    <script type="text/javascript">

        $(function () {
            var chart = new CanvasJS.Chart("chartContainer",
                {
                    animationEnabled: true,
                    title:{
                        text: "SLA Applicatifs SageX3"
                    },
                    axisY: {
                        suffix: "%",
                        minimum: 95,
                        maximum: 100
                    },
                    axisX: {
                        suffix:""
                    },
                    toolTip: {
                        shared: false
                    },
                    data: [
                        {
                            title: "Disponibilité",
                            yValueFormatString: "0.0\"%\"",
                            type: "stackedColumn",
                            color: "#5aa85a",
                            indexLabel: "{y}",
                            indexLabelPlacement: "inside",
                            legendText: "Disponibilité",
                            showInLegend: "true",
                            explodeOnClick: true,
                            dataPoints: <?php echo json_encode($dataPointsDispo, JSON_NUMERIC_CHECK); ?>
                        },
                        {
                            title: "Indisponibilité partielle",
                            yValueFormatString: "0.0\"%\"",
                            type: "stackedColumn",
                            color: "orange",
                            legendText: "Indisponibilité partielle",
                            showInLegend: "true",
                            dataPoints: <?php echo json_encode($dataPointsIndispoPartielle, JSON_NUMERIC_CHECK); ?>
                        },
                        {
                            title: "Indisponibilité pour maintenance",
                            yValueFormatString: "0.0\"%\"",
                            type: "stackedColumn",
                            color: "#55e0e0",
                            legendText: "Indisponibilité pour maintenance",
                            showInLegend: "true",
                            dataPoints: <?php echo json_encode($dataPointsIndispoMaintenance, JSON_NUMERIC_CHECK); ?>
                        },
                        {
                            title: "Indisponibilité totale",
                            yValueFormatString: "0.0\"%\"",
                            type: "stackedColumn",
                            color: "red",
                            legendText: "Indisponibilité totale",
                            showInLegend: "true",
                            dataPoints: <?php echo json_encode($dataPointsIndispoTotale, JSON_NUMERIC_CHECK); ?>
                        },
                        {
                            type: "stackedColumn",
                            dataPoints: <?php echo json_encode($dataPointsName); ?>
                        }
                    ]
                });
            chart.render();
        });
    </script>

    <?php
    // $reponse2 = $bdd->query('SELECT id,Environnement,date_incident,status FROM `applis_mailhistory` WHERE `applis_mailhistory`.`Environnement` = "Sage X3" AND `applis_mailhistory`.`date_incident` != "0000-00-00 00:00:00" ORDER BY `applis_mailhistory`.`id` ;');
    /*
    $reponse2 = $bdd->query('
        SELECT id,Environnement,date_incident,status,MIN(date_incident)
        AS debut_incident, MAX(date_incident)
        AS fin_incident FROM applis_mailhistory
        WHERE status = "orange" OR status = "green" AND Environnement = "Sage X3"
        GROUP BY `Destinataires` HAVING COUNT(CASE WHEN status = "orange" THEN 1 END) >= 1 AND COUNT(CASE WHEN status = "green" THEN 1 END) >= 1;
    ');
    */

    echo "<br />";

    $reponse2 = $bdd->query('
	SELECT * FROM `applis_mailhistory`
');

    // Test du retour
    /*$donnees2 = $reponse2->fetchAll();
    foreach($donnees2 as $donnee) {
        var_dump($donnee);
    }*/

    $donnees2 = $reponse2->fetchAll();
    foreach($donnees2 as $donnee) {
        if ($donnee['status'] === "red") {
            $ok = false;
            $id = $donnee['id'];
            $idVerif = $id;
            $dateRetourNormal = "";
            //verification que c'est bien la première itération
            $verif = false;
            while ($verif === "false") {
                $idVerif--;
                $entry = $bdd->query("SELECT * FROM 'applis_mailhistory' WHERE ID = $idVerif");
                $entry = $entry->fetch();
                if ($donnee['Environnement'] === $entry['Environnement'] && $entry['status'] === "green") { //C'est la première itération

                }
            }


            While ($ok === false) {
                $id++;
                $entry = $bdd->query("SELECT * FROM `applis_mailhistory` WHERE ID = $id");
                $entry = $entry->fetch();
                if ($entry['status'] === "green") {
                    $dateRetourNormal = $entry['date_incident'];
                    $ok = true;
                }
            }

            echo "$donnee[date_incident] Indisponibilité totale $donnee[Environnement] : $donnee[impact] --> retour à la normal $dateRetourNormal bg <br />";
        }

        elseif ($donnee['status'] === "orange") {
            $ok = false;
            $id = $donnee['id'];
            $dateRetourNormal = "";

            while ($ok === false) {
                $id++;
                $entry = $bdd->query("SELECT * FROM `applis_mailhistory` WHERE ID = $id");
                $entry = $entry->fetch();
                if ($entry['status'] === "green") {
                    $dateRetourNormal = $entry['date_incident'];
                    $ok = true;
                }
            }

            echo "$donnee[date_incident] Indisponibilité partielle $donnee[Environnement] : $donnee[impact] --> retour à la normal $dateRetourNormal <br />";
        }
    }


    /*
    while ($donnees2 = $reponse2->fetchAll()) {
        echo "<pre>";
        print_r($donnees2);
        echo "</pre>";
    }
    */


    while ($donnees2 = $reponse2->fetchAll()) {
        foreach($donnees2 as $donnee){
            if ($donnee['status'] === "green") {
                $fin = $donnee['date_incident'];
                echo "fin : ".$donnee['date_incident']."<br />";
            }
            elseif ($donnee['status'] === "orange") {
                $debut = $donnee['date_incident'];
                echo "début : ".$donnee['date_incident']."<br />";
            }
            elseif ($donnee['status'] === "red") {
                $debut = $donnee['date_incident'];
                echo "début : ".$donnee['date_incident']."<br />";
            }

        if(($debut !== null) AND ($fin !== null)){
            $start_datetime = new DateTime($debut);
            $diff = $start_datetime->diff(new DateTime($fin));

                if($diff->d < 1){
                    echo $diff->h.' Heures<br>';
                    echo $diff->i.' Minutes<br>';
                    echo '<br /><hr />';
                }
            }
        }
    }



    // $debut = null;
    // $fin = null;
    ?>

</div>

<?php
include('/var/www/html/sirh/footer.php');

$reponse->closeCursor(); // Termine le traitement de la requête

?>
</body>
</html>
