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
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript">
/**************************
 fonction recherche joueurs
 **************************/
    $(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#recherche_joueur').keyup( function(){
    $('#results').show();
    
    $('.ClassPseudo').hide();
    $('.ClassPseudo:contains("'+$('#recherche_joueur').val()+'")').each(function(){
        
        $(this).show();
        $('#results').hide();
    });
    
    
  });
    $('#recherche_equipe').keyup( function(){
    $('#results_equipe').show();
    
    $('.ClassEquipe').hide();
    $('.ClassEquipe:contains("'+$('#recherche_equipe').val()+'")').each(function(){
        
        $(this).show();
        $('#results_equipe').hide();
    });
    
    
  });
/*********************************************
alerte apparait lors du clic sur l'emplacement
**********************************************/
//colorie la case pour le pseudo
$('div').delegate('.ClassPseudo','click',function(e)
    {
    $('.place').css({background : "none"});
    $('#'+$(this).attr("value")).css({background : 'rgba(0, 119, 193, 0.8)'});
     });

        
// recupere id_equipes pour l'envoi en AJAX
 $('.ClassEquipe').click(function(e)
            {
            valeur = $(this).attr("value");
            $('.place').css({background : "none"});
                $.ajax({ 
		    type: "POST", 
		    url: "ajax/color_equipe.php",
		    data: "id_equipes="+valeur,
		    success : function(contenu,etat)
                        { 
			$( "#dialogEquipe_Emplacement" ).html(contenu);
                        }
		
                    });
      
            });


   });
/*****       
onglet
******/
	function show_tab(num)
	{
		if(num==1)
		{
			document.getElementById('tabs-1').style.display="";
			document.getElementById('tabs-2').style.display="none";
		}
		else
		{
			document.getElementById('tabs-2').style.display="";
			document.getElementById('tabs-1').style.display="none";	
		}
	}
	
    </script>
	 <style>
 a:hover /* Apparence au survol des liens */
{
text-decoration: underline;
color: green;
cursor:pointer;
}   
a.ClassPseudo{
display: block;
}
a.ClassEquipe{
display: block;
}

/*INFO-BULLE*/
.tooltip
{
  position: relative;
  background-color:;
  cursor: help;
  display: block;
  text-decoration: none;
  color: #222;
  outline: none;
  font-size: medium;
 z-index: 998;  
}

.tooltip span
{
  visibility: hidden;
  position: absolute;
  bottom: -50px;
  left: 50%;
  z-index: 999;
  width: 230px;
  margin-left: -127px;
  padding: 10px;
  border: 2px solid #ccc;
  opacity: .9;
  background-color: #ddd;
  background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
  -moz-border-radius: 4px;
  border-radius: 4px;
  -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
  text-shadow: 0 3px  grey);
}

.tooltip:hover
{
  border: 0; /* IE6 fix */
}

.tooltip:hover span
{
  visibility: visible;
  background-color:#ff0000;
}

.tooltip span:before,
.tooltip span:after
{
  content: "";
  position: absolute;
  z-index: 1000;
  bottom: -7px;
  left: 50%;
  margin-left: -8px;
  border-top: 8px solid #ddd;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 0;
  
}

.tooltip span:before
{
  border-top-color: #ccc;
  bottom: -8px;
}
</style>  
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
	//---------------------------------------PLAN
	echo '
		

			<div style="position: relative;
						float:right; 
						height: 90%;
						width:80%;
						font-size:10px;
						border-width: 1px;
						border-style: solid;">
						
				<img class="photo" src="img/plan.jpg" width="100%" height="100%" >';

                              
				//------------------- DESSIN DES TABLES
				$query="SELECT * FROM emplacement where id_emplacement!=0";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->execute();
				while($emplacements=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<div class='place' onclick='Click(this)' id=";
					echo $emplacements['id_emplacement'];
					echo " style='";
					echo "position:absolute";
					echo ";";
					echo "top:";
					echo $emplacements['top'];
					echo "%";
					echo ";";
					echo "left:";
					echo $emplacements['xy_left'];
					echo "%";
					echo ";";
					echo "width:";
					echo $emplacements['width'];
					echo "%;height:";
					echo $emplacements['height'];
					echo "%;border:0.1em solid #000;text-align:center;color:#000000;'>";
					echo $emplacements['numero'];
					echo "</div>";
				}  
				//----------------------				

				//---------------création des vignetes
				$query1="SELECT * FROM emplacement,joueurs where joueurs.id_emplacement=emplacement.id_emplacement and emplacement.id_emplacement!=0";
				$requete_preparee1=$connexion->prepare($query1);
				$requete_preparee1->execute();
				while($emplacements1=$requete_preparee1->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<div class='tooltip' style='position:absolute;top:".$emplacements1['top']."%;left:".$emplacements1['xy_left']."%;width:".$emplacements1['width']."%;height:".$emplacements1['height']."%;border:0.1em solid ;text-align:center;'>";
					echo "<span>pseudo : <strong>".$emplacements1['pseudo']."</strong><br>Equipe : ";
					$id_joueur=$emplacements1['id_joueur'];

					$query2="SELECT * FROM equipes,equipes_joueur where equipes_joueur.id_joueur='$id_joueur' and equipes.id_equipes=equipes_joueur.id_equipes";
					$requete_preparee2=$connexion->prepare($query2);
					$requete_preparee2->execute();
					$team = array();
					while($emplacements2=$requete_preparee2->fetch(PDO::FETCH_ASSOC)) 
					{					
						$team[]=$emplacements2['nom'];        
					}
					echo implode(', ', $team);
							
					$query3="SELECT nomTournoi FROM joueurtournoi,tournoi where joueurtournoi.id_joueur='$id_joueur' and tournoi.id_tournoi=joueurtournoi.id_tournoi";
					 
					$requete_preparee3=$connexion->prepare($query3);
					$requete_preparee3->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
					$requete_preparee3->execute();
					echo "<br>";
					echo "<u>Tournoi :</u>";
					$nomTournoi=array();
					while($equipe=$requete_preparee3->fetch()) 
					{
						$nomTournoi[]=$equipe['nomTournoi']; 
					} 
					echo implode(', ', $nomTournoi);
					echo "</span></a>";
					echo "</div>";
				}
				//-----------------------------------		
	echo '	</div>';
		//-------------------------------- FIN PLAN
		
		
		//------------------------------LISTING
	echo '	<div id="tabs" style="position: relative;float: left;width:18%; border:solid 1px black;" >
				<div style="float:left;width:50%">
					<a href="#" onclick="show_tab(1);" style="font-size:16px;font-weight:bold;">Joueur</a>
				</div>	
				<div style="float:right;width:50%">
					<a href="#" onclick="show_tab(2);" style="font-size:16px;font-weight:bold;">Equipe</a>
				</div>

				<div id="tabs-1">

  
					<!-- FONCTION RECHERCHER JOUEUR -->
					<!--debut du formulaire-->
					<p>
						<label for="recherche_joueur">Rechercher un pseudo :</label>
						<input type="text" name="recherche_joueur" id="recherche_joueur" />
					</p>
					<!--fin du formulaire-->
 
					<!--preparation de l\'affichage des resultats-->
					<div id="results" style="display: none"><strong>Pas de résultat</strong></div>

					<div id="liste_joueur">

						<u>Liste des joueurs :</u><br>';

						// Selection des pseudos			
						
						$query="SELECT id_emplacement,pseudo FROM joueurs ORDER BY pseudo ASC ";
						$requete_preparee=$connexion->prepare($query);
						$requete_preparee->execute();
						while($joueurs=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
						{
							echo "<a class='ClassPseudo' value='";
							echo $joueurs['id_emplacement'];
							echo "'>";
							echo $joueurs["pseudo"];
							echo"</a>";		
						}

echo '
					</div>    
				</div>

				<!-- ONGLET EQUIPE -->
				<div id="tabs-2" style="display:none;">
					<!-- FONCTION RECHERCHER Equipe -->
					<!--debut du formulaire-->
					<p>
						<label for="recherche_equipe">Rechercher une équipe :</label>
						<input type="text" name="recherche_equipe" id="recherche_equipe" />
					</p>
					<!--fin du formulaire-->
					
					<div id="results_equipe" style="display: none"><strong>Pas de résultat</strong></div>
				 
					<div id="liste_equipe">
						<u>Liste des Equipes :</u><br>
						<div class="liste_equipe">';

						// Selection des équipes		

						$query="SELECT id_equipes,nom from equipes ORDER BY nom ASC ";
						$requete_preparee=$connexion->prepare($query);
						$requete_preparee->execute();
						while($equipes=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
						{
							echo "<a class='ClassEquipe' value='";
							echo $equipes['id_equipes'];
							echo "'>";
							echo $equipes["nom"];
							echo "</a>";		
						}

echo '					</div>
					</div>
				</div>
				
				<div id="dialogEquipe_Emplacement" style="display:none;"></div>
				<div id="dialogPseudo_Emplacement" style="display:none;"></div>
			</div>';
//--------------------------------
		
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