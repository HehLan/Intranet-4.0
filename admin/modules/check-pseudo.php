<?php
require_once("connect.php");

//vérifier pseudo
$sql="SELECT * FROM joueurs WHERE pseudo = :pseudo";
if(!empty($_POST["pseudo"])){
    $pseudo=$_POST["pseudo"];
    $req = $connexion->prepare($sql);
    $req->execute(array(
        ':pseudo'=>$pseudo
    ));
    $joueur = $req->fetch();
    if(!empty($joueur)){
        echo'Ce pseudo est déjà pris!';
        echo '<script>
                $( "#pseudobox" ).css({color: "#f00"});
                </script>
                ';
    }
    else {
        echo'Ce pseudo est disponible!';
        echo '<script>
                $( "#pseudobox" ).css({color: "#0f0"});
                </script>
                ';
    }
}
else echo'';

//vérifier pseudoLOL dans les tournois
$sql="SELECT * FROM joueurtournoi WHERE pseudoJeux = :pseudo";
if(!empty($_POST["pseudoLOL"])){
    $pseudo=$_POST["pseudoLOL"];
    $req = $connexion->prepare($sql);
    $req->execute(array(
        ':pseudo'=>$pseudo
    ));
    $joueur = $req->fetch();
    if(!empty($joueur)){
        echo'Ce pseudo existe déjà!';
        echo '<script>
                $( "#pseudoboxLOL" ).css({color: "#f00"});
                </script>
                ';
    }
    else {
        echo'Ce pseudo est disponible!';
        echo '<script>
                $( "#pseudoboxLOL" ).css({color: "#0f0"});
                </script>
                ';
    }
}
else echo'';

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