$( document ).ready(function() {
  
	$('#nav-center li a').hover(function(e){
		e.target.className = "active"
	},function(e){
		if($.trim(e.target.innerHTML) != "accueil")
			e.target.className = "";
	});

});