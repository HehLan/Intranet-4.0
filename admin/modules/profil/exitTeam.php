<?php
    session_start();
    
    require_once("../connect.php");
    
    $sql = "DELETE FROM equipes_joueur WHERE id_joueur = (SELECT id_joueur FROM joueurs WHERE pseudo = :pseudo AND password = :mdp)";
    
    if(!empty($_SESSION['Auth']['pseudo'])&&!empty($_SESSION['Auth']['password'])){
        
        $req = $connexion->prepare($sql);
        $nbrLignesEff = $req->execute(array(
            ':pseudo'=>$_SESSION['Auth']['pseudo'],
            ':mdp' => $_SESSION['Auth']['password']
        ));
        
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