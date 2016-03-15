<?php
session_start();

//Générer une chaine de caractère unique et aléatoire
function random($car) {
    $string = "";
    
    $chaine = "abcdefghijklmnpqrstuvwxy123456789";
    
    srand((double)microtime()*1000000);
    
    for($i=0; $i<$car; $i++) {
	$string .= $chaine[rand()%strlen($chaine)];
    }
    return $string;
}


if (!empty($_POST)){
    $valid=true;
    
    $erreurEmail='';
    
    //email
    if(empty($_POST['emailOublie'])){
	$valid=false;
	$erreurEmail="Vous n'avez pas rempli votre email. \n";
    }
    else if(!preg_match("/^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$/i",$_POST['emailOublie'])){
	$valid=false;
	$erreurEmail="Votre email n'est pas valide. \n";
    }
    
    if($valid){
	
	require_once("../connect.php");
	
	$pseudo=$_POST["pseudoOublie"];
	$email=$_POST["emailOublie"];
	// Génère une chaine aléatoire de 20 caractères
	$password = random(20);
	
	$sql="SELECT id_joueur, prenom FROM joueurs WHERE email = :email AND pseudo = :pseudo";
	$requete_preparee = $connexion->prepare($sql);
	$requete_preparee->bindValue("email",$email,PDO::PARAM_STR);
	$requete_preparee->bindValue("pseudo",$pseudo,PDO::PARAM_INT);
	$requete_preparee->execute();
	$nbr=$requete_preparee->rowCount();
	if($nbr != 0)
	{
	    $joueur=$requete_preparee->fetch();
	    //on a trouvé l'email dans la BD donc l'utilisateur
	    try{
	    
		$query = "UPDATE joueurs SET password = :password WHERE id_joueur = :id_joueur";
		$requete_preparee = $connexion->prepare($query);
		$requete_preparee->bindValue("password",sha1($password),PDO::PARAM_STR);
		$requete_preparee->bindValue("id_joueur",$joueur['id_joueur'],PDO::PARAM_INT);
		$requete_preparee->execute();
		
		$sql="SELECT * FROM joueurs WHERE email = :email AND pseudo = :pseudo AND password = :password";
		$requete_preparee = $connexion->prepare($sql);
		$requete_preparee->bindValue("email",$email,PDO::PARAM_STR);
		$requete_preparee->bindValue("pseudo",$pseudo,PDO::PARAM_INT);
		$requete_preparee->bindValue("password",sha1($password),PDO::PARAM_STR);
		$requete_preparee->execute();
		$nbr=$requete_preparee->rowCount();
		if($nbr != 0){
		    //mail envoi new MDP
		    $to = $email;
		    $sujet = "HEHLAN : Réinitialisation de votre mot de passe";
		    $sujet= utf8_decode($sujet);
		    $header="From: \"HeHLan\" <hehlan.be@gmail.com>\n";
		    $header .= "Reply-to: \"HeHLan\" <hehlan.be@gmail.com>\n";
		    $message = "
		    Bonjour ".$joueur['prenom']."! Vous avez réinitialisé votre mot de passe.
		    
				    Votre nouveau mot de passe : ".$password." 
		    
		    En vous connectant sur www.hehlan.be, vous pouvez modifier votre mot de passe en accédant à votre espace membre.
		    
		    Si vous avez des questions, n'hésitez pas à nous contacter.
		    
		    L'équipe de la HeHLan.
		    ";
				    
		    if (mail($to,$sujet,$message,$header)){
			echo '<br>Votre nouveau mot de passe vous a été envoyé à votre adresse email!<br>';
		    }else{
			echo "<br>Une erreur est survenue durant l'envoi de l'email!<br>";
		    }
		}
		else
		{
		    echo "<br>Une erreur est survenue durant l'envoi de l'email! Réessayez plus tard!<br>";
		}
		
		
	    }
	    catch(Exception $e){
		echo "Une erreur est survenue<br>";
		echo "Message = ".$e->getMessage();
		
		die();
	    }
	}else{
	    //on a pas trouvé le joueur
	    echo "Vos identifiants que vous avez mentionnés, n'ont pas été trouvé dans notre base de données<br>";
	}
	
    }
    else{
	echo $erreurEmail;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}
		
?>