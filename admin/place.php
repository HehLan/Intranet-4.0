<?php


$id_place=$_POST["SelectEmplacement"];
$pseudo=$_POST["SelectPseudo"];


echo "<script type='text/JavaScript'>";

require_once("modules/connect.php");
$sql="select * from joueurs where id_emplacement=$id_place";
$requete_preparee=$connexion->prepare($sql);
$requete_preparee->execute();
$data=$requete_preparee->fetch(PDO::FETCH_BOTH);
if ($data[0] == 0)
{    
$query="UPDATE joueurs SET id_emplacement='$id_place' WHERE pseudo='$pseudo'";
$requete_preparee1=$connexion->prepare($query);
$requete_preparee1->bindvalue("id_emplacement",$id_place,PDO::PARAM_INT);
$requete_preparee1->execute();
echo "alert('";
echo '                                  OK \n';
echo "la place de ";
echo $pseudo;
echo " est place au numero ";
$query1="select numero from emplacement where id_emplacement='$id_place'";
$requete_preparee1=$connexion->prepare($query1);
$requete_preparee1->execute();
while($joueurs=$requete_preparee1->fetch(PDO::FETCH_ASSOC)) 
	{
echo $joueurs['numero'];
        }
}
else
{
echo "alert('";
echo '                           ERREUR \n';
echo "la place numero ";
$query1="select numero from emplacement where
id_emplacement='$id_place'";	
$requete_preparee1=$connexion->prepare($query1);
$requete_preparee1->execute();
while($joueurs=$requete_preparee1->fetch(PDO::FETCH_ASSOC)) 
	{
echo $joueurs['numero'];
        }
echo " est prise par ";
$query1="select pseudo,numero from emplacement,joueurs where joueurs.id_emplacement='$id_place' and joueurs.id_emplacement=emplacement.id_emplacement";	
$requete_preparee2=$connexion->prepare($query1);
$requete_preparee2->execute();
$pseudo=array();
while($joueurs=$requete_preparee2->fetch(PDO::FETCH_ASSOC)) 
	{
            echo '->';
$pseudo[]=$joueurs['pseudo'];

        }
echo implode(', ', $pseudo);
}
echo "');";
echo "window.location.href='emplacements.php';";
echo "</script>";

?>

