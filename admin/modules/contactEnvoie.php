<?php
    if(!empty($_POST)){
        $valid=true;
        $erreurNom='';
        $erreurPrenom='';
        $erreurEmail='';
        $erreurMessage='';
        if(empty($_POST['nom'])){
            $valid=false;
            $erreurNom="Vous n'avez pas rempli votre nom. \n";
        }
        if(empty($_POST['prenom'])){
            $valid=false;
            $erreurPrenom="Vous n'avez pas rempli votre prénom. \n";
        }
        if(!preg_match("/^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$/i",$_POST['email'])){
            $valid=false;
            $erreurEmail="Votre email n'est pas valide. \n";
        }
        if(empty($_POST['email'])){
            $valid=false;
            $erreurEmail="Vous n'avez pas rempli votre email. \n";
        }
        if(empty($_POST['message'])){
            $valid=false;
            $erreurMessage="Vous n'avez pas rempli le champs message. \n";
        }
        
        if($valid){
            //l'adresse du destinataire
            $to = "hehlan.be@gmail.com";
            $sujet = stripcslashes($_POST['nom']).' '.stripcslashes($_POST['prenom'])." nous a contacté.";
            $header="From: \"".stripcslashes($_POST['nom'])." ".stripcslashes($_POST['prenom'])."\"<".$_POST['email'].">\n";
            $header .= "Reply-to: \"".stripcslashes($_POST['nom'])." ".stripcslashes($_POST['prenom'])."\" <".$_POST['email'].">\n";
            $message = stripcslashes($_POST['message']);
            if (mail($to,$sujet,$message,$header)){
                echo "votre message a bien été envoyé.";
                echo '
                    <script>
                        $("#contact input,textarea").css({background: "none"});
                        $("#contact input,textarea").val("");
                        $("#contact input#contactEnvoyer").val("Envoyer");
                        $("#reponseContact").css({color: "#0f0"});
                    </script>
                ';
            }
            else {
                echo "Erreur : votre message n'a pas été envoyé. Ré-essayez plus tard!";
                echo '
                    <script>
                        $("#reponseContact").css({color: "#f00"});
                    </script>
                ';
            }
        }
        else{
            echo $erreurNom.$erreurPrenom.$erreurEmail.$erreurMessage;
        }
        
    }
?>
