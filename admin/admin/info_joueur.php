<?php


$id_emplacement=$_POST['id_emplacement'];
require_once("connect.php");
$query="SELECT joueurs.id_joueur,nom,pseudo,prenom,IP from joueurs,emplacement where joueurs.id_emplacement=:id_emplacement and joueurs.id_emplacement=emplacement.id_emplacement";

$requete_preparee=$connexion->prepare($query);
$requete_preparee->bindValue("id_emplacement",$id_emplacement,PDO::PARAM_INT);
$requete_preparee->execute();
echo "<script type='text/javascript'>";

while($joueur=$requete_preparee->fetch()) 

{
    
    echo "$('#dialogInfo_joueur').css({display:'block'});";
    echo "</script>";
    echo "<center><u>Information Joueur :</u></center>";
    echo "<u>Pseudo :</u>".$joueur['pseudo'];
    $id_joueur=$joueur['id_joueur'];
    echo "<br>";
    echo "<u>Nom :</u>".$joueur['nom'];
    echo "<br>";
    echo "<u>Prénom :</u>".$joueur['prenom'];
    echo "<br>";
    $id_joueur=$joueur['id_joueur'];
    echo "<u>IP :</u>";
    echo $joueur['IP'];
    echo "<br>";

    
}
$query1="SELECT nom FROM equipes,equipes_joueur where equipes_joueur.id_joueur='$id_joueur' and equipes.id_equipes=equipes_joueur.id_equipes";
 
$requete_preparee1=$connexion->prepare($query1);
$requete_preparee1->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
$requete_preparee1->execute();
echo "<br>";
echo "<u>Equipe :</u>";
$nomEquipe=array();
while($equipe=$requete_preparee1->fetch()) 

{
    
    $nomEquipe[]=$equipe['nom'];
}
echo implode(', ', $nomEquipe);
$query1="SELECT nomTournoi FROM joueurtournoi,tournoi where joueurtournoi.id_joueur='$id_joueur' and tournoi.id_tournoi=joueurtournoi.id_tournoi";
 
$requete_preparee1=$connexion->prepare($query1);
$requete_preparee1->bindValue("id_joueur",$id_joueur,PDO::PARAM_INT);
$requete_preparee1->execute();
   echo "<br>";
    echo "<u>Tournoi :</u>";
    $nomTournoi=array();
while($equipe=$requete_preparee1->fetch()) 

{
    $nomTournoi[]=$equipe['nomTournoi']; 
} 
echo implode(', ', $nomTournoi);



?>