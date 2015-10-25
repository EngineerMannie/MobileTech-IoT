<!DOCTYPE html>
<html>
<head>
	<title>eHealth Stats</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	
	<script>
		setTimeout(function(){location = ''},60000) // 60sec refresh
		
	</script>
	
</head>
<body onload="setInterval(location.reload(forceGet),3000);" >


    <div data-role="page" id="pageone">
 
        <div data-role="header">
            <h1>e-Health Readings for Patient</h1>
        </div><!-- /header -->
 
        <div data-role="content" data-Swatch="b">
            <p align="center">Readings from today for No.6<br>
			Temperature readings are external.</p>
			<div align="center">
				<table data-role="table" data-mode="columntoggle" id="temp-table">
				
					<thead>
						<tr>
						    <th width="80px">Time</th>
						    <th width="80px">BdyTemp</th>
							<th width="80px">HrtRate</th>
							<th width="80px">OxySats</th>
						</tr>
					</thead>
					<tbody>
						<?php include "ehealthstatus.php"; ?>
					</tbody>
				</table>
			</div>
        </div><!-- /content -->
 
        <div data-role="footer" class="ui-bar" data-position="fixed">
            <a href="" data-rel="back" data-icon="back">Go Back..</a>
            <a href="#pagetwo">eHealth History</a>
        </div><!-- /footer -->
 
    </div><!-- /page one-->
    
    <div data-role="page" id="pagetwo" data-add-back-button="true">
 
        <div data-role="header">
            <h1>e-Health History for Patient</h1>
        </div><!-- /header -->
 
        <div data-role="content" data-Swatch="b">
            <p align="center">Recent Summary of Records for No.6<br>
			Temperature readings are external.</p>
			<div align="center">
				<table data-role="table" data-mode="columntoggle" id="temp-table">
				
					<thead>
						<tr>
						    <th width="30px">Change</th>
							<th width="30px">Status</th>
							<th width="200px">Results</th>
						</tr>
					</thead>
					<tbody>
						<?php include "ehealthhistory.php"; ?>
					</tbody>
				</table>
			</div>
        </div><!-- /content -->
 
        <div data-role="footer" class="ui-bar" data-position="fixed">
            <a href="" data-rel="back" data-icon="back">Go Back..</a>
        </div><!-- /footer -->
 
    </div><!-- /page two-->
    
</body>
</html>