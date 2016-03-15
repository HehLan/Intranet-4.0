<?php
session_start();
require_once('modules/connect.php');

$con=false;

if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0) && $_SESSION['level']<=3) $con=true;
}
if(!$con)
{
 header('Location: ../index.php');
} 

if(isset($_GET['id_news']) && isset($_GET['invisible']))
{
 $id_news=$_GET['id_news'];
 $invi=$_GET['invisible'];
}
else exit;
		

	$sql="UPDATE news SET invisible=:invi WHERE id_news=:id";
	$query=$connexion->prepare($sql);
	$query->bindValue('invi', $invi, PDO::PARAM_INT);
	$query->bindValue('id', $id_news, PDO::PARAM_INT);
	if($query->execute())
	{
		header('Location: news.php');
	}
	else echo 'ERREUR EFFACER';	





?>