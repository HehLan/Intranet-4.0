<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	if(!empty($_POST['id_joueur'])){
	    
	    $query = "SELECT e.id_equipes, e.nom AS team
		FROM  equipes_joueur et
		LEFT OUTER JOIN equipes e ON e.id_equipes = et.id_equipes
		WHERE et.id_joueur = :id
	    ";
	    $requete_preparee=$connexion->prepare($query);
	    $requete_preparee->bindValue("id",$_POST['id_joueur'],PDO::PARAM_INT);
	    $result=$requete_preparee->execute();
	    
		
	    while($equipe = $requete_preparee->fetch()){
		echo'<input type="checkbox" checked class="chkbxEquipeDuJoueur" value="'.$equipe["id_equipes"].'">'.$equipe["team"].'<br>';
	    }
	    $query="SELECT id_equipes, nom FROM equipes ORDER BY nom";
            $requete_preparee=$connexion->prepare($query);
            $requete_preparee->execute();
            echo'Ajouter à l\'équipe : <select id="SelectAjoutJoueurEquipe" style="width: 200px;"><option></option>';              
            while($equipe=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
            {
                echo '<option value="'.$equipe["id_equipes"].'" style="color:#000">'.$equipe["nom"];
		echo "</option>";
            }
            echo'
                </select>
                <input type="button" id="submitChangementEquipeDuJoueur" value="Validez les changements">
            ';
	    echo'<input type="hidden" id="idJoueurAdminForEquipe" value="'.$_POST['id_joueur'].'">';
	    
	}
	
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";
?>                

























