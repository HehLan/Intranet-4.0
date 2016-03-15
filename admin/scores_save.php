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
} 

$id_tournoi=1;
if(isset($_POST['id_tournoi'])) $id_tournoi=$_POST['id_tournoi'];
else exit;

$sql="SELECT * FROM tournoi WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);
if($query->execute())
{
	$tournoi = $query->fetch(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR SQL TOURNOI'; exit;}

$jpt=$tournoi['joueurParTeam'];

if($jpt>1)
{
	$manches='';
	$sql="SELECT m.nbr_manche,m.id_match,me.id_equipe FROM matchs as m,matchs_equipes as me 
	WHERE m.id_tournoi=:idt AND m.id_groupe IS NOT NULL AND me.id_match=m.id_match";
	$query=$connexion->prepare($sql);
	$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
	if($query->execute())
	{
		$manches = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	else {echo 'ERREUR SQL GROUPES'; exit;}

	foreach($manches as $manche)
	{
		
		if(isset($_POST['score_m_'.$manche['id_match'].'_p_'.$manche['id_equipe']]))
		{

			$sql="INSERT INTO manches_equipes (id_match, numero_manche, id_equipe,score) VALUES (:idm,:nm,:ide,:sc)
			ON DUPLICATE KEY UPDATE score=:sc";
			$query=$connexion->prepare($sql);
			$query->bindValue('idm', $manche['id_match'], PDO::PARAM_INT);
			$query->bindValue('nm', 1, PDO::PARAM_INT);
			$query->bindValue('ide', $manche['id_equipe'], PDO::PARAM_INT);
			$query->bindValue('sc', $_POST['score_m_'.$manche['id_match'].'_p_'.$manche['id_equipe']], PDO::PARAM_INT);
			if(!$query->execute()) {echo 'ERREUR SQL INSERT SCORE TEAM'; exit;}
		
		}
	}
}
else
{
	$manches='';
	$sql="SELECT m.nbr_manche,m.id_match,mj.id_joueur FROM matchs as m,matchs_joueurs as mj 
	WHERE m.id_tournoi=:idt AND m.id_groupe IS NOT NULL AND mj.id_match=m.id_match";
	$query=$connexion->prepare($sql);
	$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
	if($query->execute())
	{
		$manches = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	else {echo 'ERREUR SQL GROUPES'; exit;}

	foreach($manches as $manche)
	{
		for($i=1;$i<=$manche['nbr_manche'];$i++)
		{
			if(isset($_POST['score_m_'.$manche['id_match'].'_ma_'.$i.'_p_'.$manche['id_joueur']]))
			{
				$sql="INSERT INTO manches_joueurs (id_match, numero_manche, id_joueur,score) VALUES (:idm,:nm,:idj,:sc)
				ON DUPLICATE KEY UPDATE score=:sc";
				$query=$connexion->prepare($sql);
				$query->bindValue('idm', $manche['id_match'], PDO::PARAM_INT);
				$query->bindValue('nm', $i, PDO::PARAM_INT);
				$query->bindValue('idj', $manche['id_joueur'], PDO::PARAM_INT);
				$query->bindValue('sc', $_POST['score_m_'.$manche['id_match'].'_ma_'.$i.'_p_'.$manche['id_joueur']], PDO::PARAM_INT);
				if(!$query->execute()) {echo 'ERREUR SQL INSERT SCORE TEAM'; exit;}
			
			}
		}
	}
}
header('Location: scores.php?id='.$id_tournoi);

?>