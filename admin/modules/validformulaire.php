
<?php


if (!empty($_POST)){
    $valid=true;
    $erreurNom='';
    $erreurPseudo='';
    $erreurPrenom='';
    $erreurEmail='';
    $erreurMDP='';
    $erreurNomTeam='';
    $erreurDate='';
    $erreurMDPteam='';
    $erreurCondition='';
    $erreurTel='';
    $erreurTag='';
    

    //pseudo
    if(empty($_POST['pseudo'])){
	$valid=false;
	$erreurPseudo="Vous n'avez pas rempli votre pseudo. \n";
    }
    else if(strlen($_POST['pseudo'])<2){
	$valid=false;
	$erreurPseudo="Votre pseudo doit comporter au moins 2 caractères \n";
    }
    else if(strlen($_POST['pseudo'])>40){
	$valid=false;
	$erreurPseudo="Votre pseudo est trop long \n";
    }
    
    //nom
    if(empty($_POST['firstname'])){
	$valid=false;
	$erreurNom="Vous n'avez pas rempli votre nom. \n";
    }
    else if ( !preg_match ( "/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ \-]{2,50}$/" , $_POST['firstname'] ) )
    {
	$valid=false;
	$erreurNom="Votre nom comporte des caractères non valides\n";
    }
    
    //prenom
    if(empty($_POST['lastname'])){
	$valid=false;
	$erreurPrenom="Vous n'avez pas rempli votre prénom. \n";
    }
    else if ( !preg_match ( "/^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ \-]{2,50}$/" , $_POST['firstname'] ) )
    {
	$valid=false;
	$erreurPrenom="Votre nom comporte des caractères non valides\n";
    }
    
    //email
    if(empty($_POST['email'])){
	$valid=false;
	$erreurEmail="Vous n'avez pas rempli votre email. \n";
    }
    else if(!preg_match("/^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$/i",$_POST['email'])){
	$valid=false;
	$erreurEmail="Votre email n'est pas valide. \n";
    }
    else if($_POST['email']!=$_POST['email2']){
	$valid=false;
	$erreurEmail="Les 2 emails ne sont pas les mêmes \n";
    }
    
    //date de naissance
    if(empty($_POST['date'])){
	$valid=false;
	$erreurDate="Vous n'avez pas rempli votre date de naissance. \n";
    }
    else if ( !preg_match ( "/^([0-3][0-9])(\/)([0-9]{2,2})(\/)((19)[0-9][0-9]|(200)[0-9])$/" , $_POST['date'] ) )
    {
	$valid=false;
	$erreurDate="Votre date de naissance n'est pas valide : \n";
	$erreurDate .=" \t \t elle doit être de la forme 00/00/0000 \n";
	
    }
    
    //password
    if(empty($_POST['password'])){
	$valid=false;
	$erreurMDP="Vous n'avez pas rempli de mot de passe. \n";
    }
    else if(strlen($_POST['password'])<8){
	$valid=false;
	$erreurMDP="Le mot de passe doit comporter au moins 8 caractères \n";
    }
    else if(strlen($_POST['password'])>30){
	$valid=false;
	$erreurMDP="Le mot de passe est trop long \n";
    }
    else if($_POST['password']!=$_POST['password2']){
	$valid=false;
	$erreurMDP="Les 2 mots de passe ne sont pas les mêmes. \n";
    }
    
    /***************************
     * Créer une team
     * ************************/
    
    if($_POST["Equipe"] == "creer"){
	
	//nom de la team
	if(empty($_POST['Team'])){
	    $valid=false;
	    $erreurNomTeam="Vous n'avez pas rempli le nom de votre team. \n";
	}
	else if(strlen($_POST['Team'])<2){
	    $valid=false;
	    $erreurNomTeam="Le nom de votre team doit comporter au moins 2 caractères \n";
	}
	else if(strlen($_POST['Team'])>40){
	    $valid=false;
	    $erreurNomTeam="Le nom de votre team est trop long \n";
	}
	
	//tag de la team
	if(empty($_POST['TagTeam'])){
	    $valid=false;
	    $erreurTag="Vous n'avez pas rempli le tag de votre team. \n";
	}
	else if(strlen($_POST['TagTeam'])<1){
	    $valid=false;
	    $erreurTag="Le tag de votre team doit comporter au moins 1 caractère \n";
	}
	else if(strlen($_POST['TagTeam'])>10){
	    $valid=false;
	    $erreurTag="Le tag de votre team est trop long \n";
	}
	
	//password de la team
	if(empty($_POST['new_psw_equipe'])){
	    $valid=false;
	    $erreurMDPteam="Vous n'avez pas rempli le mot de passe de la team. \n";
	}
	else if(strlen($_POST['new_psw_equipe'])<8){
	    $valid=false;
	    $erreurMDPteam="Le mot de passe de votre team doit comporter au moins 8 caractères \n";
	}
	else if(strlen($_POST['new_psw_equipe'])>30){
	    $valid=false;
	    $erreurMDPteam="Le mot de passe de votre team est trop long \n";
	}
	else if($_POST['new_psw_equipe']!=$_POST['new_psw_equipe2']){
	    $valid=false;
	    $erreurMDPteam="Les 2 mots de passe de votre team ne sont pas les mêmes. \n";
	}
    }
    
    /***************************
     * Rejoindre une team
     * ************************/
    
    if($_POST["Equipe"] == "rejoindre"){
	//nom de la team
	if(empty($_POST['nomequipe'])){
	    $valid=false;
	    $erreurNomTeam="Vous n'avez pas choisi le nom de votre team. \n";
	}
	//vérifie que l'utilisateur n'est pas introduit de valeur intrusive dans notre select
	else if ( !preg_match ( "/^[0-9]{1,10}$/" , $_POST['nomequipe'] ) )
	{
	    $valid=false;
	    $erreurNomTeam="Le nom de la team n'est pas valide \n";
	}
	
	//password de la team
	if(empty($_POST['psw_equipe'])){
	    $valid=false;
	    $erreurMDPteam="Vous n'avez pas rempli le mot de passe de la team. \n";
	}
	else if(strlen($_POST['psw_equipe'])<8){
	    $valid=false;
	    $erreurMDPteam="Le mot de passe de votre team doit comporter au moins 8 caractères \n";
	}
	else if(strlen($_POST['psw_equipe'])>30){
	    $valid=false;
	    $erreurMDPteam="Le mot de passe de votre team est trop long \n";
	}
	
    }
    //vérifie le telephone
    if(!empty($_POST['telephone'])){
	if(strlen($_POST['telephone'])>40){
	    $valid=false;
	    $erreurTel="Votre numéro de téléphone est trop long! \n";
	}
    }
    
    //vérifie si les conditions ont été accepté
    if($_POST["agree"] != "agree"){
	$valid=false;
	$erreurCondition="Vous n'avez accepté les règlements de la HeHLan \n";
	
    }
    
    
    if($valid){
	require_once("connect.php");
	$pseudo=trim($_POST["pseudo"]);
	$nom=$_POST["firstname"];
	$prenom=$_POST["lastname"];
	$date_de_naissance=$_POST["date"];
	$sexe=$_POST["sexe"];
	$gsm=$_POST["telephone"];
	$password=sha1($_POST["password"]);
	$email=$_POST["email"];
	if(!empty($_POST["tournois"])){
	    $tournois=$_POST["tournois"];
	}
	$id_equipes=0;
	$pseudoLOL=$_POST["pseudoLOL"];
	
	
	
	require_once("sauvegardejoueur.php");
	
	// regarder si l'utilisateur veut créer ou rejoindre une équipe
	if($_POST["Equipe"] == "creer")
	{
	    $mot_de_passe=sha1($_POST["new_psw_equipe"]);
	    $id_equipes = 0;
	    $nomteam = trim($_POST["Team"]);
	    $tag=$_POST["TagTeam"];
	    
	    require_once("sauvegardeequipe.php");
	    
	}
	if($_POST["Equipe"] == "rejoindre")
	{
	    $mot_de_passe = sha1($_POST["psw_equipe"]);
	    $id_equipes = $_POST["nomequipe"];
	    require_once("sauvegardeequipe.php");
	}
	
	
    }
    else{
	echo $erreurNom.$erreurPseudo.$erreurPrenom.$erreurEmail.$erreurMDP.$erreurDate.$erreurTel.$erreurNomTeam.$erreurTag.$erreurMDPteam.$erreurCondition;
	
    }
}
else{
    echo "aucune valeur n'a été envoyée";
}
?>
