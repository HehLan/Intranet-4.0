<?php
session_start();
require_once('common/connect.php');
require_once('common/utils.php');

$con=false;

if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0)) $con=true;
}
/*if(!$con)
{
	if(isset($_POST['login']) && isset($_POST['pwd']))
	{
		$sql="SELECT id_joueur FROM joueurs WHERE pseudo=:login and password=:pwd";
		$query=$connexion->prepare($sql);
		$query->bindValue('login', $_POST['login'], PDO::PARAM_STR);
		$query->bindValue('pwd', sha1($_POST['pwd']), PDO::PARAM_STR);
		if($query->execute())
		{
			$rst = $query->fetch(PDO::FETCH_ASSOC);
			if(!is_null($rst['id_joueur']))
			{
				$_SESSION['id_joueur']=$rst['id_joueur'];
				$_SESSION['login']=$_POST['login'];
				$con=true;
			}
		}
		else  {echo 'ERREUR LOGIN SQL';}
	}
	else header('Location: index.php');
	
}*/
$id_tournoi=1;
if(isset($_GET['id'])) $id_tournoi=$_GET['id'];

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
	
	<link rel="icon" href="img/logoheh.ico" >
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/getXhr.js"></script>

	

	
    
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
					echo 'Bienvenue à toi '.$_SESSION['login'].', <a href="common/deco.php">se déconnecter</a><br>';
					$sql="SELECT DISTINCT m.id_match, m.heure, t.nomTournoi 
					FROM (matchs as m, tournoi as t,matchs_joueurs as mj, matchs_equipes as me, equipes_joueur as ej)

					WHERE
						t.id_tournoi=m.id_tournoi AND 
						(
							(mj.id_joueur=:idj AND m.id_match=mj.id_match)
							OR
							(ej.id_joueur=:idj AND me.id_equipe=ej.id_equipes AND m.id_match=me.id_match)
						)
						AND m.heure>NOW()
					ORDER BY m.heure
					LIMIT 0,3";
					$query=$connexion->prepare($sql);
					$query->bindValue('idj', $_SESSION['id_joueur'], PDO::PARAM_INT);
					if($query->execute())
					{
						$next_matches= $query->fetchAll(PDO::FETCH_ASSOC);
					}
					else {echo 'ERREUR SQL NEXT MATCHES'; exit;}	
					$first=true;
					foreach($next_matches as $next_match)
					{
						if($first) echo '<strong>Prochains matchs</strong><br>';
						echo get_jour_de_la_semaine($next_match['heure']).' '.get_heure($next_match['heure']).' '.$next_match['nomTournoi'].'<br>';
						$first=false;
					}
				}
			?>
		</div>	     
 	</div>
 	
    <div id="navigation">
	<?php
		require_once('common/menuTop.php');
    ?>   
    </div>
	<div id="container">
		<div id="contenu">
			<?php
				$nbr_lb2=0;
				$nbr_lb3=0;
				$sql="SELECT COUNT(*) as nbr FROM matchs WHERE id_groupe IS NULL AND id_tournoi=:idt AND looser_bracket=2";
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
				if(!$query->execute()) {echo 'ERREUR SQL COUNT LB2'; exit;}
				else
				{
					$nbr_lb2=$query->fetch(PDO::FETCH_ASSOC);
					$nbr_lb2=$nbr_lb2['nbr'];
				}
				$sql="SELECT COUNT(*) as nbr FROM matchs WHERE id_groupe IS NULL AND id_tournoi=:idt AND looser_bracket=3";
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
				if(!$query->execute()) {echo 'ERREUR SQL COUNT LB3'; exit;}
				else
				{
					$nbr_lb3=$query->fetch(PDO::FETCH_ASSOC);
					$nbr_lb3=$nbr_lb3['nbr'];
				}			
				echo '<h1>';
				if ($id_tournoi!=2) echo 'Qualifications'; 
				echo $tournoi['nomTournoi'].'</h1>';
				if ($id_tournoi!=2)
				{
					echo 'Cliquez ici pour voir les <a href="finales.php?id='.$id_tournoi.'">FINALES DES PGM\'S (gold)</a><br>';
					if($nbr_lb2>0) echo 'Cliquez ici pour voir les <a href="finales.php?id='.$id_tournoi.'&lb=2">FINALES DES LOSERS (silver)</a><br>';
					if($nbr_lb3>0) echo 'Cliquez ici pour voir les <a href="finales.php?id='.$id_tournoi.'&lb=3">FINALES DES ULTRA NOOBS (bronze)</a><br>';
				}
				echo '<br>';
				
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
							/*$sql="SELECT me.id_match,m.heure, SUM(me.score) as score, 
								(SELECT me2.id_equipe FROM manches_equipes as me2 WHERE me2.id_match=me.id_match AND me2.id_equipe<>:ide LIMIT 0,1) as team2								
							FROM manches_equipes as me, matchs as m
							WHERE me.id_match=m.id_match and m.id_groupe=:idg
							AND me.id_equipe=:ide
							GROUP BY me.id_match";*/
							$sql="SELECT me.id_match,m.heure, SUM(me.score) as score, 
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
										$scores[$team['id']][$ligne['team2']]['id_match']=$ligne['id_match'];
									}	
									$heures[$team['id']][$ligne['team2']]=$ligne['heure'];
									
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
									if(isset($scores[$team['id']][$team2['id']]))
									{
										$couleur='loose_';
										$valeur=$scores[$team['id']][$team2['id']]['score'].'-'.$scores[$team2['id']][$team['id']]['score'];
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
									if ($valeur=='')
									{
										if(isset($heures[$team['id']][$team2['id']]))
										{
											$valeur=get_jour_de_la_semaine($heures[$team['id']][$team2['id']]).' '.get_heure($heures[$team['id']][$team2['id']]);
										}
									}	
									echo '<td class="td_'.$couleur.'pool_lol">'.$valeur.'</td>';
								}
								
							}
							echo '<td class="td_score_pool_lol">'.$totaux[$team['id']].'</td>	
								</tr>';
						 }
						  echo '</table><br><br>';
					}
					else
					{
						//-----------------TOURNOI TYPE UT TRACKMANIA-----------------
						
						$sql="SELECT nbr_manche, heure FROM matchs WHERE id_groupe=:idg LIMIT 0,1";
						$query=$connexion->prepare($sql);
						$query->bindValue('idg', $groupe['id_groupe'], PDO::PARAM_INT);
						$heure='';
						if($query->execute())
						{
							if($nbr_manches=$query->fetch(PDO::FETCH_ASSOC))
							{
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
								'.get_jour_de_la_semaine($heure).' '.get_heure($heure).'</th>
							</tr>
							<tr>
								<th class="th_part_pool_tm">Participant</th>';
								for($i=1;$i<=$nbr_manches;$i++) echo '<th class="th_manche_pool_tm">Manche '.$i.'</th>';
								echo '<th class="th_points_pool_tm">Points</th>
							</tr>';
						$inscrits='';	
						foreach($participants[$groupe['id_groupe']]	as $joueur)
						{
							$inscrits[$joueur['id']]['nom']=$joueur['nom'];
							$inscrits[$joueur['id']]['ok']=false;
						}
						foreach($totaux	as $joueur)
						{
							echo '<tr>
							<td class="td_pseudo_pool_tm">'.$joueur['pseudo'].'</td>';
							for($i=1;$i<=$nbr_manches;$i++)
							{
								echo '<td class="td_score_pool_tm">';
								if(isset($scores[$joueur['id_joueur']][$i]))
									echo $scores[$joueur['id_joueur']][$i];
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
								for($i=1;$i<=$nbr_manches;$i++) echo '<td class="td_score_pool_tm"></td>';	

								echo '<td class="td_total_pool_tm"></td>
								</tr>';
							}
						}
							
						echo '</table><br><br>';	
						
					}
				}
				
				
			?>
		</div>
		
		
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>

</body>
</html>