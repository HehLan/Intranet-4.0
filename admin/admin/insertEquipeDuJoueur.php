<?php
session_start();
require_once('classAuth.php');

if (Auth::isLogged()){
    if (Auth::isAllow(3)){
        if(!empty($_POST['inscrit'])){
            try
            {
                
                //on tente d'exécuter les requêtes suivantes dans une transactions
                
                //on lance la transaction
                $connexion->beginTransaction();
                //supprime toutes les lignes la table
                $sql="DELETE FROM equipes_joueur WHERE id_joueur = :id_joueur";
                $req = $connexion->prepare($sql);
                $req->bindValue("id_joueur",$_POST['id_joueur'],PDO::PARAM_INT);
                $req->execute();
                $query="INSERT INTO equipes_joueur (id_joueur,id_equipes) VALUES ";
                $i=0;
                foreach($_POST['inscrit'] as $row){
                    if($i==0){
                        $query.="(".$_POST['id_joueur'].",".$row.")";
                    }else
                    {
                        $query.=",(".$_POST['id_joueur'].",".$row.")";
                    }
                    $i++;
                }
                $query.=";";
                
                $req = $connexion->prepare($query);
                $req->execute();
                $connexion->commit();
                echo'requête réussie!';
            }
            catch(Exception $e) //en cas d'erreur
            {
                //on annule la transation
                $connexion->rollback();
            
                //on affiche un message d'erreur ainsi que les erreurs
                echo 'Tout ne s\'est pas bien passé!<br />';
                echo 'Erreur : '.$e->getMessage().'<br />';
                echo 'N° : '.$e->getCode();
            
                //on arrête l'exécution s'il y a du code après
                exit();
            }
        }else if(!empty($_POST['deleteAll'])){
            try
            {
                
                //on tente d'exécuter les requêtes suivantes dans une transactions
                
                //on lance la transaction
                $connexion->beginTransaction();
                //supprime toutes les lignes la table
                $sql="DELETE FROM equipes_joueur WHERE id_joueur = :id_joueur";
                $req = $connexion->prepare($sql);
                $req->bindValue("id_joueur",$_POST['id_joueur'],PDO::PARAM_INT);
                $req->execute();
                $connexion->commit();
                echo'requête réussie!';
            }
            catch(Exception $e) //en cas d'erreur
            {
                //on annule la transation
                $connexion->rollback();
            
                //on affiche un message d'erreur ainsi que les erreurs
                echo 'Tout ne s\'est pas bien passé!<br />';
                echo 'Erreur : '.$e->getMessage().'<br />';
                echo 'N° : '.$e->getCode();
            
                //on arrête l'exécution s'il y a du code après
                exit();
            }
        }
        else echo'aucune donnée reçue';
    }
    else echo "Vous n'êtes pas autorisé à effectuer cette modification!";
}
else echo "Vous n'êtes pas connecté!";

?>