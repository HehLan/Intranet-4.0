<?php
session_start();

if (!empty($_POST)){
    $valid=true;
    
    $erreurNomTeam='';
    $erreurMDPteam='';
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
    
    
    /***************************
     * Rejoindre une team
     * ************************/
    
    
	
    //nom de la team
    if(empty($_POST['nomequipe'])){
	$valid=false;
	$erreurNomTeam="Vous n'avez pas choisi le nom de votre team. \n";
    }
    //vérifie que l'utilisateur n'est pas introduit de valeur intrusive dans notre select
    else if ( !preg_match ( "/^[0-9]{1,10}$/" , $_POST['nomequipe'] ) )
    {
	$valid=false;
	$erreurNomTeam="Le nom de la team n'est pas valide \n";
    }
    
    //password de la team
    if(empty($_POST['psw_equipe'])){
	$valid=false;
	$erreurMDPteam="Vous n'avez pas rempli le mot de passe de la team. \n";
    }
    else if(strlen($_POST['psw_equipe'])<8){
	$valid=false;
	$erreurMDPteam="Le mot de passe de votre team doit comporter au moins 8 caractères \n";
    }
    else if(strlen($_POST['psw_equipe'])>30){
	$valid=false;
	$erreurMDPteam="Le mot de passe de votre team est trop long \n";
    }
	
    
    
    
    if($valid){
	
	require_once("connect.php");
	
	
	$mot_de_passe = sha1($_POST["psw_equipe"]);
	$id_equipes = $_POST["nomequipe"];
	
	// Verification bon mot de passe
	$query = "SELECT * FROM equipes WHERE id_equipes = :id_equipes AND mot_de_passe = :mdp ";
	$requete_preparee = $connexion->prepare($query);
	$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
	$requete_preparee->bindValue("mdp",$mot_de_passe,PDO::PARAM_INT);
	$result=$requete_preparee->execute();
	$nbr=$requete_preparee->rowCount();
	if($nbr != 0)
	{
		// Verification que le joueur fait parti d'une equipe
		$query = "SELECT * FROM equipes_joueur WHERE id_joueur = :idj";
		$requete_preparee = $connexion->prepare($query);
		$requete_preparee->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
		$result=$requete_preparee->execute();
		$nbr=$requete_preparee->rowCount();
		if($nbr == 0)
		{
		    //le joueur ne fait parti d'aucune equipe
		    try{
			    
			    $query = "INSERT INTO equipes_joueur (id_joueur,id_equipes) VALUES (:idj,:id_equipes)";
			    $requete_preparee = $connexion->prepare($query);
			    $requete_preparee->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
			    $requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
			    $requete_preparee->execute();
			    echo'Vous avez été ajoutée à votre team!<br>';
			    
		    }
		    catch(Exception $e){
			    echo "Une erreur est survenue<br>";
			    echo "Message = ".$e->getMessage();
			    
			    die();
		    }
		}else{
		    //le joueur fait parti d'une equipe
		    try{
			    
			    $query = "UPDATE equipes_joueur SET id_equipes=:id_equipes WHERE id_joueur=:idj";
			    $requete_preparee = $connexion->prepare($query);
			    $requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
				$requete_preparee->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
			    $requete_preparee->execute();
			    echo 'Vous avez changé de Team!<br>';
			    
		    }
		    catch(Exception $e){
			    echo "Une erreur est survenue<br>";
			    echo "Message = ".$e->getMessage();
			    
			    die();
		    }
		}
		
	}
	else
	{
		echo 'Le mot de passe pour rejoindre l\'équipe est incorrect!<br/>';
	}
	
    }
    else{
	echo $erreurNomTeam.$erreurMDPteam.$erreurSession;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}

	
?>