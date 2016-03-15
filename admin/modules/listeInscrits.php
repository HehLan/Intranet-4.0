<?php
require_once("modules/connect.php");

function isPlay($idJoueur, $idTournoi, &$connexion){
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
            
        }
        else {
            
            //joue a ce jeux
            return 'check.png';
            
        }
    }
    
    catch(PDOException $e) {
        return '';
    }
    
    
}
$sql="SELECT j.pseudo AS pseudo, e.nom AS team, j.id_joueur AS id, j.paye
    FROM  joueurs j
    LEFT OUTER JOIN equipes_joueur ej ON j.id_joueur = ej.id_joueur
    LEFT OUTER JOIN equipes e ON e.id_equipes = ej.id_equipes
    WHERE ej.id_equipes !=1 OR ej.id_equipes IS NULL
    ORDER BY e.nom
";
$req = $connexion->query($sql);


/***************************************
 *     compte les nombres de joueurs   *
 **************************************/
$nbrPaye=0;
$nbrInscrit=$req->rowCount();
$nbrTeam=0;
$nbrJoueurLOL=0;
$nbrJoueurCOD=0;
$nbrJoueurUT=0;
$nbrJoueurTM=0;

$sqlPaye="SELECT * FROM joueurs WHERE paye = :paye";
    $reqPaye = $connexion->prepare($sqlPaye);
    $reqPaye->execute(array(
        'paye'=> 1
    ));
    $nbrPaye=$reqPaye->rowCount();
$sqlTeam="SELECT DISTINCT id_equipes FROM equipes_joueur";
    $reqTeam = $connexion->query($sqlTeam);
    $nbrTeam=$reqTeam->rowCount();
$sqlJoueurJeux="SELECT * FROM joueurtournoi WHERE id_tournoi = :id_tournoi";
    //LOL
    $reqNbrJeux = $connexion->prepare($sqlJoueurJeux);
    $reqNbrJeux->execute(array(
        'id_tournoi'=> 1
    ));
    $nbrJoueurLOL=$reqNbrJeux->rowCount();
    
    //COD
    $reqNbrJeux = $connexion->prepare($sqlJoueurJeux);
    $reqNbrJeux->execute(array(
        'id_tournoi'=> 2
    ));
    $nbrJoueurCOD=$reqNbrJeux->rowCount();
    
    //UT
    $reqNbrJeux = $connexion->prepare($sqlJoueurJeux);
    $reqNbrJeux->execute(array(
        'id_tournoi'=> 4
    ));
    $nbrJoueurUT=$reqNbrJeux->rowCount();
    
    //TM
    $reqNbrJeux = $connexion->prepare($sqlJoueurJeux);
    $reqNbrJeux->execute(array(
        'id_tournoi'=> 3
    ));
    $nbrJoueurTM=$reqNbrJeux->rowCount();



?>

    
    
    
<table id="listeInscrit">
  <thead>
    <tr>
      <th>Payé</th>
      <th>Pseudo</th>
      <th>Team</th>
      <th>LOL</th>
      <th>COD</th>
      <th>UT3</th>
      <th>TM</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td><?php echo $nbrPaye; ?> inscriptions payées</td>
      <td><?php echo $nbrInscrit; ?> inscrits</td>
      <td><?php echo $nbrTeam; ?> equipes différentes</td>
      <td><?php echo $nbrJoueurLOL; ?> joueurs</td>
      <td><?php echo $nbrJoueurCOD; ?> joueurs</td>
      <td><?php echo $nbrJoueurUT; ?> joueurs</td>
      <td><?php echo $nbrJoueurTM; ?> joueurs</td>
    </tr>
  </tfoot>
  <tbody>
   
    
<?php
    try {
        
        while($inscrit = $req->fetch()){
            echo'<tr>';
            if (!empty($inscrit)){
                
                // affiche si le joueur a paye ou non
                if ($inscrit['paye']==0){
                    //joueur n'a pas paye
                    echo'<td><img src="img/cross.png" alt="nonpaye"></td>';
                }else {
                    //joueur a paye
                    echo'<td><img src="img/check.png" alt="paye"></td>';
                }
                
                
                echo'
                    <td >'.$inscrit['pseudo'].'</td>
                    <td >'.$inscrit['team'].'</td>
                    <td><img src="img/'.isPlay($inscrit['id'],"1",$connexion).'" alt="inscritLOL"></td>
                    <td><img src="img/'.isPlay($inscrit['id'],"2",$connexion).'" alt="inscritCOD"></td>
                    <td><img src="img/'.isPlay($inscrit['id'],"4",$connexion).'" alt="inscritUT3"></td>
                    <td><img src="img/'.isPlay($inscrit['id'],"3",$connexion).'" alt="inscritTM"></td>
                ';
                
                
                
            }else echo "La liste des joueurs inscrits n'est pas encore disponible!";
            
            echo'</tr>';
        }
    }
    
    catch(PDOException $e) {
        echo 'Base de données est indisponible pour le moment!';
    }
      
?>
    
     
  </tbody>
</table>


