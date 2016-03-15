<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	if(!empty($_POST['id_equipe'])){
	    
	    $query = "SELECT j.id_joueur, j.pseudo
		FROM  joueurs j
		LEFT OUTER JOIN equipes_joueur ej ON j.id_joueur = ej.id_joueur
		WHERE ej.id_equipes = :id";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue("id",$_POST['id_equipe'],PDO::PARAM_INT);
	    $result=$requete_preparee->execute();
	    $nbr=$requete_preparee->rowCount();
	    
	    if($nbr!=0){
		
		while($joueur = $requete_preparee->fetch()){
		    $sql = "SELECT jt.pseudoJeux
			FROM  joueurs j
			LEFT OUTER JOIN joueurtournoi jt ON j.id_joueur = jt.id_joueur
			WHERE jt.id_tournoi = :id_tournoi AND j.id_joueur = :id_joueur";
		    $req=$connexion->prepare($sql);
		    $req->bindValue("id_tournoi",1,PDO::PARAM_INT);
		    $req->bindValue("id_joueur",$joueur["id_joueur"],PDO::PARAM_INT);
		    $req->execute();
		    $nbr=$req->rowCount();
		    
		    echo'
			<h6 class="EquipeJoueurAdmin" value="'.$joueur["id_joueur"].'">'.$joueur["pseudo"].'
		    ';
		    if($nbr!=0){
			$joueurtournoi = $req->fetch();
			echo'
			    (LoL ==> '.$joueurtournoi["pseudoJeux"].')</h6>
			';		
		    }
		    else echo'</h6>';
		}
	    }
	    else echo "L'équipe ne contient pas de joueur!";
	}
	else echo 'aucune donnée reçue!';
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";

?>