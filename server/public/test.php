<?php

 

   error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP

 

   // A partir de PHP 5.6, sinon les caractères accentués seront mal affichés

   ini_set('default_charset', 'iso8859-1');

 

 

function getmicrotime()

   {

   list($usec, $sec) = explode(" ",microtime());

   return ((float)$usec + (float)$sec);

   }

 

   $Date_start = getmicrotime();

 

 

// ******  Exemples de configuration selon les hébergements mutualisés ******

 

//      $DBhost  = "<Nom_de_la_Base>.mysql.db";

//      $DBowner = "<Nom_de_la_Base>";  // Ton login SQL

//      $DBName  = $DBowner;

 

 

// ******  Fin des exemples de configuration

 

 

// ******  Configuration - Debut ******

   $DBhost  = "127.0.0.1:3306/gjchoc";   // Par exemple

   $DBowner = "root";  // ton login SQL

   $DBpw    = "";  // ton password SQL

   $DBName  = "gjchoc";

// ******  Configuration - Fin ******

     

 

//  Titre

echo "<br> \n";

echo " Opérations sur une Base de Données <b>MySQL</b> via les commandes <b>mysqli</b><br>";

 

//  Version de PHP

 

echo "<br> \n";

echo "Version de PHP : <b>".phpversion()."</b><br> \n";

 

echo "<br> \n";

echo " Travaux dans la base '<b>" .$DBName. "</b>' sur le serveur '<b>" .$DBhost. "</b>' <br>";

 

$Table_SQL = "Tab_test_DB_SQLI";

 

//  Etablissement de la connexion SQL

$mysqli = new mysqli($DBhost, $DBowner, $DBpw, $DBName);

echo "<br> \n";

echo "Connexion à MySQL - Erreur=<b>(".$mysqli->connect_errno.")".$mysqli->connect_error."</b> <br> \n";

 

//  Supression de la table Tab_test_DB_SQLI

$mysqli->query("DROP TABLE IF EXISTS `Tab_test_DB_SQLI` ");    // Requête

echo "<br> \n";

echo "Supression de la table Tab_test_DB_SQLI - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

//  Création de la table Tab_test_DB_SQLI

$mysqli->query("CREATE TABLE `Tab_test_DB_SQLI` (  `Id` int(11) NOT NULL auto_increment,  `NOM` varchar(32) NOT NULL default '',  `PRENOM` varchar(32) NOT NULL default '', PRIMARY KEY  (`Id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 ");    // Requête

echo "<br> \n";

echo "Création de la table Tab_test_DB_SQLI - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

//  Ajout d'un Premier enregistrement

$mysqli->query("INSERT INTO `Tab_test_DB_SQLI`  values ('','DURANDAL','Michel')  ");    // Requête

echo "<br> \n";

echo "Ajout d'un Premier enregistrement - ('','DURANDAL','Michel') - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

//  Ajout d'un Deuxième enregistrement

$mysqli->query("INSERT INTO `Tab_test_DB_SQLI`  values ('','DURANDAL','Pierre')  ");    // Requête

echo "Ajout d'un Deuxième enregistrement - ('','DURANDAL','Pierre') - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

//  Ajout d'un Troisième enregistrement

$mysqli->query("INSERT INTO `Tab_test_DB_SQLI`  values ('','accents-éèàâ','Jean-Noël')  ");    // Requête

echo "Ajout d'un Troisième enregistrement - ('','accents-éèàâ','Jean-Noël') - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

 

//  SELECT dans la table Tab_test_DB_SQLI : NOM='DURANDAL'

 

$mysqli->real_query("SELECT Id, NOM, PRENOM FROM `Tab_test_DB_SQLI` WHERE NOM='DURANDAL' ");   // Requête

$res = $mysqli->use_result();

echo "<br> \n";

echo "Sélection dans la table NOM='DURANDAL' - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

$Count = 0;

while ($row = $res->fetch_assoc())

   {

   $Count     = $Count + 1;

   $NOM       = $row['NOM'];

   $PRENOM    = $row['PRENOM'];

   echo " - <b>".$Count."</b> - \$NOM=<b>".$NOM."</b> - \$PRENOM=<b>".$PRENOM."</b> <br> \n";

   }

 

 

//  SELECT dans la table Tab_test_DB_SQLI : Tous les enregistrements

 

$mysqli->real_query("SELECT Id, NOM, PRENOM FROM `Tab_test_DB_SQLI` ");   // Requête

$res = $mysqli->use_result();

echo "<br> \n";

echo "Sélection dans la table de tous les enregistrements - Erreur=<b>(".$mysqli->errno.")".$mysqli->error."</b> <br> \n";

 

$Count = 0;

while ($row = $res->fetch_assoc())

   {

   $Count     = $Count + 1;

   $NOM       = $row['NOM'];

   $PRENOM    = $row['PRENOM'];

   echo " - <b>".$Count."</b> - \$NOM=<b>".$NOM."</b> - \$PRENOM=<b>".$PRENOM."</b> <br> \n";

   }

 

 

// Fermeture de la connexion   

 

$Date_end = getmicrotime();

$Duree = $Date_end - $Date_start;

$Duree = sprintf("%01.2f", $Duree);

 

echo " <br>  Durée du traitement = <b>$Duree</b> secondes <br> \n";

?>