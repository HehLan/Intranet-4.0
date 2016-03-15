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
if(isset($_POST['id_tournoi'])) $id_tournoi=$_POST['id_tournoi'];
else exit;


$sql="SELECT * FROM tournoi WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);	
if($query->execute())
{
	$tournoi=$query->fetch(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR TOURNOI SQL'; exit;}

$jpt=$tournoi['joueurParTeam'];
$nomt=$tournoi['nomTournoi'];

$sql="SELECT * FROM groupes_pool WHERE id_tournoi=:id ORDER BY nom_groupe";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);	
if($query->execute())
{
	$groupes=$query->fetchALL(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR GROUPES READ SQL'; exit;}
$nbr_groupes=count($groupes);



foreach($groupes  as $groupe)
{

	$sql="DELETE FROM matchs WHERE id_tournoi=:idt AND id_groupe=:idg";
	$query=$connexion->prepare($sql);
	$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);	
	$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
	if(!$query->execute())
	{echo 'ERREUR MATCHS DELETE SQL'; exit;}
				
	if($jpt>1)
	{

		$sql="DELETE FROM equipes_groupes WHERE id_groupe=:idg";
		$query=$connexion->prepare($sql);
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
		if(!$query->execute())
		{echo 'ERREUR EQUIPE_GROUPE DELETE SQL'; exit;}	
	
		$sql="SELECT et.id_equipe as id FROM equipes_tournoi as et
		WHERE et.id_tournoi=:id";
		$query=$connexion->prepare($sql);
		$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);	
		if($query->execute())
		{
			$participants=$query->fetchAll(PDO::FETCH_ASSOC);
		}
		else {echo 'ERREUR PARTICIPANTS TEAM SQL'; exit;}
		
		foreach($participants as $parti)
		{
			if(isset($_POST['parti_'.$parti['id']]))
			{
				if ($_POST['parti_'.$parti['id']]==$groupe['id_groupe'])
				{
					$sql="INSERT INTO equipes_groupes (id_equipe,id_groupe)
					VALUES (:ide,:idg)";
					$query=$connexion->prepare($sql);
					$query->bindValue('ide', $parti['id'], PDO::PARAM_INT);	
					$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
					if(!$query->execute()) {echo 'ERREUR INSERT PARTICIPANTS GROUPE'; exit;}
				}
			}
		}

		$sql="SELECT id_equipe as id FROM equipes_groupes WHERE id_groupe=:idg";
		$query=$connexion->prepare($sql);
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
		if($query->execute()) 
		{
			$equipes=$query->fetchAll(PDO::FETCH_ASSOC);
		}
		else {echo 'ERREUR SELECT PARTICIPANTS GROUPE'; exit;}
		
		$nbr_equipes=count($equipes);
		
		$h_start=$tournoi['heure_groupe_start'];
		switch($nbr_equipes)
		{
			case 2 :	
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[1]['id']);
 
						break;
			case 3 :
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[1]['id']);			
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[2]['id']);	
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[2]['id']);													
						break;
			case 4 :
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[1]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[2]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[3]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[2]['id']);						
						break;
			case 5 :
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[1]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);	
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[2]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[4]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[4]['id'],$equipes[2]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[4]['id'],$equipes[0]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[3]['id']);
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[2]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[4]['id']);
						
						break;
			case 6 :
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[1]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[3]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[4]['id'],$equipes[5]['id']);											
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[2]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[4]['id'],$equipes[3]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[5]['id']);											
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[3]['id'],$equipes[5]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[4]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[0]['id']);											
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[5]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[0]['id'],$equipes[4]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[3]['id']);											
						$h_start=ajouter_heures($h_start,$tournoi['duree_inter_match']);
						
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[2]['id'],$equipes[4]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[1]['id'],$equipes[5]['id']);
						creer_match_equipe($connexion,$id_tournoi,$groupe['id_groupe'],$tournoi['nombreManche'],$tournoi['teamParMatch'],
											$h_start,$equipes[3]['id'],$equipes[0]['id']);											
												
						break;						
		
		}
		
	}
	else
	{
		$sql="DELETE FROM joueurs_groupes WHERE id_groupe=:idg";
		$query=$connexion->prepare($sql);
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
		if(!$query->execute())
		{echo 'ERREUR JOUEUR_GROUPE DELETE SQL'; exit;}	
	
		$sql="SELECT jt.id_joueur as id FROM joueurtournoi as jt
		WHERE jt.id_tournoi=:id";
		$query=$connexion->prepare($sql);
		$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);	
		if($query->execute())
		{
			$participants=$query->fetchAll(PDO::FETCH_ASSOC);
		}
		else {echo 'ERREUR PARTICIPANTS TEAM SQL'; exit;}		
		

		foreach($participants as $parti)
		{
			if(isset($_POST['parti_'.$parti['id']]))
			{
				if ($_POST['parti_'.$parti['id']]==$groupe['id_groupe'])
				{
					$sql="INSERT INTO joueurs_groupes (id_joueur,id_groupe)
					VALUES (:idj,:idg)";
					$query=$connexion->prepare($sql);
					$query->bindValue('idj', $parti['id'], PDO::PARAM_INT);	
					$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
					if(!$query->execute()) {echo 'ERREUR INSERT PARTICIPANTS GROUPE'; exit;}
				}
			}
		}	
		
		$sql="INSERT INTO matchs (id_tournoi,nbr_manche,teamParMatch,heure,id_groupe)
		VALUES (:idt,:nbrm,:tpm,:heure,:idg)";
		$query=$connexion->prepare($sql);
		$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);	
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);	
		$query->bindValue('nbrm', $tournoi['nombreManche'], PDO::PARAM_INT);	
		$query->bindValue('tpm', $tournoi['teamParMatch'], PDO::PARAM_INT);	
		$query->bindValue('heure', $tournoi['heure_groupe_start'], PDO::PARAM_INT);	
		if(!$query->execute()) {echo 'ERREUR INSERT MATCHS (JOUEURS)'; exit;}	
		
		$id_match=$connexion->lastInsertId();

		$sql="INSERT INTO matchs_joueurs (id_match,id_joueur)
			SELECT :idm,jg.id_joueur FROM 
			joueurs_groupes as jg WHERE jg.id_groupe=(
			SELECT m.id_groupe FROM matchs as m WHERE m.id_match=:idm)";
		$query=$connexion->prepare($sql);
		$query->bindValue('idm', $id_match, PDO::PARAM_INT);				
		if(!$query->execute()) {echo 'ERREUR INSERT MATCHS_JOUEURS'; exit;}
	}
	

	
}
header('Location: tournois.php');

?>