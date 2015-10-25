<!DOCTYPE html>
<html>
<head>
	<title>My Connection PHP page</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset="utf-8">

	<?php
	// get the position from the url
	$latitude = htmlspecialchars($_GET["pos1"]);
	$longitude = htmlspecialchars($_GET["pos2"]);

	?>
	<style>
		html, body, #map-canvas {
			height: 600px;
			width: 600px;
			margin: 0px;
			padding: 0px
		}
	</style>
	
	<script>
	  (function() {
		var cx = '018318980045356464995:c0grsuqancq';
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			'//www.google.com/cse/cse.js?cx=' + cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
	  })();
	</script>
	<gcse:search></gcse:search>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp">
	</script>
	
	<script>
	
	function initialize(){
		var myLatLng = new google.maps.LatLng(<?php echo $latitude.", ".$longitude; ?>);
		var mapOptions = {
			zoom: 12,
			center: myLatLng
		};
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			title: 'You are here!'
		});
	}
	
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</head>

<body>
	
	<p id="demo">The Temerature Sensor Position is: <?php echo $latitude; ?> Lat, <?php echo $longitude; ?> Long.</p>
		
	<div id="map-canvas">
	</div>
</body>
</html>
