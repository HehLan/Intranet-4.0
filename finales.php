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
$looser=0;
if(isset($_GET['id'])) $id_tournoi=$_GET['id'];
if(isset($_GET['lb'])) $looser=$_GET['lb'];

$sql="SELECT * FROM tournoi WHERE id_tournoi=:id";
$query=$connexion->prepare($sql);
$query->bindValue('id', $id_tournoi, PDO::PARAM_INT);
if($query->execute())
{
	$tournoi = $query->fetch(PDO::FETCH_ASSOC);
}
else {echo 'ERREUR SQL TOURNOI'; exit;}
$jpt=$tournoi['joueurParTeam'];
/*
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

*/
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="img/logoheh.ico" >
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="js/getXhr.js"></script>
	<script type="text/javascript" src="js/jquery.gracket.js"></script>

	


	
    
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
			echo '<h1>Finales de '.$tournoi['nomTournoi'].'</h1>';
			echo 'Cliquez ici pour voir les <a href="tournois.php?id='.$id_tournoi.'">QUALIFICATIONS</a><br>';
			if($nbr_lb2>0) echo 'Cliquez ici pour voir les <a href="finales.php?id='.$id_tournoi.'&lb=2">FINALES DES LOSERS (silver)</a><br>';
			if($nbr_lb3>0) echo 'Cliquez ici pour voir les <a href="finales.php?id='.$id_tournoi.'&lb=3">FINALES DES ULTRA NOOBS (bronze)</a><br>';
			
			echo '<br>';
			$nbrmatch=0;
			if($jpt>1)
			{
				//-----------------TOURNOI TYPE LOL COD-----------------
					
				$sql="SELECT m.id_match,m.nom_match,m.heure,m.id_parent,m.id_enfant1, m.id_enfant2, m.nbr_manche
				FROM matchs as m
				WHERE m.id_tournoi=:idt AND m.id_groupe IS NULL AND m.looser_bracket=:looser
				ORDER BY m.id_parent";
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
				$query->bindValue('looser', $looser, PDO::PARAM_INT);
				$finale=0;
				$petite_finale=0;
				if($query->execute())
				{
					while($match=$query->fetch(PDO::FETCH_ASSOC))
					{
						$matches[$match['id_match']]['id']=$match['id_match'];
						$matches[$match['id_match']]['heure']=$match['heure'];
						$matches[$match['id_match']]['nom']=$match['nom_match'];
						$matches[$match['id_match']]['id_parent']=$match['id_parent'];
						$matches[$match['id_match']]['id_enfant1']=$match['id_enfant1'];
						$matches[$match['id_match']]['id_enfant2']=$match['id_enfant2'];
						$matches[$match['id_match']]['nbr_manche']=$match['nbr_manche'];
						if (is_null($match['id_parent']))
						{
							if(is_null($match['id_enfant1']) and is_null($match['id_enfant2']))
								$petite_finale=$match['id_match'];
							else 	
								$finale=$match['id_match'];
						}
						$nbrmatch++;
						$sql2="SELECT mte.id_equipe,e.nom,
							(SELECT SUM(ma.score) FROM manches_equipes as ma 
								WHERE ma.id_match=:idm AND ma.id_equipe=mte.id_equipe
								GROUP BY ma.id_equipe) as score
						FROM matchs_equipes as mte, equipes as e 
						WHERE mte.id_match=:idm and e.id_equipes=mte.id_equipe";
						$query2=$connexion->prepare($sql2);
						$query2->bindValue('idm', $match['id_match'], PDO::PARAM_INT);
						if($query2->execute())
						{
							$cpt=1;
							while($team=$query2->fetch(PDO::FETCH_ASSOC))
							{
								$matches[$match['id_match']][$cpt]['id']=$team['id_equipe'];
								$matches[$match['id_match']][$cpt]['nom']=$team['nom'];
								$matches[$match['id_match']][$cpt]['score']=$team['score'];
								$cpt++;
							}
						}
						else {echo 'ERREUR SQL TEAMS'; exit;}
					}	
				}
				else {echo 'ERREUR SQL MATCHES'; exit;}
				if($nbrmatch>0)
				{
					$esc=0;
					$niveau=0;
					$tablo='';
					$match_par_niveau='';
					$tablo[$niveau][1]=$matches[$finale]['id'];
					$match_par_niveau[0]=1;
					$niveau++;
					$match_par_niveau_max=1;

					while($esc==0)
					{
						$match_par_niveau[$niveau]=0;
						$mpn2=1;
						for($mpn=1;$mpn<=$match_par_niveau[$niveau-1];$mpn++)
						{
							$tablo[$niveau][$mpn2]=$matches[$tablo[$niveau-1][$mpn]]['id_enfant1'];
							if(!is_null($tablo[$niveau][$mpn2])) $mpn2++;
							$tablo[$niveau][$mpn2]=$matches[$tablo[$niveau-1][$mpn]]['id_enfant2'];
							if(!is_null($tablo[$niveau][$mpn2])) $mpn2++;
						}

						$match_par_niveau[$niveau]=$mpn2-1;
						if($match_par_niveau[$niveau]>$match_par_niveau[$niveau-1]) $match_par_niveau_max=$match_par_niveau[$niveau];
						$ok=true;
						for($mpn=1;$mpn<=$match_par_niveau[$niveau];$mpn++)
						{
							if(!is_null($matches[$tablo[$niveau][$mpn]]['id_enfant1']) or
							!is_null($matches[$tablo[$niveau][$mpn]]['id_enfant2'])) $ok=false;
						}
						if($ok)
						{
							$esc=1;
						}
						$niveau++;
					}
					$niveau--;
					
					if($petite_finale!=0)
					{
						$tablo[0][2]=$matches[$petite_finale]['id'];
						$match_par_niveau[0]=2;
					}
					
					echo '<table>
							<tr>';
					for($c=$niveau;$c>=0;$c--)		
					{
						echo '<th>Round '.(1+$niveau-$c).'</th>';
							
					}		
					echo '</tr><tr>';
					for($c=$niveau;$c>=0;$c--)
					{
						echo '<td>
								<table>';
						for($m=1;$m<=$match_par_niveau[$c];$m++)
						{
							$nom1='TBD';
							$nom2='TBD';
							$score1='';
							$score2='';
							if(isset($matches[$tablo[$c][$m]][1]['id']))
							{
								$nom1=$matches[$tablo[$c][$m]][1]['nom'];
								$score1=$matches[$tablo[$c][$m]][1]['score'];
							}	
							if(isset($matches[$tablo[$c][$m]][2]['id']))
							{
								$nom2=$matches[$tablo[$c][$m]][2]['nom'];
								$score2=$matches[$tablo[$c][$m]][2]['score'];
							}	
							$clr1='1';
							if($score1>$score2) $clr1='win';
							$clr2='2';
							if($score2>$score1) $clr2='win';
							$fleche='->';						
							if($c==0)
							{
								if($m==1) 	echo '<tr class="tr_arbre_vide"><td class="td_finale_vide" colspan="3">FINALE</td></tr>';
								if($m==2) 	echo '<tr class="tr_arbre_vide"><td class="td_finale_vide" colspan="3">Petite Finale</td></tr>';
								$fleche='';
							}
							if($score1=='') $score1=substr(get_jour_de_la_semaine($matches[$tablo[$c][$m]]['heure']),0,3);
							if($score2=='') $score2=get_heure($matches[$tablo[$c][$m]]['heure']);
							echo '<tr>
								<td class="td_arbre_gauche" rowspan="2">#'.$tablo[$c][$m].'</td>
								<td class="td_arbre_team'.$clr1.'">'.$nom1.'</td>
								<td class="td_arbre_score'.$clr1.'">'.$score1.'</td>
								<td class="td_arbre_droite" rowspan="2">'.$fleche.' '.$matches[$tablo[$c][$m]]['id_parent'].'</td>
							</tr>';
							echo '<tr>
								<td class="td_arbre_team'.$clr2.'">'.$nom2.'</td>
								<td class="td_arbre_score'.$clr2.'">'.$score2.'</td>							
							</tr>';		
							echo '<tr class="tr_arbre_vide"><td class="td_arbre_vide" colspan="3"></td></tr>';
						}
							
						echo '</table></td>';
					}
					echo '</tr>
					</table>';	
				}
				else				
				{
					echo 'Ce tournoi n\'est pas encore encodé dans la base de données du site';
				}				
			}	
			else
			{
			//------------------------------TOURNOI TYPE TM UT--------------------------------------
				$sql="SELECT m.id_match,m.nom_match,m.heure,m.id_parent,m.id_enfant1, m.id_enfant2, 
				m.nbr_manche, m.teamParMatch as mtpm, t.teamParMatch as ttpm
				FROM matchs as m, tournoi as t
				WHERE m.id_tournoi=:idt AND t.id_tournoi=:idt AND m.id_groupe IS NULL AND m.looser_bracket=:looser
				ORDER BY m.id_parent";
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $id_tournoi, PDO::PARAM_INT);
				$query->bindValue('looser', $looser, PDO::PARAM_INT);
				$finale=0;
				$petite_finale=0;
				
				if($query->execute())
				{
					while($match=$query->fetch(PDO::FETCH_ASSOC))
					{
						$nbrmatch++;
						$matches[$match['id_match']]['id']=$match['id_match'];
						$matches[$match['id_match']]['heure']=$match['heure'];
						$matches[$match['id_match']]['nom']=$match['nom_match'];
						$matches[$match['id_match']]['id_parent']=$match['id_parent'];
						$matches[$match['id_match']]['id_enfant1']=$match['id_enfant1'];
						$matches[$match['id_match']]['id_enfant2']=$match['id_enfant2'];
						$matches[$match['id_match']]['nbr_manche']=$match['nbr_manche'];
						$matches[$match['id_match']]['mtpm']=$match['mtpm'];
						$matches[$match['id_match']]['ttpm']=$match['ttpm'];
						if (is_null($match['id_parent']))
						{
							if(is_null($match['id_enfant1']) and is_null($match['id_enfant2']))
								$petite_finale=$match['id_match'];
							else 	
								$finale=$match['id_match'];
						}
						
						$sql2="SELECT mtj.id_joueur,j.pseudo,
							(SELECT SUM(ma.score) FROM manches_joueurs as ma 
								WHERE ma.id_match=:idm AND ma.id_joueur=mtj.id_joueur
								GROUP BY ma.id_joueur) as score
						FROM matchs_joueurs as mtj, joueurs as j 
						WHERE mtj.id_match=:idm and j.id_joueur=mtj.id_joueur
						ORDER BY score DESC";
						$query2=$connexion->prepare($sql2);
						$query2->bindValue('idm', $match['id_match'], PDO::PARAM_INT);
						if($query2->execute())
						{
							$cpt=0;
							while($team=$query2->fetch(PDO::FETCH_ASSOC))
							{
								$cpt++;
								$matches[$match['id_match']][$cpt]['id']=$team['id_joueur'];
								$matches[$match['id_match']][$cpt]['nom']=$team['pseudo'];
								$matches[$match['id_match']][$cpt]['score']=$team['score'];
								
							}
							$matches[$match['id_match']]['nbr_joueurs']=$cpt;
						}
						else {echo 'ERREUR SQL JOUEURS'; exit;}
						
						
						$sql2="SELECT mj.id_joueur, mj.numero_manche, mj.score
						FROM manches_joueurs as mj
						WHERE mj.id_match=:idm
						ORDER BY mj.id_joueur";
						$query2=$connexion->prepare($sql2);
						$query2->bindValue('idm', $match['id_match'], PDO::PARAM_INT);
						if($query2->execute())
						{
							$nbrmax=0;
							$nbrm=0;
							$old_idj=0;
							while($ligne=$query2->fetch(PDO::FETCH_ASSOC))
							{
								if($old_idj!=$ligne['id_joueur'])
								{
									$nbrm=0;
									$old_idj=$ligne['id_joueur'];
								}
								$scores[$match['id_match']][$ligne['id_joueur']][$ligne['numero_manche']] = $ligne['score'];
								
								if($old_idj==$ligne['id_joueur'])
								{
									$nbrm++;
									if($nbrm>$nbrmax) $nbrmax=$nbrm;
								}
							}
						}
						else {echo 'ERREUR SQL MANCHES'; exit;}	
		
						if($nbrmax>$matches[$match['id_match']]['nbr_manche']) $matches[$match['id_match']]['nbr_manche']=$nbrmax;
						
						
						
					}	
				}			
				else {echo 'ERREUR SQL MATCHES'; exit;}	
				if($nbrmatch==1)
				{
				$finale=$petite_finale;
				$petite_finale=0;
				}
				if($nbrmatch!=0)
				{
					$esc=0;
					$niveau=0;
					$tablo='';
					$match_par_niveau='';
					$tablo[$niveau][1]=$matches[$finale]['id'];
					$match_par_niveau[0]=1;
					$niveau++;
					$match_par_niveau_max=1;

					while($esc==0)
					{
						$match_par_niveau[$niveau]=0;
						$mpn2=1;
						for($mpn=1;$mpn<=$match_par_niveau[$niveau-1];$mpn++)
						{
							$tablo[$niveau][$mpn2]=$matches[$tablo[$niveau-1][$mpn]]['id_enfant1'];
							if(!is_null($tablo[$niveau][$mpn2])) $mpn2++;
							$tablo[$niveau][$mpn2]=$matches[$tablo[$niveau-1][$mpn]]['id_enfant2'];
							if(!is_null($tablo[$niveau][$mpn2])) $mpn2++;
						}

						$match_par_niveau[$niveau]=$mpn2-1;
						if($match_par_niveau[$niveau]>$match_par_niveau[$niveau-1]) $match_par_niveau_max=$match_par_niveau[$niveau];
						$ok=true;
						for($mpn=1;$mpn<=$match_par_niveau[$niveau];$mpn++)
						{
							if(!is_null($matches[$tablo[$niveau][$mpn]]['id_enfant1']) or
							!is_null($matches[$tablo[$niveau][$mpn]]['id_enfant2'])) $ok=false;
						}
						if($ok)
						{
							$esc=1;
						}
						$niveau++;
					}
					$niveau--;
					
					if($petite_finale!=0)
					{
						$tablo[0][2]=$matches[$petite_finale]['id'];
						$match_par_niveau[0]=2;
					}
					echo '<table>
							<tr>';
					for($c=$niveau;$c>=0;$c--)		
					{
						echo '<th>Round '.(1+$niveau-$c).'</th>';
							
					}		
					echo '</tr><tr>';
					for($c=$niveau;$c>=0;$c--)
					{
						echo '<td>
								<table>';
						for($m=1;$m<=$match_par_niveau[$c];$m++)
						{
							$maxj=$matches[$tablo[$c][$m]]['nbr_joueurs'];
							if($matches[$tablo[$c][$m]]['mtpm']>$matches[$tablo[$c][$m]]['nbr_joueurs']) $maxj=$matches[$tablo[$c][$m]]['mtpm'];
							for($j=1;$j<=$maxj;$j++)
							{
								$nom[$j]='TBD';
								$score[$j]='';
							
								if(isset($matches[$tablo[$c][$m]][$j]['id']))
								{
									$nom[$j]=$matches[$tablo[$c][$m]][$j]['nom'];
									$score[$j]=$matches[$tablo[$c][$m]][$j]['score'];
								}
								
								$clr[$j]='';

								$fleche='->';	
								$heure=get_jour_de_la_semaine($matches[$tablo[$c][$m]]['heure']).' '.get_heure($matches[$tablo[$c][$m]]['heure']);
								
								if($j==1)
								{
									if($c==0)
									{
										if($m==1) 	echo '<tr class="tr_arbre_vide"><td class="td_finale_vide" colspan="'.($matches[$tablo[$c][$m]]['nbr_manche']+4).'">FINALE<br>'.$heure.'</td></tr>';
										if($m==2) 	echo '<tr class="tr_arbre_vide"><td class="td_finale_vide" colspan="'.($matches[$tablo[$c][$m]]['nbr_manche']+4).'">Petite Finale<br>'.$heure.'</td></tr>';
										$fleche='';
									}
									else
									{
										echo '<tr class="tr_arbre_vide"><td class="td_finale_vide" colspan="'.($matches[$tablo[$c][$m]]['nbr_manche']+4).'">'.$heure.'</td></tr>';
									}
									echo '<tr class="tr_arbre_vide">
										<td class="td_arbre_gauche"></td>
										<th class="th_arbre_joueur">Joueur</th>';
									for($ma=1;$ma<=$matches[$tablo[$c][$m]]['nbr_manche'];$ma++)
										echo '<th class="th_arbre_joueur">M'.$ma.'</th>';
											
									echo '<th class="th_arbre_joueur">Total</th>
											<td class="td_arbre_droite"></td>
										</tr>';
								}
								
								
								echo '<tr>';
								if($j==1) echo '<td class="td_arbre_gauche" rowspan="'.$maxj.'">#'.$tablo[$c][$m].'</td>';
								echo '<td class="td_arbre_joueur'.$clr[$j].'">'.$nom[$j].'</td>';
								for($ma=1;$ma<=$matches[$tablo[$c][$m]]['nbr_manche'];$ma++)
								{
									$score_ma='';
									if(isset($matches[$tablo[$c][$m]][$j]['id']))
									{
										$idj=$matches[$tablo[$c][$m]][$j]['id'];
										if(isset($scores[$tablo[$c][$m]][$idj][$ma])) $score_ma=$scores[$tablo[$c][$m]][$idj][$ma];
									
									}
									echo '<td class="td_arbre_joueur_score'.$clr[$j].'">'.$score_ma.'</td>';
								}		
								echo '<td class="td_arbre_joueur_total'.$clr[$j].'">'.$score[$j].'</td>';
								if($j==1) echo '<td class="td_arbre_droite" rowspan="'.$maxj.'">'.$fleche.' '.$matches[$tablo[$c][$m]]['id_parent'].'</td>';
								
								echo '</tr>';
								
							}
							echo '<tr class="tr_arbre_vide"><td class="td_arbre_vide" colspan="'.($matches[$tablo[$c][$m]]['nbr_manche']+4).'"></td></tr>';
						}
							
						echo '</table></td>';
					}
					echo '</tr>
					</table>';	
				}
				else				
				{
					echo 'Ce tournoi n\'est pas encore encodé dans la base de données du site';
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