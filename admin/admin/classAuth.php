<?php
require_once("connect.php");
class Auth {
    /***************************************************************************
     *      vérifie si l'utilisateur est connecté
     ***************************************************************************/
    static function isLogged() {
		
		if(isset($_SESSION['id_joueur']))
		{
			if(($_SESSION['id_joueur']!=0)) return true;
		}
 
        return false;
       
    }
    
    
    
    /***************************************************************************
     *      vérifie si l'utilisateur est autorisé à accéder à la page
     *      si l'utilisateur a level :
     *          1 = super-admin
     *          2 = admin
     *          5 = membre
     ***************************************************************************/
    static function isAllow($levelPage){
		if(isset($_SESSION['id_joueur']))
		{
			if(($_SESSION['level']<=$levelPage)) return true;
		}
 
        return false;
    }
}

?>