<?php
session_start();
require_once('classAuth.php');

function isPlay($idJoueur, $idTournoi, &$connexion, &$pseudoJeux){
    //$sqlPlay="SELECT * FROM joueurtournoi jt WHERE jt.id_joueur = :id_joueur AND jt.id_tournoi = :id_tournoi " ;
    $sqlPlay="SELECT * FROM joueurtournoi WHERE id_joueur = :id_joueur AND id_tournoi = :id_tournoi";
    try {
        $req = $connexion->prepare($sqlPlay);
        $req->execute(array(
            'id_joueur'=>$idJoueur,
            'id_tournoi'=>$idTournoi
        ));
        $jeux = $req->fetch();
        if(empty($jeux)){
            
            //ne joue pas a ce jeux
            return 'cross.png';
            $pseudoJeux='';
        }
        else {
            $pseudoJeux=$jeux['pseudoJeux'];
            //joue a ce jeux
            return 'check.png';
        }
    }
    
    catch(PDOException $e) {
        return '';
    }
    
    
}

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
	if(!empty($_POST['id_joueur'])){
	    
	    $sql="SELECT j.*, e.nom AS team
		FROM  joueurs j
		LEFT OUTER JOIN equipes_joueur ej ON j.id_joueur = ej.id_joueur
		LEFT OUTER JOIN equipes e ON e.id_equipes = ej.id_equipes
		WHERE j.id_joueur = :id_joueur
	    ";
	    $requete_preparee = $connexion->prepare($sql);
	    $requete_preparee->bindValue("id_joueur",$_POST['id_joueur'],PDO::PARAM_INT);
	    $requete_preparee->execute();
	    $nbr=$requete_preparee->rowCount();
	    $joueur = $requete_preparee->fetch();
	    $pseudoJeuxLOL='';
	    $pseudoJeuxCOD='';
	    $pseudoJeuxUT='';
	    $pseudoJeuxTM='';
	    
	    if($nbr!=0){
?>
		<label for="InfoJoueurPseudo">Pseudo :</label>
		<input type="text" id="InfoJoueurPseudo" readonly value="<?php echo $joueur['pseudo']; ?>"><br >
		
		<label for="InfoJoueurNom">Nom :</label>
		<input type="text" id="InfoJoueurNom" readonly value="<?php echo $joueur['nom']; ?>"><br >
		
		<label for="InfoJoueurPrenom">Prénom :</label>
		<input type="text" id="InfoJoueurPrenom" readonly value="<?php echo $joueur['prenom']; ?>"><br >
		
		<label for="InfoJoueurEmail">Email :</label>
		<input type="text" id="InfoJoueurEmail" readonly value="<?php echo $joueur['email']; ?>"><br >
		<?php
		$i=1;
		do {
		    echo'<label for="InfoJoueurTeam">Team'.$i.' :</label>';
		    echo'<input type="text" id="InfoJoueurTeam" readonly value="'.$joueur['team'].'"><br >';
		    $i++;
		} while ($joueur = $requete_preparee->fetch());
		?>
		
		<table class="imgJeuxJoueurs">
		    <thead>
		      <tr>
			<th>LOL</th>
			<th>COD</th>
			<th>UT3</th>
			<th>TM</th>
		      </tr>
		    </thead>
			
		    <tbody>
			<?php
			    try {
				
				
				echo'<tr>';
				  
				echo'
				    
				    <td><img class="imgJeuxJoueurs" src="img/'.isPlay($_POST['id_joueur'],"1",$connexion,$pseudoJeuxLOL).'" alt="inscritLOL"></td>
				    <td><img class="imgJeuxJoueurs" src="img/'.isPlay($_POST['id_joueur'],"2",$connexion,$pseudoJeuxCOD).'" alt="inscritCOD"></td>
				    <td><img class="imgJeuxJoueurs" src="img/'.isPlay($_POST['id_joueur'],"4",$connexion,$pseudoJeuxUT).'" alt="inscritUT3"></td>
				    <td><img class="imgJeuxJoueurs" src="img/'.isPlay($_POST['id_joueur'],"3",$connexion,$pseudoJeuxTM).'" alt="inscritTM"></td>
				';
				  
				echo'</tr>';
				echo'<tr>';
				  
				echo'
				    
				    <td>'.$pseudoJeuxLOL.'</td>
				    <td>'.$pseudoJeuxCOD.'</td>
				    <td>'.$pseudoJeuxUT.'</td>
				    <td>'.$pseudoJeuxTM.'</td>
				';
				  
				echo'</tr>';
				
			    }
			    
			    catch(PDOException $e) {
				echo 'Base de données est indisponible pour le moment!';
			    }
			      
			?>
		    </tbody>
		</table> 
<?php
		
	    }
	    else echo "L'équipe ne contient pas de joueur!";
	}
	else echo 'aucune donnée reçue!';
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";

?>