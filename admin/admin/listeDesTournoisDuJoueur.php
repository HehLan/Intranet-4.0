<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	if(!empty($_POST['id_joueur'])){
	    
	    $query = "SELECT t.*, jt.id_joueur, jt.pseudoJeux
		FROM   tournoi AS t
		LEFT OUTER JOIN joueurtournoi AS jt ON jt.id_tournoi = t.id_tournoi AND jt.id_joueur = :id
		";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue("id",$_POST['id_joueur'],PDO::PARAM_INT);
	    $result=$requete_preparee->execute();
	    
		
	    while($tournoi = $requete_preparee->fetch()){
		
		if($tournoi['id_joueur']==$_POST['id_joueur']){
		    echo'
			<input type="checkbox" checked class="chkbxJoueurTournoi" value="'.$tournoi["id_tournoi"].'">'.$tournoi["nomTournoi"].'
		    ';
		}
		else {
		    echo'
			<input type="checkbox" class="chkbxJoueurTournoi" value="'.$tournoi["id_tournoi"].'">'.$tournoi["nomTournoi"].'
		    ';
		}
		
		echo'
		    <input type="text" id="txtbxJoueurTournoi'.$tournoi["id_tournoi"].'" value="'.$tournoi["pseudoJeux"].'">
		';
		echo'<br>';
	    }
	    echo'<input type="hidden" id="idJoueurAdmin" value="'.$_POST['id_joueur'].'">';
	    echo'<br>';
	    echo'<input type="button" id="submitChgtTournoijoueurAdmin" value="Valider les changements">';
	}
	else echo 'aucune donnée reçue!';
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";

?>