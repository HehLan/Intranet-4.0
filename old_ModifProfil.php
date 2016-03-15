<?php
session_start();
require_once('common/connect.php');
require_once('common/utils.php');
$con=false;
$chat=false;
if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0)) $con=true;
}
if(!$con)
{
	if(isset($_POST['login']) && isset($_POST['pwd']))
	{
		$sql="SELECT id_joueur,level FROM joueurs WHERE pseudo=:login and password=:pwd";
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
				$_SESSION['level']=$rst['level'];
				$con=true;
			}
		}
		else  {echo 'ERREUR LOGIN SQL';}
	}
	
}

function isPlay($idJoueur, $idTournoi, &$connexion,&$pseudoJeux){
   
    $sqlPlay="SELECT * FROM joueurtournoi WHERE id_joueur = :id_joueur AND id_tournoi = :id_tournoi";
    try {
        $req = $connexion->prepare($sqlPlay);
        $req->execute(array(
            'id_joueur'=>$idJoueur,
            'id_tournoi'=>$idTournoi
        ));
        $jeux = $req->fetch();
        if(empty($jeux)){
            
            //ne joue pas a ce jeux
            return '';
            
        }
        else {
            
	    $pseudoJeux=$jeux['pseudoJeux'];
            //joue a ce jeux
            return 'checked';
	    
            
        }
    }
    
    catch(PDOException $e) {
        return '';
    }
    
    
}

$sql="SELECT j.*, e.nom AS team
    FROM  joueurs j
    LEFT OUTER JOIN equipes_joueur ej ON j.id_joueur = ej.id_joueur
    LEFT OUTER JOIN equipes e ON e.id_equipes = ej.id_equipes
    WHERE j.id_joueur = :idj
";
$requete_preparee = $connexion->prepare($sql);
$requete_preparee->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
$requete_preparee->execute();
$joueur = $requete_preparee->fetch();
$pseudoJeux='';

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="img/logoheh.ico" >
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/profil.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui2.css" type="text/css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>	
    <script type="text/javascript" src="js/getXhr.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script type="text/javascript" src="js/less.js"></script>
    <script type="text/javascript" src="js/jquery.media.js"></script>
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
					echo 'Bienvenu à toi '.$_SESSION['login'].', <a href="common/deco.php">se déconnecter</a><br>';
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
<div id="formInscription">
<form id="formModifProfil" class="formular" method="post" action="validformulaire.php">
<input id="afficheModifProfil" type="button" value="Modifier le profil" >
	<fieldset>
		<legend>Profil</legend>
		<label for="pseudo">Pseudo :</label>
		<input type="text" name="pseudo" id="pseudo" readonly value="<?php echo $joueur['pseudo']; ?>"><br >
		<div id="pseudobox"></div>

		<label for="firstname">Nom :</label>
		<input type="text" id="firstname" readonly value="<?php echo $joueur['nom']; ?>"><br >
	
		<label for="firstname">Prénom :</label>
		<input type="text" name="lastname" id="lastname" readonly value="<?php echo $joueur['prenom']; ?>"><br >

		<label for="datepicker">Date de naissance :</label>
		<input type="text" name="date" id="date" readonly value="<?php echo date("d/m/Y", strtotime($joueur['date_de_naissance'])); ?>"><br >
	
		<label for="telephone">Téléphone :</label>
		<input type="text" name="telephone" id="telephone" readonly value="<?php echo $joueur['gsm']; ?>"><br >
		
		<label for="email">Email :</label>
		<input type="text" name="email" id="email" readonly value="<?php echo $joueur['email']; ?>"><br >
		<div id="ModifEmail">
		    <label for="email2">Confirmer Email :</label>
		    <input type="text" name="email2" id="email2" value="<?php echo $joueur['email']; ?>" ><br >
		</div> 
		
		<input id="afficheChgtMDP" type="button" value="Changer de mot de passe" ><br><br>
		
		
		<fieldset id="ModifMDP" style="display:none";>
		<legend>Modification de mot de passe</legend>
		
		    <label for="password">Ancien mot de passe :</label>
		    <input type="password" name="passwordOld" id="passwordOld"><br >
			
		    <label for="password">Nouveau mot de passe :</label>
		    <input type="password" name="password" id="password"><br >
		    
		    <label for="password2">Confirmer nouveau mot de passe :</label>
		    <input type="password" name="password2" id="password2"><br >
		    <div id="infoChgtMDP" style="border: none"></div>
		    <input id="submitChgtMDP" type="button" value="Valider le nouveau mot de passe">
		</fieldset>
		    
		
		

		
	</fieldset>
	<fieldset>
		<legend>Jeux</legend>
		
		<input type="checkbox" class="jeux" name="tournois[]" id="LOL" <?php echo isPlay($joueur['id_joueur'],"1",$connexion,$pseudoJeux); ?> disabled value="1">League Of Legends<br >
		<div id="pseudoLOL" style="<?php if(empty($pseudoJeux)) echo 'display : none;';?>">
			<label for="pseudoLOL" style="padding-left: 40px; ">Votre pseudo à LOL :</label>
			<input type="text" name="pseudoLOL" id="VerifPseudoLOL" style="margin-left: -40px;" readonly value="<?php echo $pseudoJeux; ?>">
			<div id="pseudoboxLOL" style="padding-left: 250px; "></div>
		</div>
		<input type="checkbox" class="jeux" name="tournois[]" id="COD4" <?php echo isPlay($joueur['id_joueur'],"2",$connexion,$pseudoJeux); ?> disabled value="2">Call Of Duty 4<br >
		<input type="checkbox" class="jeux" name="tournois[]" id="TM" <?php echo isPlay($joueur['id_joueur'],"3",$connexion,$pseudoJeux); ?> disabled value="3">TrackMania<br >
		<input type="checkbox" class="jeux" name="tournois[]" id="UT3" <?php echo isPlay($joueur['id_joueur'],"4",$connexion,$pseudoJeux); ?> disabled value="4">Unreal Tournament 3
		<br >
	</fieldset>
<?php
/*	<fieldset id="equipe">
		<legend>Equipe</legend>
		<br/> 
		
		    if(empty($joueur['team'])){
			echo "Vous n'avez pas de team!<br>";
			echo '<input id="newTeam" type="button" class="pasDeTeam" value="Créer votre team">';
			echo '<input id="rejoindreUneTeam" type="button" class="pasDeTeam" value="Rejoindre une team">';
			echo'
			    <br>
			    <div id="creerTeam" style="display:none;">
				    <input id="RetourTeam2" style="width: 100px" type="button" value="Retour"><br><br>
				    <label for="Team">Nom de la team :</label>
				    <input type="text" name="Team" id="Team"><br >
				    
				    <div id="pseudoboxTeam" style="border: none; margin: 0px; padding: 0px"></div>
				    
				    <label for="TagTeam">Tag de la team :</label>
				    <input type="text" name="TagTeam" id="TagTeam"><br >
				    <div id="pseudoboxTagTeam" style="border: none; margin: 0px; padding: 0px"></div>
					    
				    <label for="new_psw_equipe">Mot de passe : </label>
				    <input type="password" name="new_psw_equipe" id="new_psw_equipe" /><br>
				    <label for="new_psw_equipe2">Confirmer mot de passe : </label>
				    <input type="password" name="new_psw_equipe2" id="new_psw_equipe2" >
				    <div id="infoNewTeam" style="border: none"></div>
				    <input id="submitNewTeam" style="" type="button" value="Créer cette team">
			    </div>
			';
			echo'
			    <div id="rejoindreTeam" style="display:none;">
				<input id="RetourTeam" style="width: 100px" type="button" value="Retour"><br><br>
				<label for="rejoindre_Team">Le nom de la team :</label>
				<select id="rejoindre_Team" name="nomequipe" style="min-width: 140px;">
			';

			$query="SELECT id_equipes, nom FROM equipes ORDER BY nom";
			$requete_preparee=$connexion->prepare($query);
			$requete_preparee->execute();
			while($equipes=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
			{
				echo '<option value="'.$equipes["id_equipes"].'" style="color:#000"/>'.$equipes["nom"];
				echo "</option>";
			}
			echo'
				</select><br />
				<label for="psw_equipe">Mot de passe :</label>
				<input type="password" name="psw_equipe" id="psw_equipe" ><br />
				<div id="infoJoinTeam" style="border: none"></div>
				<input id="submitRejoindreTeam" type="button" value="Rejoindre cette team">
			    </div>
			';
		    }else{
			echo "Votre team est : <strong id='votreTeam'>".$joueur['team']."</strong><br>";
			echo '<input id="quitterTeam" type="button" class="withTeam" value="Quitter cette team">';
			echo '<input id="rejoindreAutreTeam" type="button" class="withTeam" value="Rejoindre une autre team">';
			echo'
			    <div id="rejoindreTeam" style="display:none;">
				<input id="RetourTeam" style="width: 100px" type="button" value="Retour"><br><br>
				<label for="rejoindre_Team">Le nom de la team :</label>
				<select id="rejoindre_Team" name="nomequipe" style="min-width: 140px;">
			';

			$query="SELECT id_equipes, nom FROM equipes ORDER BY nom";
			$requete_preparee=$connexion->prepare($query);
			$requete_preparee->execute();
			while($equipes=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
			{
				echo '<option value="'.$equipes["id_equipes"].'" style="color:#000"/>'.$equipes["nom"];
				echo "</option>";
			}
			echo'
				</select><br />
				<label for="psw_equipe">Mot de passe :</label>
				<input type="password" name="psw_equipe" id="psw_equipe" ><br />
				<div id="infoJoinTeam" style="border: none"></div>
				<input id="submitRejoindreTeam" type="button" value="Rejoindre cette team">
			    </div>
			';
		    }
	
		
	</fieldset>*/
?>	
	
	<div id='infoModifProfil'></div>
	<br>
	
</form>
<div id="dialogMessage"></div>
</div>
		</div>	
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>
</body>
</html>



