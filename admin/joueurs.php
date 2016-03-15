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
$query="SELECT id_joueur, pseudo FROM joueurs ORDER BY pseudo";
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
    <link rel="stylesheet" href="css/joueurs.css" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/getXhr.js"></script>

<script>
    $(document).ready(function() {
        
        $( ".joueurAdmin" ).click(function() {
            
            $(".joueurAdmin").css({background: "none"});
            $( this ).css({background: "rgba(0,0,255,0.2)"});
            $.ajax({ 
                type: "POST", 
                url: "admin/listeDesTournoisDuJoueur.php",
                data: "id_joueur="+$(this).attr("value"),
                success : function(contenu,etat){ 
                    $('#listeTournoisInscritDuJoueur').html(contenu);
                }
            });
            $.ajax({ 
                type: "POST", 
                url: "admin/equipesDuJoueur.php",
                data: "id_joueur="+$(this).attr("value"),
                success : function(contenu,etat){ 
                    $('#EquipesDuJoueurAdmin').html(contenu);
                }
            });
            
        });
        
        $( "#infoEquipeAdmin" ).dialog({
            autoOpen: false,
            title:"information",
            height: 300,
            width: 350,
            modal: true,
            close: function() {
                
            }
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
        
        
        $("div").delegate("#submitChgtTournoijoueurAdmin", "click", function(){
            var erreurSurvenue=false;
            var i=0;
            
            var id ='id_joueur='+$("#idJoueurAdmin").attr("value");
            $( ".chkbxJoueurTournoi:checked" ).each(function(){
                id +='&inscrit['+i+'][1]='+$(this).attr("value");
                id +='&inscrit['+i+'][2]='+$("#txtbxJoueurTournoi"+$(this).attr("value")).val();
                i++;
                
            });
            
            $.ajax({ 
                type: "POST", 
                url: "admin/insertTournoiJoueur.php",
                data: id,
                success : function(contenu,etat){ 
                    $( "#infoEquipeAdmin" ).html(contenu);
                    $( "#infoEquipeAdmin" ).dialog({ buttons: [ { text: "Ok", click: function() { $( this ).dialog( "close" ); location.reload(); } } ] });
                    $( "#infoEquipeAdmin" ).dialog( "open" );
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
					echo 'Bienvenu à toi '.$_SESSION['login'].', <a href="../common/deco.php">se déconnecter</a><br>';
					
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
<div id="ListejoueurAdmin">
    <fieldset>
        <legend>Liste des joueurs</legend>
        <table class="listeJoueurs">
            <thead>
                <tr>
                    <th>Les Joueurs</th>
                    <th>Tournois</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div id="listejoueurAdmin" >
                        <?php
                        try {
                            
                            while($joueur=$requete_preparee->fetch(PDO::FETCH_ASSOC)) 
                            {
                                echo'
                                    <h6 class="joueurAdmin" value="'.$joueur["id_joueur"].'">'.$joueur["pseudo"].'</h6>
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
                        <div id="listeTournoisInscritDuJoueur">
                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <td  colspan="2" style="font-size: 16px; font-weight: bold; color: #fff;">
                        Equipes du joueur
                    </td>
                </tr>
                <tr>
                    <td  colspan="2">
                        <div id="EquipesDuJoueurAdmin" style="height:150px;">
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
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