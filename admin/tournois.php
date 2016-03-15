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
	/*function go_groupes(id)
	{
		document.location='groupes.php?id='+id;
	}*/
	function go_finales(id,parts,lb)
	{
		document.getElementById('input2_id_tournoi').value=id;
		document.getElementById('input2_looser').value=lb;
		document.getElementById('nbrpart2').innerHTML=parts;
		document.getElementById('shadowing').style.display='block';
		document.getElementById('div_popup2').style.visibility='visible';
	}	
	function go_finales2(id,parts,lb)
	{
		document.getElementById('input3_id_tournoi').value=id;
		document.getElementById('input3_looser').value=lb;
		document.getElementById('nbrpart3').innerHTML=parts;
		document.getElementById('shadowing').style.display='block';
		document.getElementById('div_popup3').style.visibility='visible';
	}		
	function go_groupes(id,parts)
	{
		document.getElementById('input_id_tournoi').value=id;
		document.getElementById('nbrpart').innerHTML=parts;
		document.getElementById('shadowing').style.display='block';
		document.getElementById('div_popup').style.visibility='visible';

	}
	function popup_close()
	{
		document.getElementById('shadowing').style.display='none';
		document.getElementById('div_popup').style.visibility='hidden';		
		document.getElementById('div_popup2').style.visibility='hidden';		
		document.getElementById('div_popup3').style.visibility='hidden';		
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
			$sql="SELECT * FROM tournoi ORDER BY nomTournoi";
			$query=$connexion->prepare($sql);
						
			if($query->execute())
			{
				$tournois = $query->fetchAll(PDO::FETCH_ASSOC);

			}
			else  {echo 'ERREUR TOURNOI SQL'; exit;}
			echo '<table id="adm_tablo">
					<tr>
						<th>id</th>
						<th>Tournoi</th>
						<th>Participants</th>
						<th>Joueurs par team</th>
						<th>Teams par match</th>
						<th>Nombre de manches</th>
						<th>Heure groupes</th>
						<th>Heure finales</th>
						<th>Durée inter match</th>
						<th>Gérer les groupes</th>
						<th>Gérer les finales</th>
						<th>Looser 1</th>
						<th>Looser 2</th>
					</tr>';	
			foreach($tournois as $tournoi)
			{
				if($tournoi['joueurParTeam']==1)
				{
					$sql="SELECT COUNT(*) as nbr FROM joueurtournoi WHERE id_tournoi=:idt";
				}
				else
				{
					$sql="SELECT COUNT(*) as nbr FROM equipes_tournoi WHERE id_tournoi=:idt";
				}
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $tournoi['id_tournoi'], PDO::PARAM_STR);
				if($query->execute())
				{
					$participants=$query->fetch(PDO::FETCH_ASSOC);
				}
				else  {echo 'ERREUR TOURNOI SQL'; exit;}
				
				$sql="SELECT COUNT(*) as nbr FROM groupes_pool WHERE id_tournoi=:idt";
				$query=$connexion->prepare($sql);
				$query->bindValue('idt', $tournoi['id_tournoi'], PDO::PARAM_STR);
				if($query->execute())
				{
					$groupes_exist=$query->fetch(PDO::FETCH_ASSOC);
				}
				else  {echo 'ERREUR COUNT GROUPE SQL'; exit;}	
				
				/*--------------------
				rajouter la modif des parametres
				activer ou pas les boutons en fonction de :
				pas de modif s'il y a deja des matchs joués -> des manches
				pas de groupes si idem (manche de groupe)
				pas de finales si idem (manche de finale)
				
				prévoir cration d'un tournoi //
				
				et de tournoi sans pool
				---------------------*/
				echo'<tr>
						<td>'.$tournoi['id_tournoi'].'</td>
						<td>'.$tournoi['nomTournoi'].'</td>
						<td><strong>'.$participants['nbr'].'</strong></td>
						<td>'.$tournoi['joueurParTeam'].'</td>
						<td>'.$tournoi['teamParMatch'].'</td>
						<td>'.$tournoi['nombreManche'].'</td>
						<td>'.$tournoi['heure_groupe_start'].'</td>
						<td>'.$tournoi['heure_finale_start'].'</td>
						<td>'.$tournoi['duree_inter_match'].'</td>
						<td>';
						if (!existe_manche_de_groupe($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam']))
						{
								echo '<input type="button" value="Créer" onclick="go_groupes('.$tournoi['id_tournoi'].','.$participants['nbr'].')"/>';					
						}	
						echo ' <a href="scores.php?id_tournoi='.$tournoi['id_tournoi'].'">Scores</a></td>
						<td>';
						//if (!existe_manche_de_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],0) && $groupes_exist['nbr']>0)
						if (!existe_manche_de_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],0))
						{			

								if($tournoi['joueurParTeam']>1)
									echo '<input type="button" value="Créer" onclick="go_finales('.$tournoi['id_tournoi'].','.$participants['nbr'].',0)"/>';
								else
									echo '<input type="button" value="Créer" onclick="go_finales2('.$tournoi['id_tournoi'].','.$participants['nbr'].',0)"/>';
							
								echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=0">Modifier</a>';	
						}
						else
						{
								if(inscrits_en_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],0)) echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=0">Scores</a>';
						}	
						echo '</td>
						<td>';
						if (!existe_manche_de_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],2) )
						{

													
								if($tournoi['joueurParTeam']>1)
									echo '<input type="button" value="Créer" onclick="go_finales('.$tournoi['id_tournoi'].','.$participants['nbr'].',2)"/>';
								else
									echo '<input type="button" value="Créer" onclick="go_finales2('.$tournoi['id_tournoi'].','.$participants['nbr'].',2)"/>';
							echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=2">Modifier</a>';	
						}
						else
						{
								if(inscrits_en_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],2)) echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=2">Scores</a>';
						}							
						echo '</td>
						<td>';
						if (!existe_manche_de_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],3) )
						{
								if($tournoi['joueurParTeam']>1)
									echo '<input type="button" value="Créer" onclick="go_finales('.$tournoi['id_tournoi'].','.$participants['nbr'].',3)"/>';
								else
									echo '<input type="button" value="Créer" onclick="go_finales2('.$tournoi['id_tournoi'].','.$participants['nbr'].',3)"/>';
							echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=3">Modifier</a>';
						}
						else
						{
								if(inscrits_en_finale($connexion,$tournoi['id_tournoi'],$tournoi['joueurParTeam'],3)) echo '<a href="finales.php?id_tournoi='.$tournoi['id_tournoi'].'&looser=3">Scores</a>';
						}							
						echo '</td>
					</tr>';
			}
			
			echo '</table>';
			?>
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
		<form method="POST" action="groupes.php">
		<input type="hidden" name="id_tournoi" id="input_id_tournoi" value="0" />
		<input type="hidden" name="looser" id="input_looser" value="0" /><br>
		Nombre de participants : <span id="nbrpart"></span><br>
		Nombre de groupes : <input type="text" name="nbrgroupes" value="0" size="4"/><br>
		<input type="submit" value="Créer" /><br>
		</form>
	</div>
	<div id="div_popup2" class="popup_centree" style="height:200px;width:600px;margin-top:-100px;margin-left:-300px;">
		<input type="button" value="annuler" onclick="popup_close()" />
		<form method="POST" action="finales.php">
		<input type="hidden" name="id_tournoi" id="input2_id_tournoi" value="0" />
		<input type="hidden" name="looser" id="input2_looser" value="0" /><br>
		Nombre de participants : <span id="nbrpart2"></span><br>
		Petite finale ? : <input type="checkbox" name="petite_finale" value="1"/><br>
		Nombre de qualifiés : <select name="qualifs"><option>4<option>6<option>8<option>12<option>16<option>24<option>32<option>48<option>64<option>96<option>128</select><br>
		<input type="submit" value="Créer" /><br>
		</form>
	</div>	
	<div id="div_popup3" class="popup_centree" style="height:200px;width:600px;margin-top:-100px;margin-left:-300px;">
		<input type="button" value="annuler" onclick="popup_close()" />
		<form method="POST" action="finales.php">
		<input type="hidden" name="id_tournoi" id="input3_id_tournoi" value="0" />
		<input type="hidden" name="looser" id="input3_looser" value="0" /><br>
		Nombre de participants : <span id="nbrpart3"></span><br>
		Petite finale ? : <input type="checkbox" name="petite_finale" value="1"/><br>
		Nombre de groupes : <select name="nbrgroupes"><option>1<option>2<option>4<option>8<option>16</select><br>
		Taille des groupes : <input type="text" name="tgroupes" value="0"  size="4"/><br>
		<input type="submit" value="Créer" /><br>
		</form>
	</div>		
</body>
</html>