<?php

$host = "localhost";
$port = 3306;
$bdd = "hehlanbd";
$user = "hehlan";
$pwd = "1234";

try{
	//$connexion = new PDO('mysql:host='.$host.';port=3306;dbname='.$bdd,$user,$pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$connexion = new PDO('mysql:host=localhost;port=3306;dbname=hehlanbd','root','1234');
	$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}catch(Exception $e){
	echo "Une erreur est survenue";
	echo "Message = ".$e->getMessage();
	die();
}

?>