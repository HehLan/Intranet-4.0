$(document).ready(function() {
	
	// Slider Jeux
	$('#slider').rhinoslider({
		effect: 'chewyBars',
		effectTime: 2500,
		easing: 'easeOutCirc',
		shiftValue: '25',
		autoPlay: true,
		showTime: 5000,
		controlsPlayPause: false,
		parts: '10'
	});

	// Sponsors slider 
	$('#sponsorSlide').rhinoslider({
			showTime: 4000,
			effectTime: 2000,
			controlsMousewheel: false,
			controlsPrevNext: false,
			controlsPlayPause: false,
			autoPlay: true,
			showBullets: 'never',
			showControls: 'never',
			slidePrevDirection: 'toTop',
			slideNextDirection: 'toBottom'
	});

    var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    var animation = {
        easing: "easeOutBounce",
        duration: 2000
    };
    $( "#accordionInfo" ).accordion({
      icons: icons,
      collapsible: true,
      animate: animation,
      active : null,
      heightStyle: "content"
    });
    $('a.media').media({width:500, height:400});
    $( "#tabsReglement" ).tabs();

            //si besoin de détails pour changer les options, il faut se rendre sur :    http://fancyapps.com/fancybox/#news
    $(".fancybox").fancybox({ 
        closeEffect : 'fade', 
        openEffect : 'elastic',
        prevEffect : 'fade', 
        nextEffect : 'fade', 
        prevSpeed : '1000',
        nextSpeed : '1000',
        helpers : {
            media : {},
            thumbs : {
                width: 120,
                height: 90
            }
		}
    });
    $('.fancybox-media').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
		media : {}
	}                
   	});

    /**********************************************
     * 		Contact
     **********************************************/
    
    $('#adresse').hide();        
        
    $('form#formContact').on('submit',function(e){
	e.preventDefault(); //on empêche le formulaire de s'envoyer
	var erreur='';
	var valid = true;
	if (!$('#nom').val()) {
	    valid = false;
	    $("#nom").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre nom <br \>";
	}
	if (!$('#prenom').val()) {
	    valid = false;
	    $("#prenom").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre prénom <br \>";
	}
	var reg = new RegExp("^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$","i");
	if (!$('#email').val()) {
	    valid = false;
	    $("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre email <br \>";
	}else if (!reg.test($('#email').val())) {
	    valid = false;
	    $("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Votre e-mail est incorrect! <br \>";
	}
	if (!$('#message').val()) {
	    valid = false;
	    $("#message").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas écrit de message<br \>";
	}
	
	if (valid) {
	    $("#reponseContact").html("Envoie en cours...");
	    $("#reponseContact").css({color: "#00f"});
	    $("#formContact").hide();
	    $.ajax({ 
		type: "POST", 
		url: "modules/contactEnvoie.php",
		data:$(this).serialize(),
		success : function(contenu,etat){ 
		
		    $("#reponseContact").html(contenu);
		    $("#formContact").show();
		}
	    
	    });
	}
	else {
	    $("#reponseContact").html(erreur);
	    $("#reponseContact").css({color: "#f00"});
	}
    });
    
    /**********************************************
     * 		Jeux
     **********************************************/
	
	$( "#tabsGames").tabs();
        $( "#accorLOL").accordion({
            icons: icons,
            animate: animation,
            heightStyle: "content"
          });
        $( "#accorCOD").accordion({
            icons: icons,
            animate: animation,
            heightStyle: "content"
          });
        $( "#accorUT").accordion({
            icons: icons,
            animate: animation,
            heightStyle: "content"
          });
        $( "#accorTNM").accordion({
            icons: icons,
            animate: animation,
            heightStyle: "content"
          });
    
    
        /**********************************************
     * 		inscription
     **********************************************/
    $( "#datepicker" ).datepicker({
	dateFormat: "dd/mm/yy",
	yearRange: "1950:2000",
	defaultDate: "01/01/1990",
	changeMonth: true,
	changeYear: true,
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	monthNamesShort: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ]
    });
    
    $('.sexe').click(function()
    {
	    if($('#radio_femme').is(':checked'))
		    $('#div_celibataire').show();
	    else	
		    $('#div_celibataire').hide();
    });
    $('#wait').click(function()
    {
	$('#rejoindreTeam').hide();
	$('#creerTeam').hide();
    });
    $('#rejoindre').click(function()
    {
	$('#rejoindreTeam').show();
	$('#creerTeam').hide();
    });
    $('#creer').click(function()
    {
	$('#rejoindreTeam').hide();
	$('#creerTeam').show();
    });
    $('#LOL').click(function()
    {
	    if($('#LOL').is(':checked'))
		    $('#pseudoLOL').show();
	    else	
		    $('#pseudoLOL').hide();
    });
    
    $('form#formID').on('submit',function(e){
	
	e.preventDefault(); //on empêche le formulaire de s'envoyer
	
	//remise en forme des inputs
	$("#psw_equipe").css({background: "none"});
	$("#new_psw_equipe").css({background: "none"});
	$("#new_psw_equipe2").css({background: "none"});
	$("#Team").css({background: "none"});
	$("#password").css({background: "none"});
	$("#password2").css({background: "none"});
	$("#datepicker").css({background: "none"});
	$("#email").css({background: "none"});
	$("#email2").css({background: "none"});
	$("#lastname").css({background: "none"});
	$("#firstname").css({background: "none"});
	$("#pseudo").css({background: "none"});
	$("#recaptcha_response_field").css({backgroundColor: "none"});
	$("#telephone").css({backgroundColor: "none"});
	$("#TagTeam").css({backgroundColor: "none"});
	$("#VerifPseudoLOL").css({background: "none"});

	
	var erreur='';
	var valid = true;
	var regPseudo = new RegExp("^[a-zA-Z0-9_]{3,30}$","i");
	var regNomPrenom = new RegExp("^[a-zA-ZàáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ \-]{2,50}$","i");
	var regEmail = new RegExp("^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$","i");
	var regDate = new RegExp("^([0-3][0-9])(\/)([0-9]{2,2})(\/)((19)[0-9][0-9]|(200)[0-9])$");
	
	//vérifie le captcha
	$.ajax({ 
	    type: "POST", 
	    url: "modules/verifCaptcha.php",
	    data:"recaptcha_response_field="+$('#recaptcha_response_field').val()+"&recaptcha_challenge_field="+$('#recaptcha_challenge_field').val(),
	    async: false,
	    success : function(contenu,etat){ 
		if(contenu!='true'){
		    valid = false;
		    $("#recaptcha_response_field").css({backgroundColor: "rgba(200, 0, 0, 0.6)"});
		    erreur += "Le captcha n'est pas valide! <br \>";
		    Recaptcha.reload();
		}
	    }
	
	});
	
	//pseudo
	if (!$('#pseudo').val()) {
	    valid = false;
	    $("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre pseudo <br \>";
	}
	else if ($('#pseudo').val().length<2) {
	    valid = false;
	    $("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Votre pseudo doit comporter au moins 2 caractères <br \>";
	}else if ($('#pseudo').val().length>40) {
	    valid = false;
	    $("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Votre pseudo est trop long<br \>";
	}
	else if ($('#pseudobox').css('color')!='rgb(0, 255, 0)') {
	    valid = false;
	    $("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur +="Ce pseudo est déjà pris!<br \>";
	}
	
	//nom
	if (!$('#firstname').val()) {
	    valid = false;
	    $("#firstname").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre nom <br \>";
	}
	else if (!regNomPrenom.test($('#firstname').val())) {
	    valid = false;
	    $("#firstname").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur +="Votre nom comporte des caractères non valides<br \>";
	}
	
	//prenom
	if (!$('#lastname').val()) {
	    valid = false;
	    $("#lastname").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre prénom <br \>";
	}
	else if (!regNomPrenom.test($('#lastname').val())) {
	    valid = false;
	    $("#lastname").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur +="Votre prénom comporte des caractères non valides<br \>";
	}
	
	//email
	if (!$('#email').val()) {
	    valid = false;
	    $("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre email <br \>";
	}else if (!regEmail.test($('#email').val())) {
	    valid = false;
	    $("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Votre e-mail est incorrect! <br \>";
	}else if ($('#email').val()!=$('#email2').val()){
	    valid = false;
	    $("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    $("#email2").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Les 2 emails ne sont pas les mêmes <br \>";
	}
	
	//date de naissance
	if (!$('#datepicker').val()) {
	    valid = false;
	    $("#datepicker").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre date de naissance <br \>";
	}
	else if (!regDate.test($('#datepicker').val())) {
	    valid = false;
	    $("#datepicker").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur +="Votre date de naissance n'est pas valide :<br \>";
	    erreur +="<span style='margin-left:20px;'>elle doit être de la forme 00/00/0000</span><br \>";
	}
	
	//password
	if (!$('#password').val()) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre mot de passe <br \>";
	}else if ($('#password').val().length<8) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Le mot de passe doit comporter au moins 8 caractères <br \>";
	}else if ($('#password').val().length>30) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Le mot de passe est trop long<br \>";
	}else if ($('#password').val()!=$('#password2').val()){
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    $("#password2").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Les 2 mots de passe ne sont pas les mêmes <br \>";
	}
	
	/***************************
	* Verif PseudoLOL
	* ************************/
	
	if($('#LOL').is(':checked') ){
		
	    if (!$('#VerifPseudoLOL').val()) {
		valid = false;
		$("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Vous n'avez pas rempli votre pseudo LOL <br \>";
	    }
	    else if ($('#VerifPseudoLOL').val().length<2) {
		valid = false;
		$("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre pseudo LOL doit comporter au moins 2 caractères <br \>";
	    }else if ($('#VerifPseudoLOL').val().length>40) {
		valid = false;
		$("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre pseudo LOL est trop long<br \>";
	    }
	    else if ($('#pseudoboxLOL').css('color')!='rgb(0, 255, 0)') {
		valid = false;
		$("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur +="Le pseudoLOL existe déjà!<br \>";
	    }
	}
	
	
	/***************************
	* Créer une team
	* ************************/
	
	if($('#creer').is(':checked') ){
	    
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
	}
	
	/***************************
	* Rejoindre une team
	* ************************/
	if($('#rejoindre').is(':checked') ){
	    
	    //nom de la team
	    if (!$("#rejoindre_Team option:selected").val()) {
		valid = false;
		erreur += "Vous n'avez pas sélectionné le nom de votre team <br \>";
	    }
	     else if ($('#rejoindre_Team option:selected').text().length<2) {
		valid = false;
		erreur += "Le nom de la team n'est pas valide<br \>";
	    }else if ($('#rejoindre_Team option:selected').text().length>40) {
		valid = false;
		erreur += "Le nom de la team n'est pas valide<br \>";
	    }
	    
	    
	    //password de la team
	    if (!$('#psw_equipe').val()) {
		valid = false;
		$("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Vous n'avez pas rempli le mot de passe de la team <br \>";
	    }else if ($('#psw_equipe').val().length<8) {
		valid = false;
		$("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Mauvais mot de passe pour rejoindre la team <br \>";
	    }else if ($('#psw_equipe').val().length>30) {
		valid = false;
		$("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Mauvais mot de passe pour rejoindre la team<br \>";
	    }
	}
	
	//vérifie num tel
	if ($('#telephone').val()!="") {
	    if ($('#telephone').val().length>30) {
		valid = false;
		$("#telephone").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre numéro de téléphone est trop long<br \>";
	    }
	}
	//vérifie qu'il a bien accepté les conditions
	if (!$('#agree').is(':checked')) {
	    valid = false;
	    erreur += "Vous n'avez pas accepté les règlements de la HeHLan <br \>";
	}

	if (valid) {
	    $("#erreurInsciption").html("Vérification en cours...");
	    $("#erreurInsciption").css({color: "#00f"});
	    
	    $.ajax({ 
		type: "POST", 
		url: "modules/validformulaire.php",
		data:$(this).serialize(),
		success : function(contenu,etat){ 
		    $("#formInscription").html(contenu);
		}
	    
	    });
	}
	else {
	    $("#erreurInsciption").html(erreur);
	    $("#erreurInsciption").css({color: "#f00"});
	}
    });
    
    $('#formInscription #pseudo').on('change', function() {
	$.ajax({ 
	    type: "POST", 
	    url: "modules/check-pseudo.php",
	    data:"pseudo="+$('#pseudo').val(),
	    success : function(contenu,etat){ 
		$("#pseudobox").html(contenu);
	    }
	
	});
    });
    $('#formInscription #VerifPseudoLOL').on('change', function() {
	
	$.ajax({ 
	    type: "POST", 
	    url: "modules/check-pseudo.php",
	    data:"pseudoLOL="+$('#VerifPseudoLOL').val(),
	    success : function(contenu,etat){ 
		$("#pseudoboxLOL").html(contenu);
	    }
	
	});
    });
    $('#formInscription #Team').on('change', function() {
	$.ajax({ 
	    type: "POST", 
	    url: "modules/check-pseudo.php",
	    data:"Team="+$('#Team').val(),
	    success : function(contenu,etat){ 
		$("#pseudoboxTeam").html(contenu);
	    }
	
	});
    });
    $('#formInscription #TagTeam').on('change', function() {
	$.ajax({ 
	    type: "POST", 
	    url: "modules/check-pseudo.php",
	    data:"TagTeam="+$('#TagTeam').val(),
	    success : function(contenu,etat){ 
		$("#pseudoboxTagTeam").html(contenu);
	    }
	
	});
    });
    $('#agreeReglement').click(function()
    {
	$('#tabsReglement').toggle();	
    });
    
    
    /*************************************
     *          connexion                *
     ************************************/
    
    $('#validConnexion').click(function(){
	var pseudo = $('#ConPseudo').val();
	var pwd = $('#ConPwd').val();
	$.ajax({ 
	    type: "POST", 
	    url: "modules/connexion/login.php",
	    data:"pseudo="+pseudo+"&pwd="+pwd,
	    async: false,
	    success : function(contenu,etat){ 
		$( "#repCon" ).html(contenu);
	    }
	
	});
    });
    
    
    $( "#formConnexion" ).dialog({
	autoOpen: false,
	resizable: false,
	draggable: true,
	width:250,
	position:{my: "right top", at: "right top", of: $('#openConnexion')},
	show: {
	    effect: "blind",
	    duration: 1000
	},
	hide: {
	    effect: "blind",
	    duration: 1000
	}
    });
    
    $( "#openConnexion" ).click(function() {
	$( "#formConnexion" ).dialog( "open" );
    });
    
    /*****************************************
     *		       profil
     ****************************************/
    $( "#afficheChgtMDP" ).click(function() {
	$( "#ModifMDP" ).show();
	$( "#afficheChgtMDP" ).hide();
    });
    
    $( "#newTeam" ).click(function() {
	$( "#creerTeam" ).show();
	$( "#creerTeam input[type='text']" ).css({background: "none"});
	$( "#newTeam" ).hide();
	$( "#rejoindreUneTeam" ).hide();
	
    });
    $( "#rejoindreUneTeam" ).click(function() {
	$( "#rejoindreTeam" ).show();
	$( "#rejoindreTeam input[type='text']" ).css({background: "none"});
	$( "#rejoindreUneTeam" ).hide();
	$( "#newTeam" ).hide();
	
    });
    $( "#RetourTeam2" ).click(function() {
	$( "#creerTeam" ).hide();
	$( "#rejoindreUneTeam" ).show();
	$( "#newTeam" ).show();
    });
    
    $( "#RetourTeam" ).click(function() {
	$( "#rejoindreTeam" ).hide();
	$( "#rejoindreUneTeam" ).show();
	$( "#newTeam" ).show();
	$( "#rejoindreAutreTeam" ).show();
	$( "#quitterTeam" ).show();
    });
    
    $( "#rejoindreAutreTeam" ).click(function() {
	$( "#rejoindreTeam" ).show();
	$( "#rejoindreTeam input[type='text']" ).css({background: "none"});
	$( "#rejoindreAutreTeam" ).hide();
	$( "#quitterTeam" ).hide();
	
    });
    
    $('#quitterTeam').click(function(){
	
	if (confirm("Etes-vous sûr de vouloir quitter la team "+$('#votreTeam').html()+" ?")) {
            $.ajax({ 
		type: "POST", 
		url: "modules/profil/exitTeam.php",
		success : function(contenu,etat){ 
		    $( "#dialogMessage" ).html(contenu);
		    
		    $( "#dialogMessage" ).dialog({
			modal: true,
			title: "Information",
			close: function( event, ui ) {
			    location.reload();
			},
			buttons: {
			  Ok: function() {
			    $( this ).dialog( "close" );
			  }
			}
		    });
		    
		}
	
	    });
	}
    	
    });
    
    /******************************************
     *	  ModifProfil Créer une new equipe
     ******************************************/
    $('#submitNewTeam').click(function(){
	
	$("#Team").css({background: "none"});
	$("#TagTeam").css({background: "none"});
	$("#new_psw_equipe").css({background: "none"});
	$("#new_psw_equipe2").css({background: "none"});
	
	var valid=true;
	var erreur='';
	
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
	    $("#infoNewTeam").html("Vérification en cours...");
	    $("#infoNewTeam").css({color: "#00f"});
	    
	    var donnee = "Team="+$('#Team').val()
		+"&TagTeam="+$('#TagTeam').val()
		+"&new_psw_equipe="+$("#new_psw_equipe").val()
		+"&new_psw_equipe2="+$("#new_psw_equipe2").val();
	    
	    $.ajax({ 
		type: "POST", 
		url: "modules/profil/modifNewTeam.php",
		data: donnee,
		success : function(contenu,etat){ 
		    $( "#dialogMessage" ).html(contenu);
		    
		    $( "#dialogMessage" ).dialog({
			modal: true,
			title: "Information",
			close: function( event, ui ) {
			    location.reload();
			},
			buttons: {
			  Ok: function() {
			    $( this ).dialog( "close" );
			  }
			}
		    });
		}
	    
	    });
	}
	else {
	    $("#infoNewTeam").html(erreur);
	    $("#infoNewTeam").css({color: "#f00"});
	}
	    
    });
    
    $('#submitRejoindreTeam').click(function(){
	
	$("#psw_equipe").css({background: "none"});
	
	
	var valid=true;
	var erreur='';
	
	/***************************
	* Rejoindre une team
	* ************************/
	
	    
	//nom de la team
	if (!$("#rejoindre_Team option:selected").val()) {
	    valid = false;
	    erreur += "Vous n'avez pas sélectionné le nom de votre team <br \>";
	}
	 else if ($('#rejoindre_Team option:selected').text().length<2) {
	    valid = false;
	    erreur += "Le nom de la team n'est pas valide<br \>";
	}else if ($('#rejoindre_Team option:selected').text().length>40) {
	    valid = false;
	    erreur += "Le nom de la team n'est pas valide<br \>";
	}
	
	
	//password de la team
	if (!$('#psw_equipe').val()) {
	    valid = false;
	    $("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli le mot de passe de la team <br \>";
	}else if ($('#psw_equipe').val().length<8) {
	    valid = false;
	    $("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Mauvais mot de passe pour rejoindre la team <br \>";
	}else if ($('#psw_equipe').val().length>30) {
	    valid = false;
	    $("#psw_equipe").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Mauvais mot de passe pour rejoindre la team<br \>";
	}
	
	if (valid) {
	    $("#infoJoinTeam").html("Vérification en cours...");
	    $("#infoJoinTeam").css({color: "#00f"});
	    
	    var donnee = "nomequipe="+$('#rejoindre_Team').val()
		+"&psw_equipe="+$("#psw_equipe").val();
	    
	    $.ajax({ 
		type: "POST", 
		url: "modules/profil/joinTeam.php",
		data: donnee,
		success : function(contenu,etat){ 
		    $( "#dialogMessage" ).html(contenu);
		    
		    $( "#dialogMessage" ).dialog({
			modal: true,
			title: "Information",
			close: function( event, ui ) {
			    location.reload();
			},
			buttons: {
			  Ok: function() {
			    $( this ).dialog( "close" );
			  }
			}
		    });
		}
	    
	    });
	}
	else {
	    $("#infoJoinTeam").html(erreur);
	    $("#infoJoinTeam").css({color: "#f00"});
	}
    });
    
    
    $('#submitChgtMDP').click(function(){
	
	$("#password").css({background: "none"});
	$("#password2").css({background: "none"});
	
	var valid=true;
	var erreur='';
	
	//password
	if (!$('#password').val()) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Vous n'avez pas rempli votre mot de passe <br \>";
	}else if ($('#password').val().length<8) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Le mot de passe doit comporter au moins 8 caractères <br \>";
	}else if ($('#password').val().length>30) {
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Le mot de passe est trop long<br \>";
	}else if ($('#password').val()!=$('#password2').val()){
	    valid = false;
	    $("#password").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    $("#password2").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
	    erreur += "Les 2 mots de passe ne sont pas les mêmes <br \>";
	}
	
	
	if (valid) {
	    $("#infoChgtMDP").html("Vérification en cours...");
	    $("#infoChgtMDP").css({color: "#00f"});
	    
	    var donnee = "password="+$('#password').val()
		+"&password2="+$("#password2").val();
	    
	    $.ajax({ 
		type: "POST", 
		url: "modules/profil/modifMDP.php",
		data: donnee,
		success : function(contenu,etat){ 
		    $( "#dialogMessage" ).html(contenu);
		    
		    $( "#dialogMessage" ).dialog({
			modal: true,
			title: "Information",
			close: function( event, ui ) {
			    location.reload();
			},
			buttons: {
			  Ok: function() {
			    $( this ).dialog( "close" );
			  }
			}
		    });
		}
	    
	    });
	}
	else {
	    $("#infoChgtMDP").html(erreur);
	    $("#infoChgtMDP").css({color: "#f00"});
	}
	
    });
    
    $('#afficheModifProfil').click(function(){
	
	    var erreur='';
	    var valid = true;
	    var regEmail = new RegExp("^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$","i");
	    
	if ($( "#afficheModifProfil" ).val()=="Valider les modifications") {
	    
	    //remise en forme des inputs
	    $("#email").css({background: "none"});
	    $("#email2").css({background: "none"});
	    $("#pseudo").css({background: "none"});
	    $("#telephone").css({backgroundColor: "none"});
	    $("#VerifPseudoLOL").css({background: "none"});
	    
	    //pseudo
	    if (!$('#pseudo').val()) {
		valid = false;
		$("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Vous n'avez pas rempli votre pseudo <br \>";
	    }
	    else if ($('#pseudo').val().length<2) {
		valid = false;
		$("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre pseudo doit comporter au moins 2 caractères <br \>";
	    }else if ($('#pseudo').val().length>40) {
		valid = false;
		$("#pseudo").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre pseudo est trop long<br \>";
	    }
	    
	    
	    //email
	    if (!$('#email').val()) {
		valid = false;
		$("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Vous n'avez pas rempli votre email <br \>";
	    }else if (!regEmail.test($('#email').val())) {
		valid = false;
		$("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Votre e-mail est incorrect! <br \>";
	    }else if ($('#email').val()!=$('#email2').val()){
		valid = false;
		$("#email").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		$("#email2").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		erreur += "Les 2 emails ne sont pas les mêmes <br \>";
	    }
	    
	    //vérifie num tel
	    if ($('#telephone').val()!="") {
		if ($('#telephone').val().length>30) {
		    valid = false;
		    $("#telephone").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		    erreur += "Votre numéro de téléphone est trop long<br \>";
		}
	    }
	    
	    /***************************
	    * Verif PseudoLOL
	    * ************************/
	    
	    if($('#LOL').is(':checked') ){
		    
		if (!$('#VerifPseudoLOL').val()) {
		    valid = false;
		    $("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		    erreur += "Vous n'avez pas rempli votre pseudo LOL <br \>";
		}
		else if ($('#VerifPseudoLOL').val().length<2) {
		    valid = false;
		    $("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		    erreur += "Votre pseudo LOL doit comporter au moins 2 caractères <br \>";
		}else if ($('#VerifPseudoLOL').val().length>40) {
		    valid = false;
		    $("#VerifPseudoLOL").css({backgroundColor: "rgba(200, 0, 0, 0.2)"});
		    erreur += "Votre pseudo LOL est trop long<br \>";
		}
		
	    }
	    
	    
	    
	    
	    
	    if (valid) {
		
		$("#infoModifProfil").html("Vérification en cours...");
		$("#infoModifProfil").css({color: "#00f"});
		var i =0;
		var donnee = "email="+$('#email').val()
		    +"&email2="+$("#email2").val()
		    +"&telephone="+$("#telephone").val()
		    +"&pseudo="+$("#pseudo").val()
		    +"&pseudoLOL="+$("#VerifPseudoLOL").val();
		
		$(".jeux:checked").each(function() {
		    
		  donnee+="&tournois["+i+"]="+$(this).val();
		  i++;
		});
		
		
		
		$.ajax({ 
		    type: "POST", 
		    url: "modules/profil/modifProfil.php",
		    data: donnee,
		    success : function(contenu,etat){ 
			$( "#dialogMessage" ).html(contenu);
			
			$( "#dialogMessage" ).dialog({
			    modal: true,
			    title: "Information",
			    close: function( event, ui ) {
				location.reload();
			    },
			    buttons: {
			      Ok: function() {
				$( this ).dialog( "close" );
			      }
			    }
			});
		    }
		
		});
	    }
	    else {
		$("#infoModifProfil").html(erreur);
		$("#infoModifProfil").css({color: "#f00"});
	    }
	}else{
	    
	    $( "#pseudo" ).removeAttr( "readonly" );
	    $( "#pseudo" ).css({background: "none"});
	    $( "#telephone" ).removeAttr( "readonly" );
	    $( "#telephone" ).css({background: "none"});
	    $( "#email" ).removeAttr( "readonly" );
	    $( "#email" ).css({background: "none"});
	    $( "#email2" ).css({background: "none"});
	    $( "#LOL" ).removeAttr( "disabled" );
	    $( "#COD4" ).removeAttr( "disabled" );
	    $( "#TM" ).removeAttr( "disabled" );
	    $( "#UT3" ).removeAttr( "disabled" );
	    $( "#VerifPseudoLOL" ).removeAttr( "readonly" );
	    $( "#VerifPseudoLOL" ).css({background: "none"});
	    
	    $( "#ModifEmail" ).show();
	    $( "#afficheModifProfil" ).val("Valider les modifications");
	    
	}
	
	
    });
    
    $( "#formMDPoublie" ).dialog({
	autoOpen: false,
	resizable: false,
	draggable: true,
	width:250,
	position:{my: "right top", at: "right top", of: $('#openConnexion')}
	
    });
    
    
    $('#afficheMDPoublie').click(function(){
	$( "#afficheFormMDPoublie" ).show();
	$("#erreurMDPoublie").hide();
	$( "#formConnexion" ).dialog( "close" );
	$( "#formMDPoublie" ).dialog( "open" );
    });
    
    
    $('#submitMDPoublie').click(function(){
	
	var erreur='';
	var valid = true;
	var regEmail = new RegExp("^[a-z0-9\-_.]+@[a-z0-9\-_.]+\.[a-z0-9\-_.]{2,3}$","i");
	var pseudo = $('#pseudoOublie').val();
	var emailOublie = $('#emailOublie').val();
	
	
	if (!$('#adresse').val()=="") {
	    valid = false;
	}
	
	
	//pseudo
	if (!$('#pseudoOublie').val()) {
	    valid = false;
	    
	    erreur += "Vous n'avez pas rempli votre pseudo <br \>";
	}
	
	
	//email
	if (!$('#emailOublie').val()) {
	    valid = false;
	    
	    erreur += "Vous n'avez pas rempli votre email <br \>";
	}else if (!regEmail.test($('#emailOublie').val())) {
	    valid = false;
	    
	    erreur += "Votre e-mail est incorrect! <br \>";
	}
	
	if (valid) {
	    $("#erreurMDPoublie").html("Vérification en cours...");
	    $("#erreurMDPoublie").css({color: "#00f"});
	    $( "#erreurMDPoublie" ).show();
	    
	    $.ajax({ 
		type: "POST", 
		url: "modules/connexion/oublieMDP.php",
		data:"pseudoOublie="+pseudo+"&emailOublie="+emailOublie,
		success : function(contenu,etat){ 
			$( "#erreurMDPoublie" ).html(contenu);
			$("#erreurMDPoublie").css({color: "#000"});
			$( "#afficheFormMDPoublie" ).hide();
			$( "#erreurMDPoublie" ).show();
		}
	
	    });
	    
	}
	else {
	    $("#erreurMDPoublie").html(erreur);
	    $("#erreurMDPoublie").css({color: "#f00"});
	    $( "#erreurMDPoublie" ).show();
	}
	
	
    });
    
});