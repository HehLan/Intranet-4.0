<?php


$id_equipes=$_POST['id_equipes'];

require_once('../common/connect.php');
$query="SELECT j.pseudo, empl.id_emplacement, e.nom
FROM `equipes_joueur` AS `ej` , `joueurs` AS `j` , `equipes` AS `e` , `emplacement` AS `empl`
WHERE ej.id_joueur = j.id_joueur
AND ej.id_equipes = :id_equipes
AND ej.id_equipes = e.id_equipes
AND j.id_emplacement = empl.id_emplacement";

$requete_preparee=$connexion->prepare($query);
$requete_preparee->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
$requete_preparee->execute();
echo "<script type='text/javascript'>";

while($emplacements=$requete_preparee->fetch()) 

{
    echo "$('#".$emplacements['id_emplacement']."').css({background : '#ffaca3'});"; 
   
}
echo "$('#dialogInfo_equipe').css({display:'block'});";
echo "</script>";

echo "<center><u>Information equipe :</u></center>";   

$query2="SELECT pseudo,equipes.nom FROM equipes_joueur,joueurs,equipes WHERE joueurs.id_joueur=equipes_joueur.id_joueur and equipes_joueur.id_equipes='$id_equipes' and equipes.id_equipes=equipes_joueur.id_equipes ORDER BY pseudo ASC ";
 
$requete_preparee1=$connexion->prepare($query2);
$requete_preparee1->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
$requete_preparee1->execute();

$pseudo=array();
$nomequipes=array();
while($equipe=$requete_preparee1->fetch()) 

{
    $nomequipes[]=$equipe['nom'];
    $pseudo[]=$equipe['pseudo'];
}
echo "<u>Team :</u>";
echo $nomequipes[0];
 echo "<br>";
echo "<u>Pseudo Joueur :</u>";
echo implode(', ', $pseudo);


$query2="SELECT nomTournoi FROM equipes_tournoi,tournoi where equipes_tournoi.id_equipe='$id_equipes' and tournoi.id_tournoi=equipes_tournoi.id_tournoi";
 
$requete_preparee2=$connexion->prepare($query2);
$requete_preparee2->bindValue("id_equipes",$id_equipes,PDO::PARAM_INT);
$requete_preparee2->execute();
   echo "<br>";
    echo "<u>Tournoi :</u>";
    $nomTournoi=array();
while($equipe1=$requete_preparee2->fetch()) 

{
    $nomTournoi[]=$equipe1['nomTournoi']; 
} 
echo implode(', ', $nomTournoi);

?>