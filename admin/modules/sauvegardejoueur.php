<?php	
	$id_joueur=0;
	//vérification si le pseudo existe déjà
	$query = "SELECT pseudo FROM joueurs WHERE pseudo=:pseudo";
	$requete_preparee=$connexion->prepare($query);
	$requete_preparee->bindValue('pseudo',$pseudo, PDO::PARAM_STR);
	$result=$requete_preparee->execute();

	$nbr=$requete_preparee->rowCount();
	// on vérifie qu'on n'a pas le même pseudo dans la BD
	if($nbr == 0)
	{// Creation du joueur
		$query = "INSERT INTO joueurs (nom,prenom,pseudo,email,password,sexe,gsm,date_de_naissance) VALUES(:nom,:prenom,:pseudo,:email,:password,:sexe,:gsm,:date_de_naissance)";
		$requete_preparee=$connexion->prepare($query);
		$requete_preparee->bindvalue("nom",$nom,PDO::PARAM_STR);
		$requete_preparee->bindvalue("prenom",$prenom,PDO::PARAM_STR);
		$requete_preparee->bindvalue("pseudo",$pseudo,PDO::PARAM_STR);
		$requete_preparee->bindvalue("email",$email,PDO::PARAM_STR);
		$requete_preparee->bindvalue("password",$password,PDO::PARAM_STR);
		$requete_preparee->bindvalue("sexe",$sexe,PDO::PARAM_STR);
		$requete_preparee->bindvalue("gsm",$gsm,PDO::PARAM_STR);
		$requete_preparee->bindvalue("date_de_naissance",date("Y-m-d", strtotime(str_replace('/', '-', $date_de_naissance))),PDO::PARAM_STR);
		
		try {
			$requete_preparee->execute();
			
			// Creation des liaisons jeux - joueurs
			//récupère l'id du dernier ajout dans la BD
			$id_joueur=$connexion->lastInsertId();
			/* on vérifie qu'il y a au moins un tournoi où le joueur est inscrit et
				on vérifie aussi qu'il n'y a pas plus de tournois que le nombre maximum possible (éviter
				les boucles que l'utilisateur aurait pu créer pour nous nuir)*/
			if(isset($tournois) and (count($tournois)<=5)){
				$query="INSERT INTO joueurtournoi (id_joueur,id_tournoi,pseudoJeux) VALUES (:id_joueur,:id_tournoi, :pseudo)";
				$requete_preparee=$connexion->prepare($query);
				foreach($tournois as $chkbx){
					
					$requete_preparee->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
					$requete_preparee->bindValue("id_tournoi",$chkbx,PDO::PARAM_INT);
					if($chkbx==1){
						$requete_preparee->bindvalue("pseudo",$pseudoLOL,PDO::PARAM_STR);
					}
					else {
						$requete_preparee->bindvalue("pseudo","",PDO::PARAM_STR);
					}
					$requete_preparee->execute();
					
				}
			}
			
			
			//vérification si le pseudo existe déjà
			$query = "SELECT * FROM joueurs WHERE pseudo=:pseudo AND password= :mdp";
			$requete_preparee=$connexion->prepare($query);
			$requete_preparee->bindValue('pseudo',$pseudo, PDO::PARAM_STR);
			$requete_preparee->bindValue('mdp',$password, PDO::PARAM_STR);
			$result=$requete_preparee->execute();
			$nbr=$requete_preparee->rowCount();
			if($nbr != 0)
			{
				echo'Votre compte est créé!<br/>';
				echo '<script>
					$( "#formInscription" ).css({color: "#0f0"});
					</script>
					';
				//mail de confirmation
				$to = $email;
				$sujet = "HEHLAN : confirmation d'inscription";
				$header="From: \"HeHLan\" <hehlan.be@gmail.com>\n";
				$header .= "Reply-to: \"HeHLan\" <hehlan.be@gmail.com>\n";
				$message = "
				Félicitations ".$prenom."! Vous êtes désormais inscrit à la HeHLan.
				
						Votre pseudo : ".$pseudo."    
						Votre mot de passe : ".$_POST['password']."
				
				
				Si vous avez des questions, n'hésitez pas à nous contacter.
				
				Entrainez-vous pour tout déchirer le jour de la LAN :)
				
				L'équipe de la HeHLan.
				";
				
				if (mail($to,$sujet,$message,$header)){
				    echo "Un email de confirmation vous a été envoyé. <br/> <br/>";
				}
			}else{
				echo "Une erreur est survenue lors de l'inscription! Veuillez réessayer plus tard.<br/>";
				exit;
			}
			
			
		}
		catch(Exception $e){
			echo "Une erreur est survenue<br/>";
			echo "Message = ".$e->getMessage();
			echo '<script>
				$( "#formInscription" ).css({color: "#f00"});
				</script>
				';
			die();
		}
		
	}
	else
	{
		echo "Le pseudo existe deja";
	}
?>