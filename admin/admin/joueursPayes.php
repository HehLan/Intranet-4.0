<?php
//require_once("../modules/connect.php");
$sql="SELECT j.pseudo AS pseudo, j.id_joueur AS id, j.nom AS nom, j.prenom AS prenom
    FROM  joueurs j
    WHERE j.paye = 0
    ORDER BY j.pseudo
";
$req = $connexion->query($sql);
?>
<style type="text/css">
    div#JoueurPaye{
        width: 100%;
    }
    div#JoueurPaye legend{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
    }
    div#JoueurPaye fieldset{
        font-size: 30px;
        margin: 10px;
        font-weight: bold;
        padding-top: 20px;
        padding-bottom: 40px
    }
    div#JoueurPaye table{
        width: 95%;
        text-align: center;
        table-layout: fixed;
        margin: auto;
        text-align: center;
        margin-bottom: 20px;
    }
    div#JoueurPaye th,td{
        width: 50%;
        font-size: 25px;
    }
    div#JoueurPaye div{
        height: 500px;
        overflow: auto;
        border: 1px silver solid;
    }
    h6.ClasslisteJoueursNonPayes, h6.ClasslisteJoueursPayes {
        font-size: 20px;
        font-weight: normal;
        cursor: pointer;
    }
    div#JoueurPaye input[type='button']{
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
    
</style>
<script>
    $(document).ready(function() {
        $(".ClasslisteJoueursNonPayes").click(function(){
            
            var ligneAjout = '<h6 class="ClasslisteJoueursPayes" value="'
                +$(this).attr("value")+'">'
                +$(this).text()+'</h6>';
            $(this).hide();
            $("#listeValidePayement").append(ligneAjout);
        });
        
        $("div").delegate(".ClasslisteJoueursPayes", "click", function(){
            $(".ClasslisteJoueursNonPayes[value="+$(this).attr("value")+"]").show();
            $(this).remove();
        });
        
        
        
        $("#submitValiderPayementJoueur").click(function(){
            var erreurSurvenue=false;
            $( ".ClasslisteJoueursPayes" ).each(function(){
                
                var id = $(this).attr("value");
                $.ajax({ 
                    type: "POST", 
                    url: "admin/validPayement.php",
                    data: "paye="+id,
                    success : function(contenu,etat){ 
                        if (contenu=="true") {    
                            $("h6[value='"+id+"']").remove();
                        }
                        else {
                            alert(contenu);
                            erreurSurvenue=true;
                        }
                    }
                });
            });
            if (!erreurSurvenue) {
                alert('Les modifications sont terminées et se sont correctement passées!');
            }
            else{
                alert('Les modifications sont terminées mais des erreurs sont survenues!');
            }
            
        });
        
        
    });
</script>
<div id="JoueurPaye">
    <fieldset>
        <legend>Valider le payement d'un joueur</legend>
        <table>
            <thead>
                <tr>
                    <th>Joueurs non payés</th>
                    <th>Joueurs payés</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div id="listeJoueursNonPayes">
                        <?php
                        try {
                            
                            while($joueur = $req->fetch()){
                               
                                if (!empty($joueur)){
                                    
                                    echo'
                                        <h6 class="ClasslisteJoueursNonPayes" value="'.$joueur['id'].'">'.$joueur['pseudo'].'  ('.$joueur['nom'].' '.$joueur['prenom'].')</h6>
                                    ';
                                    
                                }else echo "La liste des joueurs inscrits n'est pas encore disponible!";
                                
                            }
                        }
                        
                        catch(PDOException $e) {
                            echo 'Base de données est indisponible pour le moment!';
                        }
                            
                        ?>
                        </div>
                    </td>
                    <td >
                        <div id="listeValidePayement">
                             
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="button" id="submitValiderPayementJoueur" value="Valider le payement des joueurs">
    </fieldset>
    
</div>

