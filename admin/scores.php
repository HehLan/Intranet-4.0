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
if(isset($_GET['id_tournoi'])) $id_tournoi=$_GET['id_tournoi'];

$sql="SELECT * FROM tournoi WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);
if($query->execute())
{
	$tournoi = $query->fetch(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR SQL TOURNOI'; exit;}

$groupes='';
$sql="SELECT * FROM groupes_pool WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);
if($query->execute())
{
	$groupes = $query->fetchAll(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR SQL GROUPES'; exit;}

$groupes='';
$sql="SELECT * FROM groupes_pool WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);
if($query->execute())
{
	$groupes = $query->fetchAll(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR SQL GROUPES'; exit;}
$jpt=$tournoi['joueurParTeam'];

foreach($groupes as $groupe)
{
	if($jpt>1)
	{
		$sql="SELECT e.id_equipes as id, e.nom as nom FROM equipes as e, equipes_groupes as g WHERE g.id_groupe=:idg and e.id_equipes=g.id_equipe";
		$query=$connexion->prepare($sql);
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
		if($query->execute())
		{
			$participants[$groupe['id_groupe']] = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		else {echo 'ERREUR SQL EQUIPES'; exit;}
	}
	else
	{
		$sql="SELECT j.id_joueur as id, j.pseudo as nom FROM joueurs as j, joueurs_groupes as g WHERE g.id_groupe=:idg and j.id_joueur=g.id_joueur";
		$query=$connexion->prepare($sql);
		$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
		if($query->execute())
		{
			$participants[$groupe['id_groupe']] = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		else {echo 'ERREUR SQL JOUEURS'; exit;}	
	}
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="../img/logoheh.ico" >
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/getXhr.js"></script>
    <script type="text/javascript">
	function active_score(idm,idt)
	{
		if(document.getElementById('score_m_'+idm+'_p_'+idt).disabled)
		{
			document.getElementById('score_m_'+idm+'_p_'+idt).disabled=false;
		}
		else
		{
			document.getElementById('score_m_'+idm+'_p_'+idt).disabled=true;
		}
	}
	function active_score2(idm,m,idt)
	{
		if(document.getElementById('score_m_'+idm+'_ma_'+m+'_p_'+idt).disabled)
		{
			document.getElementById('score_m_'+idm+'_ma_'+m+'_p_'+idt).disabled=false;
		}
		else
		{
			document.getElementById('score_m_'+idm+'_ma_'+m+'_p_'+idt).disabled=true;
		}
	}
	function popup_heure(idm)
	{
		document.getElementById('input_id_match').value=idm;
		document.getElementById('shadowing').style.display='block';
		document.getElementById('div_popup').style.visibility='visible';

	}
	function popup_close()
	{
		document.getElementById('shadowing').style.display='none';
		document.getElementById('div_popup').style.visibility='hidden';			
	}	
	</script>
</head>

<body style="background-color: #000;">

 	<div id="header">
		<div id="banner">
		    <a href="index.php">
		    <img src="img/logoheh.png" alt="HEHLan" width="250px">
		    </a>
		</div>
		<div id="login">
			<?php
				if($con)
				{
					echo 'Bienvenu à toi '.$_SESSION['login'].', <a href="../common/deco.php">se déconnecter</a><br>';
					
				}
			?>
		</div>	     
 	</div>
 	
    <div id="navigation">
	<?php
		require_once('modules/menuTop.php');
    ?>        
    </div>
	<div id="container">
		<div id="contenu">
			<?php
				echo '<h1>Qualifications '.$tournoi['nomTournoi'].'</h1>';
				echo '<form method="POST" action="scores_save.php">
				<input type="hidden" name="id_tournoi" value="'.$id_tournoi.'">
				<input type="SUBMIT" value="Enregistrer"><br>';
				
				foreach($groupes as $groupe)
				{
					if($jpt>1)
					{
					//-----------------TOURNOI TYPE LOL COD-----------------
						$nbrteam=0;
						$teams='';
						$scores='';
						foreach($participants[$groupe['id_groupe']] as $team)
						{
								$teams[$nbrteam]['nom']=$team['nom'];
								$teams[$nbrteam]['id']=$team['id'];
								$nbrteam++;
						}
		
						$heures='';
	
						
						foreach($teams as $team)
						{

							$sql="SELECT m.id_match,m.heure, SUM(me.score) as score, 
								(SELECT mte2.id_equipe FROM matchs_equipes as mte2 WHERE mte2.id_match=m.id_match AND mte2.id_equipe<>:ide LIMIT 0,1) as team2								
							FROM (matchs_equipes as mte, matchs as m) 
							LEFT JOIN (manches_equipes as me)
							ON (me.id_match=m.id_match AND me.id_equipe=:ide)
							WHERE m.id_groupe=:idg AND mte.id_match=m.id_match AND mte.id_equipe=:ide
							GROUP BY m.id_match";
							$query=$connexion->prepare($sql);
							$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
							$query->bindValue('ide', $team['id'], PDO::PARAM_INT);
							if($query->execute())
							{
								while($ligne=$query->fetch(PDO::FETCH_ASSOC))
								{
									if(!is_null($ligne['score']))
									{
										$scores[$team['id']][$ligne['team2']]['score']=$ligne['score'];
										
									}	
									$matchs[$team['id']][$ligne['team2']]['heure']=$ligne['heure'];
									$matchs[$team['id']][$ligne['team2']]['id_match']=$ligne['id_match'];
								}
							}
							else {echo 'ERREUR SQL SCORES TEAM 1'; exit;}
							
				
						}

						 echo '<table class="table_pool_lol">
							<tr>
								<th class="th_titre_pool_lol" colspan="'.($nbrteam+2).'">'.$groupe['nom_groupe'].'<th>
							</tr>
							<tr>
								<td class="td_vide_pool_lol"></td>';
							for($i=0;$i<$nbrteam;$i++) echo '<th class="th_team2_pool_lol">'.$teams[$i]['nom'].'</th>';
							echo '<th class="th_score_pool_lol">score</th></tr>';
							$teams2=$teams;
							$totaux='';
						 foreach($teams as $team)
						 {
							$totaux[$team['id']]=0;
							echo '<tr class="tr_pool_lol">
									<th class="th_team_pool_lol">'.$team['nom'].'</th>';
									
							foreach($teams2 as $team2)
							{	
								

								if ($team['id']==$team2['id']) echo '<td class="td_X_pool_lol">X</td>';
								else
								{
									$couleur='same_';
									$valeur='';
									if(isset($scores[$team['id']][$team2['id']]['score']))
									{
										$couleur='loose_';
										$valeur=$scores[$team['id']][$team2['id']]['score'];
										//echo 'id'.$team['id'].' '.$team['id'].'<br>';
										if ($scores[$team['id']][$team2['id']]['score']>$scores[$team2['id']][$team['id']]['score']) 
										{
											$totaux[$team['id']]+=3;
											$couleur='win_';
										}	
										if ($scores[$team['id']][$team2['id']]['score']==$scores[$team2['id']][$team['id']]['score']) 
										{
											$totaux[$team['id']]+=1;
											$couleur='same_';
										}		
											
									}	
		
											$heure=get_jour_de_la_semaine($matchs[$team['id']][$team2['id']]['heure']).' '.get_heure($matchs[$team['id']][$team2['id']]['heure']);
			
				
									echo '<td class="td_'.$couleur.'pool_lol"><a href="#" onclick="popup_heure('.$matchs[$team['id']][$team2['id']]['id_match'].')" >'.$heure.'</a><br>
									<input type="checkbox" name="cb_m_'.$matchs[$team['id']][$team2['id']]['id_match'].'_p_'.$team['id'].'" value="1" onclick="active_score('.$matchs[$team['id']][$team2['id']]['id_match'].','.$team['id'].')"> <input type="text" name="score_m_'.$matchs[$team['id']][$team2['id']]['id_match'].'_p_'.$team['id'].'" id="score_m_'.$matchs[$team['id']][$team2['id']]['id_match'].'_p_'.$team['id'].'" value="'.$valeur.'" size="4" disabled="disabled"></td>';
								}
								
							}
							echo '<td class="td_score_pool_lol">'.$totaux[$team['id']].'</td>	
								</tr>';
						 }
						echo '				 
						  </table><br><br>';
					}
					else
					{
						//-----------------TOURNOI TYPE UT TRACKMANIA-----------------
						$sql="SELECT id_match,nbr_manche, heure FROM matchs WHERE id_groupe=:idg LIMIT 0,1";
						$query=$connexion->prepare($sql);
						$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
						if($query->execute())
						{
							if($nbr_manches=$query->fetch(PDO::FETCH_ASSOC))
							{
								$id_match=$nbr_manches['id_match'];
								$heure=$nbr_manches['heure'];
								$nbr_manches=$nbr_manches['nbr_manche'];
							}
							else $nbr_manches=$tournoi['nombreManche'];
						}
						else {echo 'ERREUR SQL MANCHES'; exit;}	
	
						
						$sql="SELECT j.pseudo, mj.id_joueur, SUM(mj.score) as total
						FROM joueurs as j, manches_joueurs as mj, matchs as m 
						WHERE m.id_groupe=:idg AND mj.id_match=m.id_match and j.id_joueur=mj.id_joueur
						GROUP BY mj.id_joueur
						ORDER BY total DESC, j.pseudo";
						$query=$connexion->prepare($sql);
						$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
						if($query->execute())
						{
							$totaux = $query->fetchAll(PDO::FETCH_ASSOC);
						}
						else {echo 'ERREUR SQL MANCHES'; exit;}									
						
						$sql="SELECT mj.id_joueur, mj.numero_manche, mj.score
						FROM manches_joueurs as mj, matchs as m 
						WHERE m.id_groupe=:idg AND mj.id_match=m.id_match 
						ORDER BY id_joueur";
						$query=$connexion->prepare($sql);
						$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
						if($query->execute())
						{
							while($ligne=$query->fetch(PDO::FETCH_ASSOC))
							{
								$scores[$ligne['id_joueur']][$ligne['numero_manche']] = $ligne['score'];
							}
						}
						else {echo 'ERREUR SQL MANCHES'; exit;}							
						
	
						
						
						echo '<table class="table_pool_tm">
							<tr>
								<th  class="titre_pool_tm" colspan="'.($nbr_manches+2).'">'.$groupe['nom_groupe'].'<br>
								<a href="#" onclick="popup_heure('.$id_match.')" >'.get_jour_de_la_semaine($heure).' '.get_heure($heure).'</a></th>
							</tr>
							<tr>
								<th class="th_part_pool_tm">Participant</th>';
								for($i=1;$i<=$nbr_manches;$i++) echo '<th class="th_manche_pool_tm">Manche '.$i.'<br></th>';
								echo '<th class="th_points_pool_tm">Points</th>
							</tr>';
						$inscrits='';	
						foreach($participants[$groupe['id_groupe']]	as $joueur)
						{
							$inscrits[$joueur['id']]['nom']=$joueur['nom'];
							$inscrits[$joueur['id']]['id']=$joueur['id'];
							$inscrits[$joueur['id']]['ok']=false;
						}
						foreach($totaux	as $joueur)
						{
							echo '<tr>
							<td class="td_pseudo_pool_tm">'.$joueur['pseudo'].'</td>';
							for($i=1;$i<=$nbr_manches;$i++)
							{
								$valeur='';
								echo '<td class="td_score_pool_tm">';
								if(isset($scores[$joueur['id_joueur']][$i]))
									$valeur=$scores[$joueur['id_joueur']][$i];
								
								echo '<input type="checkbox" 
									name="cb_m_'.$id_match.'_ma_'.$i.'_p_'.$joueur['id_joueur'].'" value="1" 
									onclick="active_score2('.$id_match.','.$i.','.$joueur['id_joueur'].')"> 
									<input type="text" name="score_m_'.$id_match.'_ma_'.$i.'_p_'.$joueur['id_joueur'].'" 
									id="score_m_'.$id_match.'_ma_'.$i.'_p_'.$joueur['id_joueur'].'" value="'.$valeur.'" size="4" disabled="disabled">';								
								echo '</td>';
							}
							echo '<td class="td_total_pool_tm">'.$joueur['total'].'</td>
							</tr>';
							$inscrits[$joueur['id_joueur']]['ok']=true;
						}		
						foreach($inscrits	as $inscrit)
						{
							if(!$inscrit['ok'])
							{
								echo '<tr>
								<td class="td_pseudo_pool_tm">'.$inscrit['nom'].'</td>';
								for($i=1;$i<=$nbr_manches;$i++) echo '<td class="td_score_pool_tm">
									<input type="checkbox" name="cb_m_'.$id_match.'_ma_'.$i.'_p_'.$inscrit['id'].'" 
									value="1" onclick="active_score2('.$id_match.','.$i.','.$inscrit['id'].')"> 
									<input type="text" name="score_m_'.$id_match.'_ma_'.$i.'_p_'.$inscrit['id'].'" 
									id="score_m_'.$id_match.'_ma_'.$i.'_p_'.$inscrit['id'].'" value="" size="4" disabled="disabled">
								</td>';	

								echo '<td class="td_total_pool_tm"></td>
								</tr>';
							}
						}
							
						echo '</table><br><br>';	
						
					}
				}
				
				
			?>
			</form>
		</div>
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>
	<div id="shadowing"></div>

	<div id="div_popup" class="popup_centree" style="height:200px;width:600px;margin-top:-100px;margin-left:-300px;">
		<input type="button" value="annuler" onclick="popup_close()" />
		<form method="POST" action="modifier_heure.php">
		<input type="hidden" name="id_match" id="input_id_match" value="0" />
		<input type="hidden" name="id_tournoi" value="<?php echo $id_tournoi; ?>" />
		<input type="hidden" name="page" value="scores" />
		vendredi <input type="radio" name="jour" value="vendredi"> / samedi <input type="radio" name="jour" value="samedi"> / dimanche <input type="radio" name="jour" value="dimanche"><br>
		Heure : <select name="heure">
				<?php 
					for($i=0;$i<24;$i++)
					{
						echo '<option>';
						if($i<10) echo '0';
						echo $i.'</option>';
					}
				?>	
				</select>h<select name="minute">
				<?php 
					for($i=0;$i<60;$i+=5)
					{
						echo '<option>';
						if($i<10) echo '0';
						echo $i.'</option>';
					}
				?>					
				</select><br>
		<input type="submit" value="Modifier" /><br>
	</div>
</body>
</html>