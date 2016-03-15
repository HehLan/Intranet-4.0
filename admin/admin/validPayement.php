<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	if(!empty($_POST['paye'])){
	    
	    $query="UPDATE joueurs SET paye = 1 WHERE id_joueur = :id";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue("id",$_POST['paye'],PDO::PARAM_INT);
	    $requete_preparee->execute();
	    
	    $query = "SELECT * FROM joueurs WHERE id_joueur = :id";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue("id",$_POST['paye'],PDO::PARAM_INT);
	    $result=$requete_preparee->execute();
	    $joueur = $requete_preparee->fetch();
	    $nbr=$requete_preparee->rowCount();
	    if($nbr!=0){
		if ($joueur['paye']==1){
		    //mail de confirmation
		    $to = $joueur['email'];
		    $sujet = "HEHLAN : confirmation de payement";
		    $header="From: \"HeHLan\" <hehlan.be@gmail.com>\n";
		    $header .= "Reply-to: \"HeHLan\" <hehlan.be@gmail.com>\n";
		    $message = "
		    Félicitations ".$joueur['prenom']."! Nous vous confirmons la réception de votre payement!
		    
		    Vous êtes désormais officiellement inscrit, sous le pseudo '".$joueur['pseudo']."' à notre évènement HeHLan Party 2014!
		    
		    L'équipe de la HeHLan.
		    ";
		    
		    if (mail($to,$sujet,$message,$header)){
			echo "true";
		    }
		    else echo "Erreur : Pour ".$joueur['pseudo'].", l'email de confirmation de payement n'a pas été envoyé!";
		}
		else echo "Erreur : Pour ".$joueur['pseudo'].", la validation du payement ne s'est pas correctement effectué!";
	    }
	    else echo "Le joueur n'a pas été trouvé dans la base de données!";
	}
	else echo 'aucune donnée reçue!';
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";

?>