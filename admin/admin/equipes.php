<?php
require_once("connect.php");

$query="SELECT id_equipes, nom FROM equipes ORDER BY nom";
$requete_preparee=$connexion->prepare($query);
$requete_preparee->execute();

?>
<style type="text/css">
    div#ListeEquipeAdmin legend{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
    }
    div#ListeEquipeAdmin fieldset{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
        padding-top: 20px;
        padding-bottom: 40px
    }
    table.listeEquipes {
        text-align: center;
        color: #CCC; 
        font-family: Helvetica, Arial, sans-serif;
        font-size: 18px;
        width: 95%; 
        border-collapse: collapse;
        border-spacing: 0;
        margin:auto;
        margin-top:40px;
    }
    
    .listeEquipes td, th {
        border: 1px solid #CCC;
        width: 50%;
    } 
    
    .listeEquipes th {
        background: none;
        font-weight: bold;
        font-size: 30px;
    }
    
    .listeEquipes td {
        background: none; 
        text-align: center; 
    }
    div#ListeEquipeAdmin div{
        height: 320px;
        overflow: auto;
        border: 1px silver solid;
    }
    div#ListeEquipeAdmin div#listeEquipeJoueurAdmin{
        height: 260px;
        overflow: auto;
        border: 1px silver solid;
    }
    h6.EquipeAdmin, h6.EquipeJoueurAdmin {
        font-size: 20px;
        font-weight: normal;
        cursor: pointer;
    }
    div#ListeEquipeAdmin input[type='button']{
        background: none;
        color: #fff;
        width: 400px;
        font-size: 25px;
        font-weight: bold;
        height: 50px;
        margin-top: 20px;
        cursor: pointer;
        float: right;
        margin-right:40px;
    }
    div#ListeEquipeAdmin input[type='button']#submitNewPlayerInTeam{
        margin: auto;
        margin-top:5px;
        margin-bottom:5px;
        float: none;
        width: 95%;
    }
    table.imgJeuxJoueurs{
        width: 95%;
        margin: auto;
        margin-top: 30px;
    }
    img.imgJeuxJoueurs{
        width: 30px;
        height:30px;
    }
    table.imgJeuxJoueurs td, th{
        width: 25%;
    }
    div#ListeEquipeAdmin div#InfoJoueurEquipes {
        font: normal 18px Calibri, Arial, sans-serif;
        margin: 0;
        padding: 0;
        padding-top: 20px;
        padding-bottom: 20px;
        text-align: center;
        height: 410px;
    }
    #InfoJoueurEquipes input,
    #InfoJoueurEquipes textarea,
    #InfoJoueurEquipes legend,
    #InfoJoueurEquipes label,
    #InfoJoueurEquipes select {
      background: none;
      color: #fff;
    }
    #InfoJoueurEquipes input[type=text],
    #InfoJoueurEquipes input[type=password],
    #InfoJoueurEquipes select {
      font: normal 18px Calibri, Arial, sans-serif;
      width: 300px;
      margin-bottom: 10px;
      text-align: center;
    }
    #InfoJoueurEquipes label {
        display: inline-block;
        width: 250px;
        font: normal 1em Calibri, Arial, sans-serif;
        margin: 0;
        padding: 0;
        margin-bottom: 10px;
    }
#ListeEquipeAdmin div#creerTeamAdmin {
  font: normal 18px Calibri, Arial, sans-serif;
  margin: 0;
  padding: 0;
  padding-top: 20px;
  padding-bottom: 20px;
  text-align: center;
  height: auto;
}
#creerTeamAdmin input,
#creerTeamAdmin legend,
#creerTeamAdmin label {
  background: none;
  color: #fff;
  margin-top: 10px;
}

#creerTeamAdmin label {
  display: inline-block;
  width: 250px;
  font: normal 1em Calibri, Arial, sans-serif;
  margin: 0;
  padding: 0;
  margin-bottom: 10px;
}

#creerTeamAdmin input[type=text],
#creerTeamAdmin input[type=password] {
  font: normal 16px Calibri, Arial, sans-serif;
  width: 300px;
  margin-bottom: 10px;
}
#creerTeamAdmin legend {
  font: normal 1.5em Verdana, Arial, sans-serif;
  margin-left: -70px;
}
div#creerTeamAdmin div {
  width: auto;
  height: auto;
}

    
    
</style>
<script>
    $(document).ready(function() {
        
        $( ".EquipeAdmin" ).click(function() {
            
            $('#submitNewPlayerInTeam').show();
            $(".EquipeAdmin").css({background: "none"});
            $('#InfoJoueurEquipes').html('');
            $(".EquipeJoueurAdmin").css({background: "none"});
            
            $( this ).css({background: "rgba(0,0,255,0.2)"});
            $.ajax({ 
                type: "POST", 
                url: "admin/listeJoueursEquipe.php",
                data: "id_equipe="+$(this).attr("value"),
                success : function(contenu,etat){ 
                    $('#listeEquipeJoueurAdmin').html(contenu);
                }
            });
        });
        $("div").delegate(".EquipeJoueurAdmin", "click", function(){
           $(".EquipeJoueurAdmin").css({background: "none"});
            $( this ).css({background: "rgba(0,0,255,0.2)"});
            $.ajax({ 
                type: "POST", 
                url: "admin/InfoJoueurAdmin.php",
                data: "id_joueur="+$(this).attr("value"),
                success : function(contenu,etat){ 
                    $('#InfoJoueurEquipes').html(contenu);
                }
            });
        });
        $( "#infoEquipeAdmin" ).dialog({
            autoOpen: false,
            title:"joueur à ajouter",
            height: 300,
            width: 350,
            modal: true,
            close: function() {
                
            }
        });
        $("div").delegate("#submitNewPlayerInTeam", "click", function(){
            $( "#infoEquipeAdmin" ).dialog( "open" );
            $.ajax({ 
                type: "POST", 
                url: "admin/chargerListeJoueurs.php",
                data: "id_joueur="+$(this).attr("value"),
                success : function(contenu,etat){ 
                    $('#infoEquipeAdmin').html(contenu);
                }
            });
        });
        $("div").delegate("#submitSeclectJoueurEquipeAdmin", "click", function(){
            $( "#infoEquipeAdmin" ).dialog({ title: "Les équipes du joueur" });
            $.ajax({ 
                type: "POST", 
                url: "admin/equipesDuJoueur.php",
                data: "id_joueur="+$("#SelectJoueur option:selected").val(),
                success : function(contenu,etat){ 
                    $('#infoEquipeAdmin').html(contenu);
                }
            });
        });
        
        $("div").delegate("#submitChangementEquipeDuJoueur", "click", function(){
            var AuMoinsUneEquipe=false;
            var i=0;
            var id ='id_joueur='+$("#idJoueurAdminForEquipe").attr("value");
            $( ".chkbxEquipeDuJoueur:checked" ).each(function(){
                id +='&inscrit['+i+']='+$(this).attr("value");
                i++;
                AuMoinsUneEquipe=true;
            });
            if ($( "#SelectAjoutJoueurEquipe" ).val()) {
                id +='&inscrit['+i+']='+$( "#SelectAjoutJoueurEquipe" ).val();
            }else if (!AuMoinsUneEquipe){
                id +='&deleteAll=1';
            }
            $.ajax({ 
                type: "POST", 
                url: "admin/insertEquipeDuJoueur.php",
                data: id,
                success : function(contenu,etat){ 
                    $( "#infoEquipeAdmin" ).html(contenu);
                    $( "#infoEquipeAdmin" ).dialog({ buttons: [ { text: "Ok", click: function() { $( this ).dialog( "close" ); location.reload(); } } ] });
                    $( "#infoEquipeAdmin" ).dialog( "open" );
                }
            });
            
        });
        
        $( "#erreurNewTeamAdmin" ).dialog({
            autoOpen: false,
            title:"Nouvelle équipe",
            height: 300,
            width: 350,
            modal: true,
            close: function() {
                location.reload();
            },
            buttons: [ { text: "Ok", click: function() { $( this ).dialog( "close" );} } ]
        });
        
        $("div").delegate("#submitCreerNewEquipeAdmin", "click", function(){
            
            //remise en forme des inputs
            $("#new_psw_equipe").css({background: "none"});
            $("#new_psw_equipe2").css({background: "none"});
            $("#Team").css({background: "none"});
            $("#TagTeam").css({backgroundColor: "none"});            
            
            var erreur='';
            var valid = true;
            
            /***************************
            * Créer une team
            * ************************/
            
            //nom de la team
            if (!$('#Team').val()) {
                valid = false;
                $("#Team").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Vous n'avez pas rempli le nom de votre team <br \>";
            }
            else if ($('#Team').val().length<2) {
                valid = false;
                $("#Team").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le nom de votre team doit comporter au moins 2 caractères <br \>";
            }else if ($('#Team').val().length>40) {
                valid = false;
                $("#Team").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le nom de votre team est trop long<br \>";
            }
            else if ($('#pseudoboxTeam').css('color')!='rgb(0, 255, 0)') {
                valid = false;
                $("#Team").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur +="Le nom de team est déjà pris!<br \>";
            }
            
            //tag de la team
            if (!$('#TagTeam').val()) {
                valid = false;
                $("#TagTeam").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Vous n'avez pas rempli le tag de votre team <br \>";
            }
            else if ($('#TagTeam').val().length<1) {
                valid = false;
                $("#TagTeam").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le tag de votre team doit comporter au moins 1 caractère <br \>";
            }else if ($('#TagTeam').val().length>10) {
                valid = false;
                $("#TagTeam").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le tag de votre team est trop long<br \>";
            }
            else if ($('#pseudoboxTagTeam').css('color')!='rgb(0, 255, 0)') {
                valid = false;
                $("#TagTeam").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur +="Le tag de team est déjà pris!<br \>";
            }
            
            //password de la team
            if (!$('#new_psw_equipe').val()) {
                valid = false;
                $("#new_psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Vous n'avez pas rempli le mot de passe de la team <br \>";
            }else if ($('#new_psw_equipe').val().length<8) {
                valid = false;
                $("#new_psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le mot de passe de la team doit comporter au moins 8 caractères <br \>";
            }else if ($('#new_psw_equipe').val().length>30) {
                valid = false;
                $("#new_psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Le mot de passe de la team est trop long<br \>";
            }else if ($('#new_psw_equipe').val()!=$('#new_psw_equipe2').val()){
                valid = false;
                $("#new_psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                $("#new_psw_equipe2").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
                erreur += "Les 2 mots de passe de la team ne sont pas les mêmes <br \>";
            }
            
            if (valid) {
                $("#erreurNewTeamAdmin").html("Vérification en cours...");
                $("#erreurNewTeamAdmin").css({color: "#00f"});
                var id="Team="+$("#Team").val();
                id+="&TagTeam="+$("#TagTeam").val();
                id+="&new_psw_equipe="+$("#new_psw_equipe").val();
                $.ajax({ 
                    type: "POST", 
                    url: "admin/insertNewEquipe.php",
                    data:id,
                    success : function(contenu,etat){ 
                        $("#erreurNewTeamAdmin").html(contenu);
                        $("#erreurNewTeamAdmin").dialog( "open" );
                    }
                });
            }
            else {
                $("#erreurNewTeamAdmin").html(erreur);
                $("#erreurNewTeamAdmin").css({color: "#f00"});
            }
        });
        
        $('#ListeEquipeAdmin #Team').on('change', function() {
            $.ajax({ 
                type: "POST", 
                url: "admin/check-Team.php",
                data:"Team="+$('#Team').val(),
                success : function(contenu,etat){ 
                    $("#pseudoboxTeam").html(contenu);
                }
            
            });
        });
        $('#ListeEquipeAdmin #TagTeam').on('change', function() {
            $.ajax({ 
                type: "POST", 
                url: "admin/check-Team.php",
                data:"TagTeam="+$('#TagTeam').val(),
                success : function(contenu,etat){ 
                    $("#pseudoboxTagTeam").html(contenu);
                }
            
            });
        });
    });
    
</script>
<div id="ListeEquipeAdmin">
    <fieldset>
        <legend>Liste des équipes</legend>
        <table class="listeEquipes">
            <thead>
                <tr>
                    <th>Les équipes</th>
                    <th>Joueurs dans l'équipe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div id="listeEquipeAdmin">
                        <?php
                        try {
                            
                            while($equipes=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
                            {
                                echo'
                                    <h6 class="EquipeAdmin" value="'.$equipes["id_equipes"].'">'.$equipes["nom"].'</h6>
                                ';
                            }
                            
                        }
                        
                        catch(PDOException $e) {
                            echo 'Base de données est indisponible pour le moment!';
                        }
                            
                        ?>
                        </div>
                    </td>
                    <td>
                        <div id="listeEquipeJoueurAdmin">
                            
                        </div>
                        <input id="submitNewPlayerInTeam" type="button" value="Ajouter un joueur" style="display: none;">
                    </td>
                </tr>
                <tr>
                    <td  colspan="2" style="font-size: 30px; font-weight: bold; color: #fff;">
                        Informations du joueur
                    </td>
                </tr>
                <tr>
                    <td  colspan="2">
                        <div id="InfoJoueurEquipes">
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </fieldset>
    
    <fieldset>
        <legend>Ajouter une équipe</legend>
        <div id="creerTeamAdmin" >
                <label for="Team">Nom de la team :</label>
                <input type="text" name="Team" id="Team"><br />
                
                <div id="pseudoboxTeam" style="border: none; margin: 0px; padding: 0px"></div>
                
                <label for="TagTeam">Tag de la team :</label>
                <input type="text" name="TagTeam" id="TagTeam"><br />
                <div id="pseudoboxTagTeam" style="border: none; margin: 0px; padding: 0px"></div>
                        
                <label for="new_psw_equipe">Mot de passe : </label>
                <input type="password" name="new_psw_equipe" id="new_psw_equipe" /><br/>
                <label for="new_psw_equipe2">Confirmer mot de passe : </label>
                <input type="password" name="new_psw_equipe2" id="new_psw_equipe2" />
        </div>
        <div id="erreurNewTeamAdmin" style="height: auto;"></div>
        <input type="button" id="submitCreerNewEquipeAdmin" value="Ajouter l'équipe">
    </fieldset>
    <div id="infoEquipeAdmin" style="display: none"></div>
</div>




























