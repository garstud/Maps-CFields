  <style type="text/css"> 
    <!-- 
    #map_canvas {
      height:600px
    }
    div.infowindow {
		  max-height:300px;
		  overflow-y:auto;
	}
	.mapinf_content{
		  width:280px;
		  height:200px;
	}
  #map_legend {
        font-family: Arial, sans-serif;
        background: #fff;
        padding: 10px;
        margin: 10px;
        border: 3px solid #000;
   }
   #map_legend h3 {
        margin-top: 0;
   }
   #map_legend img {
        vertical-align: middle;
   }      
    -->
  </style> 

  <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?php echo $this->params->gmapsKey ?>&callback=initMap" type="text/javascript"></script>    

<?php if(this->params->gmapsCluster) : ?>
  <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>    
<?php endif; ?>

  <script type="text/javascript"> 
	var map = null;
	var currInfo = null;
	var infoMaxWidth = 320;
	var infoContent1 = '<div class="mapinf_content"><h3>';

	function initMap() {
        // params 
        var paramMapHeight = 600;
        var paramInitLat = 48.86;
        var paramInitLong = 2.35;
        var paramInitZoom = 3;
        var paramMapType = google.maps.MapTypeId.ROADMAP;
        var paramMinZoom = 2;
        var paramMaxZoom = 20;
        
		var latlng = new google.maps.LatLng( paramInitLat, paramInitLong );
		var myOptions = { zoom: paramInitZoom, 
			minZoom:paramMinZoom, maxZoom:paramMaxZoom, 
			center: latlng, mapTypeId: paramMapType,
			panControl: false,
			zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM
            },
            mapTypeControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU, 
                                    mapTypeIds: ['roadmap', 'terrain', 'hybrid', 'satellite']},
            scaleControl: true,
			streetViewControl: true,
            rotateControl: true,
            fullscreenControl: true,
            styles: [
                {"elementType": "geometry", "stylers": [{"color": "#f5f5f5"}]},
                {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
                {"elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},
                {"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f5f5"}]},
                {"featureType": "administrative.land_parcel", "stylers": [{"visibility": "off"}]},
                {"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#bdbdbd"}]},
                {"featureType": "administrative.neighborhood", "stylers": [{"visibility": "off"}]},
                {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]},
                {"featureType": "poi", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},
                {"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},
                {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"}]},
                {"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]},
                {"featureType": "road", "stylers": [{"visibility": "off"}]},
                {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#ffffff"}]},
                {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]},
                {"featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},
                {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#dadada"}]},
                {"featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},
                {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]},
                {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"}]},
                {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]},
                {"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#d9c9c9"}]},
                {"featureType": "water", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},
                {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]}
            ]                     
		};    
      
        var jdayMapType = new google.maps.StyledMapType([
                {"elementType": "geometry", "stylers": [{"color": "#e0e0e5"}]},
                {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
                {"elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},
                {"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f5f5"}]},
                {"featureType": "administrative.land_parcel", "stylers": [{"visibility": "off"}]},
                {"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#bdbdbd"}]},
                {"featureType": "administrative.neighborhood", "stylers": [{"visibility": "off"}]},
                {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]},
                {"featureType": "poi", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},
                {"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},
                {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"}]},
                {"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]},
                {"featureType": "road", "stylers": [{"visibility": "off"}]},
                {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#ffffff"}]},
                {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]},
                {"featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]},
                {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#dadada"}]},
                {"featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{"color": "#616161"}]},
                {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]},
                {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"}]},
                {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#eeeeee"}]},
                {"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#a9b9c9"}]},
                {"featureType": "water", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},
                {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"}]}
            ], {name: 'Jday Map'});
               
        // Styles a map in night mode.
        // https://mapstyle.withgoogle.com/
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('jday_map', jdayMapType);
        map.setMapTypeId('jday_map');        
        
		// ajout des marqueurs sur la carte
		var markers = [];

		//-----------------------------------
		var name = "Marc Studer";
        var town = "Aix-en-Provence, France";
		var photoUrl = "https://www.joomladay.fr/images/2018/intervenants/marc-studer.jpg";
        var photoCode = "<img src='"+photoUrl+"' with=100 height=100>"
		var confs = "Développer un module complet en php<br />Lightning Talks Pro";
        var linkUrl = "https://www.joomladay.fr/intervenants/marc-studer";
        var linkCode = "<a href='"+linkUrl+"'>Fiche intervenant</a>";
        var coordLat = 43.53;
        var coordLong = 5.45;
            
		ltlg = new google.maps.LatLng(coordLat, coordLong);
		var mkr1 = new google.maps.Marker({ map:map, position:ltlg, title:name, label:"MS" });
		var txt1 = infoContent1+name+"</h3>"+town+"<br/>"+photoCode+"<br/>"+confs+"<br/>"+linkCode+"</div>";
		google.maps.event.addListener(mkr1, 'click', function() { 
				if (currInfo) { currInfo.close(); }
				currInfo = new google.maps.InfoWindow({ maxWidth: infoMaxWidth });
				currInfo.setContent(txt1);
				currInfo.open(map, mkr1); 
		});
		markers.push(mkr1);
        
		//-----------------------------------
		var name = "Marc Dechèvre";
        var town = "Bruxelles, Belgique";
		var photoUrl = "https://www.joomladay.fr/images/2018/intervenants/marc-dechevre.jpg";
        var photoCode = "<img src='"+photoUrl+"' with=100 height=100>"
		var confs = "Automatiser la restauration de ses sites<br />la beauté et la puissance des custom fields couplés aux overrides de layout";
        var linkUrl = "https://www.joomladay.fr/intervenants/marc-dechevre";
        var linkCode = "<a href='"+linkUrl+"'>Fiche intervenant</a>";
        var coordLat = 50.84;
        var coordLong = 4.37;
            
		ltlg = new google.maps.LatLng(coordLat, coordLong);
		var mkr2 = new google.maps.Marker({ map:map, position:ltlg, title:name, label:"MD" });
		var txt2 = infoContent1+name+"</h3>"+town+"<br/>"+photoCode+"<br/>"+confs+"<br/>"+linkCode+"</div>";
		google.maps.event.addListener(mkr2, 'click', function() { 
				if (currInfo) { currInfo.close(); }
				currInfo = new google.maps.InfoWindow({ maxWidth: infoMaxWidth });
				currInfo.setContent(txt2);
				currInfo.open(map, mkr2); 
		});
        markers.push(mkr2);     

        //---------------CLUSTER--------------------
     <?php if(this->params->gmapsCluster) : ?>
        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      <?php endif; ?>

        
        //---------------LEGEND--------------------
       var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
        var icons = {
          parking: {
            name: 'Parkings JoomlaDay',
            icon: iconBase + 'parking_lot_maps.png'
          },
          library: {
            name: 'Coordinateurs',
            icon: iconBase + 'library_maps.png'
          },
          info: {
            name: 'Info',
            icon: iconBase + 'info-i_maps.png'
          }
        };
        var legend = document.getElementById('map_legend');
        for (var key in icons) {
          var type = icons[key];
          var name = type.name;
          var icon = type.icon;
          var div = document.createElement('div');
          div.innerHTML = '<img src="' + icon + '"> ' + name;
          legend.appendChild(div);
        }
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(legend);           
    }
  </script>	
</head> 


<body>
    <div class="component_gwmap" style="width:99%"> 
      <div id="map_canvas"></div> 
      <div id="map_legend"><h3>Legend</h3></div> 
    </div> 	   
</body> 
</html> 
 
