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

$sql="SELECT id_match as id,nbr_manche,teamParMatch as tpm FROM matchs WHERE id_tournoi=:idt AND id_groupe IS NULL";
$query=$connexion->prepare($sql);
$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);	
if(!$query->execute()) {echo 'ERREUR SQL MATCHS'; exit;}
else
{
	$matchs = $query->fetchAll(PDO::FETCH_ASSOC);
}

if($jpt>1)
{
	
	foreach($matchs as $match)
	{
		if(isset($_POST['m_'.$match['id'].'_1']))
		{
			$sql="DELETE FROM manches_equipes WHERE id_match=:idm";
			$query=$connexion->prepare($sql);
			$query->bindValue('idm', $match['id'], PDO::PARAM_INT);	
			if(!$query->execute()) {echo 'ERREUR SQL DELETE MANCHE_EQUIPE'; exit;}

			$sql="DELETE FROM matchs_equipes WHERE id_match=:idm";
			$query=$connexion->prepare($sql);
			$query->bindValue('idm', $match['id'], PDO::PARAM_INT);	
			if(!$query->execute()) {echo 'ERREUR SQL DELETE MATCH_EQUIPE'; exit;}			
			
			$id1=true;
			$id2=true;
			if($_POST['m_'.$match['id'].'_1']==0) $id1=false;
			if($_POST['m_'.$match['id'].'_2']==0) $id2=false;
			
			if($id1)
			{
				$sql="INSERT INTO matchs_equipes (id_match, id_equipe) VALUES (:idm,:id1)";
				$query=$connexion->prepare($sql);
				$query->bindValue('idm', $match['id'], PDO::PARAM_INT);			
				$query->bindValue('id1', $_POST['m_'.$match['id'].'_1'], PDO::PARAM_INT);			
				if(!$query->execute()) {echo 'ERREUR INSERT MATCH_EQUIPE 1'; exit;}	
			}
			if($id2)
			{
				$sql="INSERT INTO matchs_equipes (id_match, id_equipe) VALUES (:idm,:id2)";
				$query=$connexion->prepare($sql);
				$query->bindValue('idm', $match['id'], PDO::PARAM_INT);			
				$query->bindValue('id2', $_POST['m_'.$match['id'].'_2'], PDO::PARAM_INT);			
				if(!$query->execute()) {echo 'ERREUR INSERT MATCH_EQUIPE 2'; exit;}	
			}
					
		
		}
		if(isset($_POST['s_'.$match['id'].'_1']))
		{
			if($_POST['s_'.$match['id'].'_1']=='') $_POST['s_'.$match['id'].'_1']=0;
			$sql="INSERT INTO manches_equipes (id_match, numero_manche, id_equipe,score) 
			VALUES (:idm,:nm,:ide,:sc)
			ON DUPLICATE KEY UPDATE score=:sc";
			$query=$connexion->prepare($sql);
			$query->bindValue('idm', $match['id'], PDO::PARAM_INT);
			$query->bindValue('nm', 1, PDO::PARAM_INT);
			$query->bindValue('ide', $_POST['s_'.$match['id'].'_1_id'], PDO::PARAM_INT);
			$query->bindValue('sc', $_POST['s_'.$match['id'].'_1'], PDO::PARAM_INT);
			if(!$query->execute()) {echo 'ERREUR SQL INSERT SCORE TEAM 1'; exit;}		
		}
		if(isset($_POST['s_'.$match['id'].'_2']))
		{
			if($_POST['s_'.$match['id'].'_2']=='') $_POST['s_'.$match['id'].'_2']=0;
			$sql="INSERT INTO manches_equipes (id_match, numero_manche, id_equipe,score) 
			VALUES (:idm,:nm,:ide,:sc)
			ON DUPLICATE KEY UPDATE score=:sc";
			$query=$connexion->prepare($sql);
			$query->bindValue('idm', $match['id'], PDO::PARAM_INT);
			$query->bindValue('nm', 1, PDO::PARAM_INT);
			$query->bindValue('ide', $_POST['s_'.$match['id'].'_2_id'], PDO::PARAM_INT);
			$query->bindValue('sc', $_POST['s_'.$match['id'].'_2'], PDO::PARAM_INT);
			if(!$query->execute()) {echo 'ERREUR SQL INSERT SCORE TEAM 1'; exit;}		
		}		
	}

}
else
{
	
	foreach($matchs as $match)
	{
		$firstdel=true;
		for($j=1;$j<=$match['tpm'];$j++)
		{
			if(isset($_POST['m_'.$match['id'].'_'.$j]))
			{	
				if($firstdel)
				{
					$sql="DELETE FROM manches_joueurs WHERE id_match=:idm";
					$query=$connexion->prepare($sql);
					$query->bindValue('idm', $match['id'], PDO::PARAM_INT);	
					if(!$query->execute()) {echo 'ERREUR SQL DELETE MANCHE_JOUEUR'; exit;}

					$sql="DELETE FROM matchs_joueurs WHERE id_match=:idm";
					$query=$connexion->prepare($sql);
					$query->bindValue('idm', $match['id'], PDO::PARAM_INT);	
					if(!$query->execute()) {echo 'ERREUR SQL DELETE MATCH_JOUEUR'; exit;}

					$firstdel=false;
				}
				
				if($_POST['m_'.$match['id'].'_'.$j]!=0)
				{
					$sql="INSERT INTO matchs_joueurs (id_match, id_joueur) VALUES (:idm,:idj)";
					$query=$connexion->prepare($sql);
					$query->bindValue('idm', $match['id'], PDO::PARAM_INT);			
					$query->bindValue('idj', $_POST['m_'.$match['id'].'_'.$j], PDO::PARAM_INT);					
					if(!$query->execute()) {echo 'ERREUR INSERT MATCH_JOUEUR'; exit;}	
					
					for($m=1;$m<=$match['nbr_manche'];$m++)
					{
						if(isset($_POST['s_'.$match['id'].'_'.$j.'_'.$m]))
						{
						
							if($_POST['s_'.$match['id'].'_'.$j.'_'.$m]!='')
							{
								$sql="INSERT INTO manches_joueurs (id_match, numero_manche, id_joueur,score) 
								VALUES (:idm,:nm,:idj,:sc)
								ON DUPLICATE KEY UPDATE score=:sc";
								$query=$connexion->prepare($sql);
								$query->bindValue('idm', $match['id'], PDO::PARAM_INT);
								$query->bindValue('nm', $m, PDO::PARAM_INT);
								$query->bindValue('idj', $_POST['s_'.$match['id'].'_'.$j.'_id'], PDO::PARAM_INT);
								$query->bindValue('sc', $_POST['s_'.$match['id'].'_'.$j.'_'.$m], PDO::PARAM_INT);
								if(!$query->execute()) {echo 'ERREUR SQL INSERT SCORE JOUEUR 1'; exit;}
							}
						}
					}
				}	
			
			}
		}		
	}

}
header('Location: finales.php?id_tournoi='.$id_tournoi.'&looser='.$_POST['looser']);

?>