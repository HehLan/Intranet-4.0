
<div id="infoZoneConnexion" >
<?php
require_once('modules/connexion/classAuth.php');
    if (Auth::isLogged()){
        echo 'Vous êtes connecté en tant que : <strong>'.$_SESSION["login"].'.</strong>  <a href="modules/connexion/logout.php">Déconnexion</a>';
    }
    else{
        header('Location: index.php');
    }
?>
</div>
    
    <div id="formConnexion" title="Connexion">
        
        <div id="erreurLogin" >
            
        </div>
        <br >
        <label>Pseudo : <br >
        <input type="text" id="ConPseudo" /></label><br ><br > 
        <label>Mot de passe : <br >
        <input type="password" id="ConPwd" /></label><br ><br >
        
        <span id="afficheMDPoublie"> mot de passe oublié? </span><br ><br >
        <input type="button" id="validConnexion" value="Connexion" style="float: right;"/>
        <div id="repCon"></div>
    </div>
    
    <div id="formMDPoublie" title="Mot de passe oublié">
        <br >
        <div id="erreurMDPoublie" >
            
        </div>
        <div id="afficheFormMDPoublie">
            <br >
            <label>Pseudo : <br >
            <input type="text" id="pseudoOublie" /></label><br ><br > 
            <label>Email : <br >
            <input type="text" id="emailOublie" /></label><br ><br >
            <input type="text" name="adresse" id="adresse" style="display: none;"/>
            <input type="button" id="submitMDPoublie" value="Valider" style="float: right;"/>
        </div>
    </div>
