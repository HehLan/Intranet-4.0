<?php
	
	// on vérifie si l'équipe existe déjà
	if($id_equipes == 0)
	{
		// verifie que le nom d'équipe n'existe pas deja
		$query = "SELECT nom FROM equipes WHERE nom=:nom"; 
		$requete_preparee=$connexion->prepare($query);
		$requete_preparee->bindValue('nom',$nomteam, PDO::PARAM_STR);
		$result=$requete_preparee->execute();
		$nbr=$requete_preparee->rowCount();
		
		if($nbr == 0)
		{
				
			try{
				// Creation de l'equipe
				$query = "INSERT INTO equipes (nom, mot_de_passe,tag) VALUES (:nom,:mot_de_passe,:tag)";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->bindvalue("nom",$nomteam,PDO::PARAM_STR);
				$requete_preparee->bindvalue("mot_de_passe",$mot_de_passe,PDO::PARAM_STR);
				$requete_preparee->bindvalue("tag",$tag,PDO::PARAM_STR);
				$requete_preparee->execute();
				// Creation de la liaison equipe - joueur
				$id_equipes=$connexion->lastInsertId();
				$query="INSERT INTO equipes_joueur (id_joueur,id_equipes) VALUES (:id_joueur,:id_equipes)";
				$requete_preparee=$connexion->prepare($query);
				$requete_preparee->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
				$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
				$requete_preparee->execute();
				echo'Votre équipe a été créée!<br/>';
				echo '<script>
					$( "#formInscription" ).css({color: "#0f0"});
					</script>
					';
			}
			catch(Exception $e){
				echo "Une erreur est survenue";
				echo "Message = ".$e->getMessage();
				echo '<br/><script>
					$( "#formInscription" ).css({color: "#f00"});
					</script>
					';
				die();
			}
			
		}
		else
		{
			echo "Le nom de la team est déjà pris <br/>";
		}
	}
	else
	{
		// sinon l'équipe existe déjà et donc on lie le joueur à cette équipe si il a le bon mot de passe
		
		// Verification bon mot de passe
		$query = "SELECT * FROM equipes WHERE id_equipes = :id_equipes AND mot_de_passe = :mdp ";
		$requete_preparee = $connexion->prepare($query);
		$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
		$requete_preparee->bindValue("mdp",$mot_de_passe,PDO::PARAM_INT);
		$result=$requete_preparee->execute();
		$nbr=$requete_preparee->rowCount();
		if($nbr != 0)
		{
			
			try{
				$query = "INSERT INTO equipes_joueur (id_joueur,id_equipes) VALUES (:id_joueur,:id_equipes)";
				$requete_preparee = $connexion->prepare($query);
				$requete_preparee->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
				$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
				$requete_preparee->execute();
				echo'Vous avez été ajoutée à votre team!<br/>';
				echo '<script>
					$( "#formInscription" ).css({color: "#0f0"});
					</script>
					';
			}
			catch(Exception $e){
				echo "Une erreur est survenue<br/>";
				echo "Message = ".$e->getMessage();
				echo '<br/><script>
					$( "#formInscription" ).css({color: "#f00"});
					</script>
					';
				die();
			}
			
		}
		else
		{
			echo 'Le mot de passe pour rejoindre l\'équipe est incorrect!<br/>';
		}
	}
	
?>