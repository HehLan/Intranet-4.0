<?php



if (!empty($_POST)){
    $valid=true;
    
    $erreurPseudo='';
    $erreurEmail='';
    $erreurTel='';
    $erreurSession='';
    
    //vérification de la session
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
    
    //pseudo
    if(empty($_POST['pseudo'])){
	$valid=false;
	$erreurPseudo="Vous n'avez pas rempli votre pseudo. \n";
    }
    else if(strlen($_POST['pseudo'])<2){
	$valid=false;
	$erreurPseudo="Votre pseudo doit comporter au moins 2 caractères \n";
    }
    else if(strlen($_POST['pseudo'])>40){
	$valid=false;
	$erreurPseudo="Votre pseudo est trop long \n";
    }
    
    
    
    //email
    if(empty($_POST['email'])){
	$valid=false;
	$erreurEmail="Vous n'avez pas rempli votre email. \n";
    }
    else if(!preg_match("/^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$/i",$_POST['email'])){
	$valid=false;
	$erreurEmail="Votre email n'est pas valide. \n";
    }
    else if($_POST['email']!=$_POST['email2']){
	$valid=false;
	$erreurEmail="Les 2 emails ne sont pas les mêmes \n";
    }
    
    
    //vérifie le telephone
    if(!empty($_POST['telephone'])){
	if(strlen($_POST['telephone'])>40){
	    $valid=false;
	    $erreurTel="Votre numéro de téléphone est trop long! \n";
	}
    }
    
    
    if($valid){
	require_once("../connect.php");
	
	$pseudo=trim($_POST["pseudo"]);
	$gsm=$_POST["telephone"];
	$email=$_POST["email"];
	if(!empty($_POST["tournois"])){
	    $tournois=$_POST["tournois"];
	}
	$pseudoLOL=$_POST["pseudoLOL"];
	
	//si le joueur change de pseudo
	if ($pseudo != $_SESSION['Auth']['pseudo']){
	    //vérification si le pseudo existe déjà
	    $query = "SELECT pseudo FROM joueurs WHERE pseudo=:pseudo";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue('pseudo',$pseudo, PDO::PARAM_STR);
	    $result=$requete_preparee->execute();

	    $nbr=$requete_preparee->rowCount();
	    
	    
	}else {
	    //le joueur n a pas change de pseudo
	    $nbr = 0;
	}
	
	$id_joueur=0;
	
	// si le joueur n'a pas change de pseudo ou si le pseudo changé n'est pas deja pris dans la BD
	if($nbr == 0)
	{
	    // mise a jour du joueur
	    $query = "UPDATE joueurs SET pseudo = :pseudoModifie, email = :email, gsm = :gsm  WHERE pseudo = :pseudo AND password = :mdp";
	    $requete_preparee=$connexion->prepare($query);
	    
	    $requete_preparee->bindvalue("pseudoModifie",$pseudo,PDO::PARAM_STR);
	    $requete_preparee->bindvalue("email",$email,PDO::PARAM_STR);
	    $requete_preparee->bindvalue("gsm",$gsm,PDO::PARAM_STR);
	    $requete_preparee->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
	    $requete_preparee->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
	    
	    try {
		    $requete_preparee->execute();
		    $_SESSION['Auth']['pseudo']=$pseudo;
		    
		    // Creation ou Mise a jour des liaisons jeux - joueurs
		    
		    
		    /* on vérifie qu'il y a au moins un tournoi où le joueur est inscrit et
			    on vérifie aussi qu'il n'y a pas plus de tournois que le nombre maximum possible (éviter
			    les boucles que l'utilisateur aurait pu créer pour nous nuir)*/
		    if(isset($tournois) and !empty($tournois)){
			
			if(count($tournois)<=5){
			    
			    // on récupère tous les tournois auxquels le joueur est inscrit
			    $query = "SELECT id_JT, id_tournoi FROM joueurtournoi WHERE id_joueur=(SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp)";
			    $req=$connexion->prepare($query);
			    $req->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
			    $req->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
			    $result=$req->execute();
			    $nbr=$req->rowCount();
			    
			    if ($nbr == 0){
				
				// si il n'est inscrit a aucun tournoi
				foreach($tournois as $chkbx){
				    
				    $query="INSERT INTO joueurtournoi (id_joueur,id_tournoi,pseudoJeux) VALUES ((SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp),:id_tournoi, :pseudoJ)";
				    $requete_preparee=$connexion->prepare($query);
				    $requete_preparee->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
				    $requete_preparee->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
				    $requete_preparee->bindValue("id_tournoi",$chkbx,PDO::PARAM_INT);
				    if($chkbx==1){
					    $requete_preparee->bindvalue("pseudoJ",$pseudoLOL,PDO::PARAM_STR);
				    }
				    else {
					    $requete_preparee->bindvalue("pseudoJ","",PDO::PARAM_STR);
				    }
				    $requete_preparee->execute();
				}
				
			    }else{
				
				// le joueur est inscrit a des tournois donc on doit vérifier qu'il n a pas quitte ces tournois
				while($joueurTournoi = $req->fetch()){
				    $tournoiTrouve = false;
				    foreach($tournois as $chkbx){
					if($chkbx==$joueurTournoi["id_tournoi"]){
					    $tournoiTrouve=true;
					}
				    }
				    //si on ne retrouve pas le tournoi dans les modifications du joueur c'est qu'il a quitté le tournois
				    // donc on le supprime
				    if (!$tournoiTrouve){
					$sql = "DELETE FROM joueurtournoi WHERE id_JT = :id_JT";
					$requete_preparee=$connexion->prepare($sql);
					$requete_preparee->bindValue("id_JT",$joueurTournoi["id_JT"],PDO::PARAM_INT);
					$result=$requete_preparee->execute();
				    }
				}
				
				foreach($tournois as $chkbx){
				
				    $query = "SELECT id_JT, pseudoJeux FROM joueurtournoi WHERE id_joueur=(SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp) AND id_tournoi = :id_tournoi";
				    $requete_preparee=$connexion->prepare($query);
				    $requete_preparee->bindValue("id_tournoi",$chkbx,PDO::PARAM_INT);
				    $requete_preparee->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
				    $requete_preparee->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
				    $result=$requete_preparee->execute();
				    $joueurTournoi = $requete_preparee->fetch();
				    $nbr=$requete_preparee->rowCount();
				    
				    
				    if ($nbr == 0){
					//si on n'a pas trouve le joueur et le tournoi dans la BD, on ajoute
					$query="INSERT INTO joueurtournoi (id_joueur,id_tournoi,pseudoJeux) VALUES ((SELECT j.id_joueur FROM joueurs as j WHERE j.pseudo = :pseudo AND j.password = :mdp),:id_tournoi, :pseudoj)";		    
					$requete_preparee=$connexion->prepare($query);
					$requete_preparee->bindValue("pseudo",$_SESSION['Auth']['pseudo'],PDO::PARAM_STR);
					$requete_preparee->bindValue("mdp",$_SESSION['Auth']['password'],PDO::PARAM_STR);
					$requete_preparee->bindValue("id_tournoi",$chkbx,PDO::PARAM_INT);
					if($chkbx==1){
						$requete_preparee->bindvalue("pseudoj",$pseudoLOL,PDO::PARAM_STR);
					}
					else {
						$requete_preparee->bindvalue("pseudoj","",PDO::PARAM_STR);
					}
					$requete_preparee->execute();
					
				    }else if(($chkbx==1) AND($result['pseudoJeux']!=$pseudoLOL)){
					
					$query = "UPDATE joueurtournoi SET pseudoJeux = :pseudoJeux WHERE id_JT = :id_JT";
					$requete_preparee=$connexion->prepare($query);
					$requete_preparee->bindvalue("id_JT",$joueurTournoi['id_JT'],PDO::PARAM_INT);
					$requete_preparee->bindvalue("pseudoJeux",$pseudoLOL,PDO::PARAM_STR);
					$requete_preparee->execute();
				    }
				    
				}
				
			    }
			}
			
			
		    }else{
			//il faut supprimer tous les tournois où le joueur est inscrit
			$sql = "DELETE FROM joueurtournoi WHERE id_joueur = (SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp)";
			$req = $connexion->prepare($sql);
			$req->execute(array(
			    ':pseudo'=>$_SESSION['Auth']['pseudo'],
			    ':mdp' => $_SESSION['Auth']['password']
			));
		    }
		    echo'Votre compte a été modifié!<br/>';
		    
		    
	    }
	    catch(Exception $e){
		    echo "Une erreur est survenue<br/>";
		    echo "Message = ".$e->getMessage();
		    die();
	    }
		
	}
	else
	{
		echo "Le pseudo existe deja";
	}
    }
    else{
	echo $erreurPseudo.$erreurEmail.$erreurTel.$erreurSession;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}

	
?>