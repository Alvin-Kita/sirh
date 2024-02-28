<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <!--
    **********************
    ** ?  EnJoY It  ******
    ** © DSI PAREDES *****
    ** Juillet 2022 ******
    ** Designed and ******
    * Coded by ***********
    * Raphaël ************
    *** BARBIER **********
    **********************
    -->

    <script type="text/javascript"> var QWA={}; QWA.startTime=new Date().getTime(); </script>

<?php
$user = 'dev';
$pass = 'dev';

try
{
    // On se connecte à MySQL
    $bdd = new PDO('mysql:host=localhost;dbname=sirh;charset=utf8', $user, $pass);
}

catch(Exception $e)
{
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : '.$e->getMessage());
}


// Si tout va bien, on peut continuer

?>