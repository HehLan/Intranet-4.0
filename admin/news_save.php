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

if(isset($_POST['id_news'])) $id_news=$_POST['id_news'];
else exit;
		
if($id_news==0)
{
	$sql="INSERT INTO news (titre,texte,quand,invisible) 
	VALUES (:titre,:texte,NOW(),:invisible) ";
	$query=$connexion->prepare($sql);
	$query->bindValue('titre', $_POST['titre'], PDO::PARAM_STR);
	$query->bindValue('texte', $_POST['texte'], PDO::PARAM_STR);
	$query->bindValue('invisible', 0, PDO::PARAM_INT);
	if($query->execute())
	{
		header('Location: news.php');
	}
	else echo 'ERREUR INSERT';
	
}
else
{
	$sql="UPDATE news SET titre=:titre, texte=:texte, quand=NOW() WHERE id_news=:id";
	$query=$connexion->prepare($sql);
	$query->bindValue('titre', $_POST['titre'], PDO::PARAM_STR);
	$query->bindValue('texte', $_POST['texte'], PDO::PARAM_STR);
	$query->bindValue('id', $id_news, PDO::PARAM_INT);
	if($query->execute())
	{
		header('Location: news.php');
	}
	else echo 'ERREUR UPDATE';	
}		
		




?>