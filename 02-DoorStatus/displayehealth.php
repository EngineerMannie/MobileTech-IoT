<!DOCTYPE html>
<html>
<head>
	<title>jQuery Temps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	
	<script>
		setTimeout(function(){location = ''},10000) // 10sec refresh
		
	</script>
	
</head>
<body onload="setInterval(location.reload(forceGet),3000);" >


    <div data-role="page" id="pageone">
 
        <div data-role="header">
            <h1>e-Health Readings for Patient</h1>
        </div><!-- /header -->
 
        <div data-role="content" data-Swatch="b">
            <p align="center">Readings from today F.Y.I.<br>
			Temperature Readings are External.</p>
			<div align="center">
				<table data-role="table" data-mode="columntoggle" id="temp-table">
				
					<thead>
						<tr>
						    <th width="100px">Time</th>
						    <th>BdyTemp</th>
							<th>HrtRate</th>
							<th>OxySats</th>
						</tr>
					</thead>
					<tbody>
						<?php include "ehealthstuff.php"; ?>
					</tbody>
				</table>
			</div>
        </div><!-- /content -->
 
        <div data-role="footer" data-position="fixed">
            <h4>e-Health Stuff</h4>
        </div><!-- /footer -->
 
    </div><!-- /page one-->
    
    
    
</body>
</html>