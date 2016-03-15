<?php
session_start();

if (!empty($_POST)){
    $valid=true;
    
    $erreurMDP='';
    $erreurSession='';

    if (empty($_SESSION['id_joueur'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }else if(empty($_SESSION['password'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }else if(empty($_SESSION['login'])){
	$valid=false;
	$erreurSession='Erreur de session : Veuillez vous reconnecter!';
    }
    
    
    //password
    if(empty($_POST['password'])){
	$valid=false;
	$erreurMDP="Vous n'avez pas rempli de mot de passe. \n";
    }
    else if(strlen($_POST['password'])<8){
	$valid=false;
	$erreurMDP="Le mot de passe doit comporter au moins 8 caractères \n";
    }
	else if($_POST['passwordOld']!=$_SESSION['password']){
	$valid=false;
	$erreurMDP="Votre ancien mdp est erroné \n";
    }
    else if(strlen($_POST['password'])>30){
	$valid=false;
	$erreurMDP="Le mot de passe est trop long \n";
    }
    else if($_POST['password']!=$_POST['password2']){
	$valid=false;
	$erreurMDP="Les 2 mots de passe ne sont pas les mêmes. \n";
    }
	
    
    
    
    if($valid){
	
	require_once("connect.php");
	
	$password=sha1($_POST["password"]);
	
	
	try{
	    
		$query = "UPDATE joueurs SET password = :password WHERE id_joueur=:idj";
		$requete_preparee = $connexion->prepare($query);
		$requete_preparee->bindValue("password",$password,PDO::PARAM_STR);
		$requete_preparee->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
		$requete_preparee->execute();
		$_SESSION['password']=$password;
		echo 'Votre mot de passe a bien été modifié!<br>';
		
	}
	catch(Exception $e){
		echo "Une erreur est survenue<br>";
		echo "Message = ".$e->getMessage();
		
		die();
	}
	
    }
    else{
	echo $erreurMDP.$erreurSession;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}

	
?>