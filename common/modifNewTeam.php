
<?php
session_start();

if (!empty($_POST)){
    $valid=true;
    $erreurTag='';
    $erreurNomTeam='';
    $erreurMDPteam='';
    $erreurSession='';

    if (empty($_SESSION['Auth'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }else if(empty($_SESSION['Auth']['password'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }else if(empty($_SESSION['Auth']['pseudo'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }
    
    /***************************
     * Créer une team
     * ************************/
    
    //nom de la team
    if(empty($_POST['Team'])){
	$valid=false;
	$erreurNomTeam="Vous n'avez pas rempli le nom de votre team. \n";
    }
    else if(strlen($_POST['Team'])<2){
	$valid=false;
	$erreurNomTeam="Le nom de votre team doit comporter au moins 2 caractères \n";
    }
    else if(strlen($_POST['Team'])>40){
	$valid=false;
	$erreurNomTeam="Le nom de votre team est trop long \n";
    }
    
    //tag de la team
    if(empty($_POST['TagTeam'])){
	$valid=false;
	$erreurTag="Vous n'avez pas rempli le tag de votre team. \n";
    }
    else if(strlen($_POST['TagTeam'])<1){
	$valid=false;
	$erreurTag="Le tag de votre team doit comporter au moins 1 caractère \n";
    }
    else if(strlen($_POST['TagTeam'])>10){
	$valid=false;
	$erreurTag="Le tag de votre team est trop long \n";
    }
    
    //password de la team
    if(empty($_POST['new_psw_equipe'])){
	$valid=false;
	$erreurMDPteam="Vous n'avez pas rempli le mot de passe de la team. \n";
    }
    else if(strlen($_POST['new_psw_equipe'])<8){
	$valid=false;
	$erreurMDPteam="Le mot de passe de votre team doit comporter au moins 8 caractères \n";
    }
    else if(strlen($_POST['new_psw_equipe'])>30){
	$valid=false;
	$erreurMDPteam="Le mot de passe de votre team est trop long \n";
    }
    else if($_POST['new_psw_equipe']!=$_POST['new_psw_equipe2']){
	$valid=false;
	$erreurMDPteam="Les 2 mots de passe de votre team ne sont pas les mêmes. \n";
    }
    
    if($valid){
	require_once("../connect.php");
	$mot_de_passe=sha1($_POST["new_psw_equipe"]);
	$id_equipes = 0;
	$nomteam = trim($_POST["Team"]);
	$tag=$_POST["TagTeam"];
	    
	// verifie que le nom d'équipe n'existe pas deja
	$query = "SELECT nom FROM equipes WHERE nom=:nom"; 
	$requete_preparee=$connexion->prepare($query);
	$requete_preparee->bindValue('nom',$nomteam, PDO::PARAM_STR);
	$result=$requete_preparee->execute();
	$nbr=$requete_preparee->rowCount();
	
	if($nbr == 0)
	{
			
		try{
			// Creation de l'equipe
			$query = "INSERT INTO equipes (nom, mot_de_passe,tag) VALUES (:nom,:mot_de_passe,:tag)";
			$requete_preparee=$connexion->prepare($query);
			$requete_preparee->bindvalue("nom",$nomteam,PDO::PARAM_STR);
			$requete_preparee->bindvalue("mot_de_passe",$mot_de_passe,PDO::PARAM_STR);
			$requete_preparee->bindvalue("tag",$tag,PDO::PARAM_STR);
			$requete_preparee->execute();
			// Creation de la liaison equipe - joueur
			$id_equipes=$connexion->lastInsertId();
			$query="INSERT INTO equipes_joueur (id_joueur,id_equipes) VALUES ((SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp),:id_equipes)";
			$requete_preparee=$connexion->prepare($query);
			$requete_preparee->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
			$requete_preparee->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
			$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
			$requete_preparee->execute();
			echo'Votre équipe a été créée!<br>';
			
		}
		catch(Exception $e){
			echo "Une erreur est survenue<br>";
			echo "Message = ".$e->getMessage();
			
			die();
		}
		
	}
	else
	{
		echo "Le nom de la team est déjà pris <br/>";
	}
	
    }
    else{
	echo $erreurNomTeam.$erreurTag.$erreurMDPteam.$erreurSession;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}
?>
