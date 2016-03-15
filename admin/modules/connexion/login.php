<?php
    session_start();
    require_once("../connect.php");
    $sql="SELECT pseudo, password, level FROM joueurs WHERE pseudo = :pseudo AND password = :mdp";
    
    if(!empty($_POST['pseudo'])&&!empty($_POST['pwd'])){
        
        $pseudo=$_POST['pseudo'];
        $password = sha1($_POST['pwd']);
        $req = $connexion->prepare($sql);
        $req->execute(array(
            ':pseudo'=>$pseudo,
            ':mdp' => $password
        ));
        $joueur = $req->fetch();
        if(!empty($joueur)){
            //vérifier si la connexion est correcte
            
            $_SESSION['Auth']=array(
            'pseudo' => $pseudo,
            'password' => $password
            );
            
            echo '<script>
                $("#formConnexion").css({color: "#00f"});
                $( "#formConnexion" ).html("Connexion réussie!");
                setTimeout(function(){location.reload();},2000);</script>
                ';
        }
        else{
            echo '<script>
                $("#erreurLogin").css({color: "#f00"});
                $( "#erreurLogin" ).html("Les identifiants sont incorrects!");
                $( "#ConPseudo" ).val("");
                $( "#ConPwd" ).val("");
                </script>
                ';
            
        }
    }
    else{
        echo '<script>
        $("#erreurLogin").css({color: "#f00"});
        $( "#erreurLogin" ).html("Il faut d\'abord remplir les champs!");
        </script>
        ';
    }
?>