<?php
session_start();
require_once('modules/connect.php');
require_once('../common/utils.php');
$con=false;

require_once('modules/connexion/classAuth.php');

if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0) && $_SESSION['level']<=3) $con=true;
}
if(!$con)
{
 header('Location: ../index.php');
} 
$query="SELECT id_equipes, nom FROM equipes ORDER BY nom";
$requete_preparee=$connexion->prepare($query);
$requete_preparee->execute();
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="../img/logoheh.ico" >
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui2.css" type="text/css">
    <link rel="stylesheet" href="css/equipes.css" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/getXhr.js"></script>

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
            modal: true

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
</head>

<body style="background-color: #000;">

 	<div id="header">
		<div id="banner">
		    <a href="index.php">
		    <img src="img/logoheh.png" alt="HEHLan" width="250px">
		    </a>
		</div>
		<div id="login">
			<?php
				if($con)
				{
					echo 'Bienvenue à toi '.$_SESSION['login'].', <a href="../common/deco.php">se déconnecter</a><br>';
					
				}
			?>
		</div>	     
 	</div>
 	
    <div id="navigation">
	<?php
		require_once('modules/menuTop.php');
    ?>        
    </div>
	<div id="container">
		<div id="contenu">
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
                    <td  colspan="2" style="font-size: 16px; font-weight: bold; color: #fff;">
                        Informations du joueur
                    </td>
                </tr>
                <tr>
                    <td  colspan="2">
                        <div id="InfoJoueurEquipes" style="height:250px;">
                            
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
		
		
		
		
		</div>	
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>

</body>
</html>