<?php
require_once("connect.php");

//vérifier le nom de la team
$sql="SELECT * FROM equipes WHERE nom = :pseudo";
if(!empty($_POST["Team"])){
    $pseudo=$_POST["Team"];
    $req = $connexion->prepare($sql);
    $req->execute(array(
        ':pseudo'=>$pseudo
    ));
    $joueur = $req->fetch();
    if(!empty($joueur)){
        echo'Le nom de team existe déjà!';
        echo '<script>
                $( "#pseudoboxTeam" ).css({color: "#f00"});
                </script>
                ';
    }
    else {
        echo'Le nom de team est disponible!';
        echo '<script>
                $( "#pseudoboxTeam" ).css({color: "#0f0"});
                </script>
                ';
    }
}
else echo'';

//vérifier le tag de la team
$sql="SELECT * FROM equipes WHERE tag = :tag";
if(!empty($_POST["TagTeam"])){
    $tag=$_POST["TagTeam"];
    $req = $connexion->prepare($sql);
    $req->execute(array(
        ':tag'=>$tag
    ));
    $joueur = $req->fetch();
    if(!empty($joueur)){
        echo'Le tag de votre team existe déjà!';
        echo '<script>
                $( "#pseudoboxTagTeam" ).css({color: "#f00"});
                </script>
                ';
    }
    else {
        echo'Le tag de votre team est disponible!';
        echo '<script>
                $( "#pseudoboxTagTeam" ).css({color: "#0f0"});
                </script>
                ';
    }
}
else echo'';
?>