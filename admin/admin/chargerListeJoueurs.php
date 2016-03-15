<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	try {
            $query="SELECT id_joueur, pseudo FROM joueurs ORDER BY pseudo";
            $requete_preparee=$connexion->prepare($query);
            $requete_preparee->execute();
            echo'<select id="SelectJoueur" style="width: 200px;"><option></option>';              
            while($joueur=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
            {
                echo '<option value="'.$joueur["id_joueur"].'" style="color:#000">'.$joueur["pseudo"];
		echo "</option>";
            }
            echo'
                </select>
                <input type="button" id="submitSeclectJoueurEquipeAdmin" value="Ajouter ce joueur">
            ';
        }
        catch(PDOException $e) {
            echo 'Base de données est indisponible pour le moment!';
        }
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";
?>                

























