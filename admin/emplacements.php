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
    <link rel="stylesheet" href="../css/jquery-ui.css" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/getXhr.js"></script>
    <script type="text/javascript">
$(document).ready(function()
{
//colorie la case pour le pseudo
    $('#SelectPseudo').change(function()
    {
    $("#dialogInfo_equipe").css({display :"none"});
    $('.place').css({background : "none"});
    $('#'+$('#SelectPseudo').val()).css({background : "#9ba0ee"});
    valeur = $('#SelectPseudo').val();
        $.ajax(
        { 
	type: "POST", 
	url: "admin/info_joueur.php",
	data: "id_emplacement="+valeur,
	success : function(contenu,etat)
            { 
	    $("#dialogInfo_joueur").html(contenu);
            }
        });
   });
 //colorie la place pour le select emplacement
    $('#SelectEmplacement').change(function()
    {
        $('.place').css({background : "none"});
      $('#'+$('#SelectEmplacement').val()).css({background : "green"});
   });
// recupere id_equipes pour l'envoi en AJAX
    $('#SelectEquipe').change(function()
    {
        $("#dialogInfo_joueur").css({display :"none"});
        $('.place').css({background : "none"});
        valeur=$('#SelectEquipe').val();
        $.ajax(
            { 
            type: "POST", 
            url: "admin/color_equipe.php",
	    data: "id_equipes="+valeur,
	    success : function(contenu,etat)
                    { 
		    $( "#dialogEquipe_Emplacement" ).html(contenu);
                    $( "#dialogInfo_equipe" ).html(contenu);
                    }          
		
            });  
   });


});


	</script>
<style>    
#info{
position:relative;
z-index:24;
color:#000;
text-decoration:blink
}
 
#info:hover{
z-index:25;
background-color:red;
cursor: help;
}
 
#info span{
display: none
}
 
#info:hover span{
display:block;
position:absolute;
top:2em; left:2em; width:10em;
border:1px solid #000;
background-color:#FFF;
color:#000;
text-align: justify;
font-size: medium;
font-weight:none;
padding:5px;
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
			<div style="position: relative;
            float:right; 
			height: 110%;
			width: 80%;
            font-size:10px;
			border-width: 1px;
			border-style: solid;">
				<img class="photo" src="../img/plan.jpg" width="100%" height="100%" >
			<?php
				$query="SELECT * FROM emplacement where id_emplacement!=0";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->execute();
				while($emplacements=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<div class='place' id=";
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
   
				$query1="SELECT * FROM emplacement,joueurs where joueurs.id_emplacement=emplacement.id_emplacement and emplacement.id_emplacement!=0";
				$requete_preparee1=$connexion->prepare($query1);
				$requete_preparee1->execute();
				while($emplacements1=$requete_preparee1->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<div class='place' id='info' style='position:absolute;top:".$emplacements1['top']."%;left:".$emplacements1['xy_left']."%;width:".$emplacements1['width']."%;height:".$emplacements1['height']."%;border:0.1em solid red;background:rgba(100,100,100,0.3);text-align:center;'>";
					echo "<span><u>pseudo :</u> ".$emplacements1['pseudo']."<br><u>Equipe :</u> ";
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
					echo "</span></a>";
					echo "</div>";
				}
				/*
						<div id='cadre' style='position: absolute;top: 5%;
				left: 36%;width: 4.5%;height:3%;border:0.1em solid #000;text-align:center;'>
				</div>
				*/
?>			
		
			</div> 
			<!-- VISIONNER LA PLACE DU JOUEUR -->    
			<b><u><center>Consulter la place :</center></u></b></b>
			<br>

			<br>
			<!-- PSEUDO -->
			Pseudo :
			<select class="SelectPseudo" name= "SelectPseudo" id="SelectPseudo">
				<option value="" selected ></option>
			<?php
				// Selection des pseudos			

				$query="SELECT pseudo,id_emplacement FROM joueurs ORDER BY pseudo ASC";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->execute();
				while($joueurs=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<option value=";
					echo $joueurs['id_emplacement'];
					echo ">";
					echo $joueurs["pseudo"];
					echo "</option>";						
				}

			?>
			</select>
			<br>
			<br>
			<!-- Equipe -->
			Equipe :
			<select id="SelectEquipe">
				<option value="" selected ></option>
			<?php
				// Selection des équipes		
				$query="SELECT id_equipes,nom from equipes order by nom ASC";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->execute();
				while($equipes=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
				{
					echo "<option value=";
					echo $equipes['id_equipes'];
					echo ">";
					echo $equipes["nom"];
					echo "</option>";						
				}

			?>
			</select>

			<div id="dialogEquipe_Emplacement" style='display:none;'></div>
			<br>
			<br>

			<!-- AJOUTER PLACE AU JOUEUR -->                                                                                       
			<form method="post" action="place.php">
			  
				<b><u><center>Associer la place :</center></u></b></b>
				<br><br> 
				Emplacement :
				<select name ="SelectEmplacement" id="SelectEmplacement">	
				<?php
					$query="SELECT * FROM emplacement";
					$requete_preparee=$connexion->prepare($query);
					$requete_preparee->execute();
					while($emplacements=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
					{
						echo "<option value =";
						echo $emplacements['id_emplacement'];
						
						echo ">";
						echo $emplacements['numero'];
						echo "</option>";
												
					}
				?>		
				</select> 
				<br><br>
				Pseudo :
				<select name ="SelectPseudo" id="SelectPseudo">
					<option value="" selected ></option>
				<?php
					// Selection des pseudos			
					$query="SELECT pseudo FROM joueurs ORDER BY pseudo ASC";
					$requete_preparee=$connexion->prepare($query);
					$requete_preparee->execute();
					while($joueurs=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
					{
						echo "<option value='".$joueurs['pseudo']."'>";
						echo $joueurs["pseudo"];
						echo "</option>";
					
					}
				?>
				</select>
				<br><br>
				<p><center><input class="submit" type="submit" value="Valider" /></center></p>
			</form>
			<div id="dialogInfo_joueur" style="width:300px;height:auto;border:1px solid #000;
			display: none;padding:5px; background-color:#9ba0ee; border:2px solid #656ab0;
			-khtml-border-radius:9px; -webkit-border-radius:9px; border-radius:9px;"></div>
			<div id="dialogInfo_equipe" style="width:300px;height:auto;border:1px solid #000;
			display: none;padding:5px; background-color:#ffaca3; border:2px solid #ff3924; -khtml-border-radius:9px;
			-webkit-border-radius:9px; border-radius:9px;"></div>			
		
		</div>
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>
	
</body>
</html>