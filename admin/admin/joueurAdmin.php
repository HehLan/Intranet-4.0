<?php
require_once("connect.php");

$query="SELECT id_joueur, pseudo FROM joueurs ORDER BY pseudo";
$requete_preparee=$connexion->prepare($query);
$requete_preparee->execute();

?>
<style type="text/css">
    div#ListejoueurAdmin legend{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
    }
    div#ListejoueurAdmin fieldset{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
        padding-top: 20px;
        padding-bottom: 40px
    }
    table.listeJoueurs {
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
    
    .listeJoueurs td, th {
        border: 1px solid #CCC;
        width: 50%;
    } 
    
    .listeJoueurs th {
        background: none;
        font-weight: bold;
        font-size: 30px;
    }
    
    .listeJoueurs td {
        background: none; 
        text-align: center; 
    }
    div#ListejoueurAdmin div{
        height: 300px;
        overflow: auto;
        border: 1px silver solid;
    }
    h6.joueurAdmin, h6.EquipeJoueurAdmin {
        font-size: 20px;
        font-weight: normal;
        cursor: pointer;
    }
    div#ListejoueurAdmin input[type='button']{
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
    div#ListejoueurAdmin div#InfoJoueurEquipes {
        font: normal 18px Calibri, Arial, sans-serif;
        margin: 0;
        padding: 0;
        padding-top: 20px;
        padding-bottom: 20px;
        text-align: center;
        height: 350px;
    }
    
    
</style>
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
                        <div id="listejoueurAdmin">
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
                    <td  colspan="2" style="font-size: 30px; font-weight: bold; color: #fff;">
                        Equipes du joueur
                    </td>
                </tr>
                <tr>
                    <td  colspan="2">
                        <div id="EquipesDuJoueurAdmin">
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </fieldset>
    <div id="infoEquipeAdmin" style="display: none"></div>
</div>




























