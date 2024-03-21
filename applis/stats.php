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

  <form action="" method="get" id="FormChoiceEnv">
    <label for="choiceEnv">Environment : </label>
    <select name="choiceEnv" id="choiceEnv">
      <option value="Sage X3" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Sage X3') echo 'selected'; ?>>Sage X3</option>
      <option value="e-Shop" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'e-Shop') echo 'selected'; ?>>e-Shop</option>
      <option value="Cash On Time" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Cash On Time') echo 'selected'; ?>>Cash On Time</option>
      <option value="Dynamics CRM" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Dynamics CRM') echo 'selected'; ?>>Dynamics CRM</option>
      <option value="Helpdesk" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Helpdesk') echo 'selected'; ?>>Helpdesk</option>
      <option value="Notilus" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Notilus') echo 'selected'; ?>>Notilus</option>
      <option value="Office365" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Office365') echo 'selected'; ?>>Office365</option>
      <option value="Orchestrade" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Orchestrade') echo 'selected'; ?>>Orchestrade</option>
      <option value="Power BI" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Power BI') echo 'selected'; ?>>Power BI</option>
      <option value="Silae" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'Silae') echo 'selected'; ?>>Silae</option>
      <option value="X3 Link" <?php if (isset($_GET['choiceEnv']) && $_GET['choiceEnv'] == 'X3 Link') echo 'selected'; ?>>X3 Link</option>
    </select>
    <input id="choiceEnv_button" type="submit" value="Envoyer!" />
  </form>

  <script>
    if (window.location.search.indexOf('choiceEnv') !== -1) {
      window.onload = function() {
        window.scrollTo(0, document.body.scrollHeight);
      };
    }
  </script>



  <?php

    // déclaration du tableau des noms des mois en Français
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


      //////////////////////////////////////////////////////////////////////////
     // DECLARATIONS DES FUNCTIONS DETERMINANT LA PLAGE DE TEMPS D'UN STATUS //
    //////////////////////////////////////////////////////////////////////////

    ///////////                       ///////////
    // Function d'interaction avec les plages //
    ///////////                     ///////////

    /**
     * Calcul le pourcentage de temps où un env était dans le status qui nous interesse sur une plage d'un mois
     * Ne marche pas pour les status "vert" qui seront determiné par élimination
     * @param $bdd string toujours mettre $bdd
     * @param $month int le numéro du mois qui nous interesse (si inferieur a 0 ajuste le mois et l'année pour correspondre)
     * @param $year int l'année qui nous interesse
     * @param $environnement string l'environnement à analyser
     * @param $status string la couleur de status d'ont on veux faire la stat
     * @return float|int pourcentage dans le mois du type de status prédéfini
     */
    function plageTimeMonth($bdd, $month, $year, $environnement, $status) {

        // Le mois étant à afficher étant déterminé par une autre fonction, le résultat peut être négatif
        // (exemple: mois actuel Février, et l'on veut les résultats de Décembre -> 2 - 3 = 0 Donc + 12)
        if ($month <= 0) {
            $month += 12;
            $year -= 1; // Le cas où l'on va chercher deux ans en arrière n'est pas pris en compte.
        }

        // Récupération de tous les status de la couleur $status pour le mois choisi
        $TotalStatusDuMois = $bdd->query("
          SELECT id, date_incident, Environnement, impact, status 
          FROM `applis_mailhistory`  
          WHERE Environnement = '$environnement' 
          AND MONTH(date_incident) = '$month'
          AND YEAR(date_incident) = '$year'
        ");

        // Pour chaque statut trouvé, on détermine le moment du retour à la normale
        // et (s'il y en a un) détermine la plage de temps pour l'ajouter au temps total du status
        $tempsTotal = 0;
        $lignePrecedente = "null"; // stocke la valeur de couleur de la dernière ligne pour ne pas faire de doublon du même status
        foreach ($TotalStatusDuMois as $ligne) {
          if ($lignePrecedente !== $status && $ligne['status'] === $status) { // Vérification que le status précédent est bien différent

            // Etape 1 :Trouver le status vert associé
            $statusOk = $bdd->query("
              SELECT *
              FROM applis_mailhistory
              WHERE Environnement = '$environnement'
              AND id > {$ligne['id']}
              AND status = 'green'
              ORDER BY id ASC
              LIMIT 1
            ");
            // Etape 2 : Determiner durée de la plage de temps du status au retour à la normale
            $statusOkData = $statusOk->fetch(PDO::FETCH_ASSOC); // Récupère la première ligne du résultat
            if ($statusOkData) {
                $plage = plageTime($bdd, $ligne['id'], $statusOkData['id']);

                // Etape 3 : Si l'on a bien un status vert associé on peut l'ajouter à la plage
                $tempsTotal += $plage;
            }
        }
    }

        // Valeurs pour le calcul de la moyenne par mois
        $nombreDeJours = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $nbsecondesTotaleParMois = 60 * 60 * 24 * $nombreDeJours;

        // Enfin retourne le pourcentage de temps occupé par le status défini en entrée de la fonction
        return round((($tempsTotal / $nbsecondesTotaleParMois) * 100), 2);
    }


    /**
     * Fait une jointure entre deux lignes de la table applis_mailhistory pour déterminer le temps écoulé entre les deux états
     * @param $bdd string toujours mettre $bdd
     * @param $id1 int Id de la requête de début de la plage
     * @param $id2 int Id de la requête de fin de la plage
     * @return mixed|string Temps écoulé entre les deux status.
     */
    function plageTime($bdd, $id1, $id2) {

      // Etape 1: la jointure
      $result = $bdd->query("
      SELECT 
        TIMESTAMPDIFF(SECOND, t1.date_incident, t2.date_incident) AS ecartSeconde
      FROM (
        SELECT date_incident 
        FROM applis_mailhistory
        WHERE id = '$id1'
      ) t1
      JOIN (
        SELECT date_incident 
        FROM applis_mailhistory
        WHERE id = '$id2'
      ) t2
    ");

      // Etape 2: Retourner le temps écoulé dans la jointure
      $toPrint = $result->fetchAll(PDO::FETCH_ASSOC);
      if (!empty($toPrint)) {
          return $toPrint[0]['ecartSeconde'];
      } else {
        return "Aucun résultat trouvé.";
      }

    }


    ///////////                               ///////////
    // Valeurs permettant d'interagir avec les durées //
    ///////////                             ///////////


    /**
     * Retourne le mois actuel moins $substract (évite les faux résultats en changeant d'année)
     * ATTENTION : Ne peux aller au-delà de 1 an en arrière !!!
     * @param $substract int nombre de mois à soustraire
     * @return int résultat
     */
    function getMonth($substract) {
        $month = date('m') - $substract;
        if ( $month <= 0) {
            return $month + 12;
        } else {
            return $month;
        }
    }

    /**
     * Retourne l'année actuel moins 1 si le mois recherché est moins 1 (évite les faux résultats en changeant d'année)
     *  ATTENTION : Ne peux aller au-delà de 1 an en arrière !!!
     * @param $substract int nombre de mois à soustraire
     * @return int résultat
     */
    function getYear($substract) {
        $actualYear = date('Y');
        $month = date('m') - $substract;
        if ($month <= 0) {
            return $actualYear - 1;
        } else {
            return $actualYear;
        }
    }


     ///////////                ///////////
    // Récupérer les valeurs à afficher //
   ///////////////            ///////////


    /**
     * Extrait de la base de données les données sur les 4 derniers mois
     * et les mets en forme en tableaux pour le graph
     * @param $bdd string Toujours mettre $bdd
     * @param $substract int nombre de moins avant le moins en cours
     * @param $status string Couleur du status recherché
     * @param $label string Message de status
     * @param $env string environnement cible
     * @return float Array ui servira à mettre les valeurs dans le graph
     */
    function getStats($bdd, $substract, $status, $env) {
        return (plageTimeMonth($bdd, getMonth($substract), getYear($substract), $env, $status));
    }

    if(isset($_GET['choiceEnv'])) {
        $choiceEnv = $_GET['choiceEnv'];
        $env = $choiceEnv;
    } else {
        $env = "E-shop";
    }


    // Indispo partielle -> orange
    $orange3 = plageTimeMonth($bdd, getMonth(3), getYear(3), $env, "orange");
    $orange2 = plageTimeMonth($bdd, getMonth(2), getYear(2), $env, "orange");
    $orange1 = plageTimeMonth($bdd, getMonth(1), getYear(1), $env, "orange");
    $orange0 = plageTimeMonth($bdd, getMonth(0), getYear(0), $env, "orange");
    $dataPointsIndispoPartielle = array(
        array("y" => $orange3, "label" => "Indisponibilité partielle"),
        array("y" => $orange2, "label" => "Indisponibilité partielle"),
        array("y" => $orange1, "label" => "Indisponibilité partielle"),
        array("y" => $orange0, "label" => "Indisponibilité partielle")
    );

    // Indispo maintenance -> cyan
    $cyan3 = plageTimeMonth($bdd, getMonth(3), getYear(3), $env, "cyan");
    $cyan2 = plageTimeMonth($bdd, getMonth(2), getYear(2), $env, "cyan");
    $cyan1 = plageTimeMonth($bdd, getMonth(1), getYear(1), $env, "cyan");
    $cyan0 = plageTimeMonth($bdd, getMonth(0), getYear(0), $env, "cyan");
    $dataPointsIndispoMaintenance = array(
        array("y" => $cyan3, "label" => "Indisponibilité pour maintenance"),
        array("y" => $cyan2, "label" => "Indisponibilité pour maintenance"),
        array("y" => $cyan1, "label" => "Indisponibilité pour maintenance"),
        array("y" => $cyan0, "label" => "Indisponibilité pour maintenance")
    );

    // Indispo totale -> red
    $red3 = plageTimeMonth($bdd, getMonth(3), getYear(3), $env, "red");
    $red2 = plageTimeMonth($bdd, getMonth(2), getYear(2), $env, "red");
    $red1 = plageTimeMonth($bdd, getMonth(1), getYear(1), $env, "red");
    $red0 = plageTimeMonth($bdd, getMonth(0), getYear(0), $env, "red");
    $dataPointsIndispoTotale = array(
        array("y" => $red3, "label" => "Indisponibilité totale"),
        array("y" => $red2, "label" => "Indisponibilité totale"),
        array("y" => $red1, "label" => "Indisponibilité totale"),
        array("y" => $red0, "label" => "Indisponibilité totale")
    );

    // Dispo -> green
    $green3 = 100 - ($red3 + $orange3 + $cyan3);
    $green2 = 100 - ($red2 + $orange2 + $cyan2);
    $green1 = 100 - ($red1 + $orange1 + $cyan1);
    $green0 = 100 - ($red0 + $orange0 + $cyan0);
    $dataPointsDispo = array(
        array("y" => $green3, "label" => "Disponibilité"),
        array("y" => $green2, "label" => "Disponibilité"),
        array("y" => $green1, "label" => "Disponibilité"),
        array("y" => $green0, "label" => "Disponibilité")
    );

    $toto1 = $bdd->query("
          SELECT id, date_incident, Environnement, impact, status 
          FROM `applis_mailhistory`  
          WHERE Environnement = 'Sage X3' 
          AND MONTH(date_incident) = '2'
          AND YEAR(date_incident) = 2024
        ");

    $dataPointsName = array(
        array("y" => 0, "label" => $m_fr[getMonth(3)]),
        array("y" => 0, "label" => $m_fr[getMonth(2)]),
        array("y" => 0, "label" => $m_fr[getMonth(1)]),
        array("y" => 0, "label" => $m_fr[getMonth(0)])
    );
    ?>



  <div id="chartContainer" style="height: 500px; width: 100%;"></div>
  <script type="text/javascript">

    $(function () {
      var chart = new CanvasJS.Chart("chartContainer",
        {
          animationEnabled: true,
          title:{
            text: `SLA Applicatifs <?php echo $env; ?>`
          },
          axisY: {
            suffix: "%",
            minimum: 97,
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
              dataPoints: <?php echo json_encode($dataPointsDispo, JSON_NUMERIC_CHECK);?>
            },
            {
              title: "Indisponibilité partielle",
              yValueFormatString: "0.0\"%\"",
              type: "stackedColumn",
              color: "orange",

              legendText: "Indisponibilité partielle",
              showInLegend: "true",
              explodeOnClick: true,
              dataPoints: <?php echo json_encode($dataPointsIndispoPartielle, JSON_NUMERIC_CHECK);?>
            },
            {
              title: "Indisponibilité pour maintenance",
              yValueFormatString: "0.0\"%\"",
              type: "stackedColumn",
              color: "#55e0e0",

              legendText: "Indisponibilité pour maintenance",
              showInLegend: "true",
              explodeOnClick: true,
              dataPoints: <?php echo json_encode($dataPointsIndispoMaintenance, JSON_NUMERIC_CHECK);?>
            },
            {
              title: "Indisponibilité totale",
              yValueFormatString: "0.0\"%\"",
              type: "stackedColumn",
              color: "red",
              legendText: "Indisponibilité totale",
              showInLegend: "true",
              explodeOnClick: true,
              dataPoints: <?php echo json_encode($dataPointsIndispoTotale, JSON_NUMERIC_CHECK);?>
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


</div>

<?php
include('/var/www/html/sirh/footer.php');

$reponse->closeCursor(); // Termine le traitement de la requête

?>
</body>
</html>
