<?php
    session_start();
    
    require_once("connect.php");
    
    $sql = "DELETE FROM equipes_joueur WHERE id_joueur = :idj";
    
    if(!empty($_SESSION['id_joueur'])){
        
        $req = $connexion->prepare($sql);
		$req->bindValue("idj",$_SESSION['id_joueur'],PDO::PARAM_INT);
        $nbrLignesEff = $req->execute();
        
        if($nbrLignesEff>0){
            
            //le joueur a quitté l'equipe
            echo"Vous n'êtes plus un membre de cette team!";
            
        }
        else{
            //erreur le joueur n a pas pu quitter l'équipe
            echo"Une erreur s'est produite, veuillez réessayer plus tard!";
            
        }
    }
    else{
        echo"Votre session n'est plus valide! Veuillez-vous reconnectez.";
    }
?>