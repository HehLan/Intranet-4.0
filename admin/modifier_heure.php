<?php
session_start();
require_once('modules/connect.php');
require_once('../common/utils.php');
$con=false;



if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0) && $_SESSION['level']<=3) $con=true;
}
if(!$con)
{
 header('Location: ../index.php');
 exit;
} 
$id_tournoi=0;
if(isset($_POST['id_tournoi']) && isset($_POST['id_match']))
{ 

	$page=$_POST['page'];
	$id_tournoi=$_POST['id_tournoi'];
	$id_match=$_POST['id_match'];
	$jour=get_variable($connexion,$_POST['jour']);
	$jour=$jour.' '.$_POST['heure'].':'.$_POST['minute'].':00';

	$sql="UPDATE matchs SET heure=:heure WHERE id_match=:id";
	$query=$connexion->prepare($sql);
	$query->bindValue('id', $id_match, PDO::PARAM_INT);
	$query->bindValue('heure', $jour, PDO::PARAM_STR);
	if($query->execute())
	{
		header('Location: '.$page.'.php?id_tournoi='.$id_tournoi.'&looser='.$_POST['looser']);
	}
	else echo 'ERREUR UPDATE HEURE';





}
?>