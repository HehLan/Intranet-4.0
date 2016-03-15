   
    <style type="text/css">
      
      #map-canvas {
        height: 600px;
        width: 680px;
        margin-left: auto;
        margin-right: auto;
        }
      #infobulle {
        width: 320px;
        height: 120px;
      }
    </style>

    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYz40Nmfn_223Ro_q7FBJ2MxuLGFgMzu0&sensor=true">
    </script>
    
    <script type="text/javascript">
        //attention à mettre le bon chemin (à partir d'où il est importé)
        //indiquer le chemin de la photo
        var cheminLogo = "../img/HEH_LOGO.GIF";
        var geocoder;
        var map;
        var infowindow = new google.maps.InfoWindow();
        var marker;
        var latlng = new google.maps.LatLng(50.462121, 3.9575029999999742);
        var mapOptions = {
            zoom: 10,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        // Texte pour l'infobulle
        var infobul = "<div id='infobulle'>"+
        "<table border='0' width='310' cellpadding='2' cellspacing='0'>"+
        "<tr><td valign='top'><div style='color: blue; font-size: 12px; font-weight:bold;'>HeHLan</div><br/>Avenue Victor Maistriau 8a<br/>7000 Mons, Belgique<br/>"+
        "<a href='http://www.heh.be'>www.heh.be</a>"+
        "</td>"+
        "<td>"+
        "<img src='"+cheminLogo+"' border='0' alt='Photo' vspace='4' width='100px' height='63px' align='right' />"+
        "</td></tr></table></div>";
            
        
        function initialize() {
                   
            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
            //point sur la carte
            var imageMarqueur = new google.maps.MarkerImage(cheminLogo,null,null,null,new google.maps.Size(100, 63));
            marker = new google.maps.Marker({
                map: map,
                position: latlng,
                icon: imageMarqueur
            });
            /* Affichage de l'infowindow sur le marker avec l'adresse récupérée */
            infowindow.setContent(infobul);
            setTimeout(function(){
                      infowindow.open(map, marker);
                      map.setCenter(latlng);}
                      ,3000);
            
                     
            
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map, marker);
                map.setCenter(marker.getPosition());
              });
            map.setCenter(latlng);
            
          
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        /*window.onload=function(){
              setTimeout(function(){
                      infowindow.open(map, marker);
                      map.setCenter(latlng);}
                      ,2000);
            };*/
        
        
    </script>
<div id="map-canvas" /> 