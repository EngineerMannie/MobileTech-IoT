<!DOCTYPE html>
<html>
<head>
	<title>SMS Stuff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
</head>
<body>


    <div data-role="page" id="pageone">
 
        <div data-role="header">
            <h1>Page One</h1>
        </div><!-- /header -->
 
        <div data-role="content" data-Swatch="b">
			<!-- remove action and replace with text file action="http://driesh.abertay.ac.uk/......info removed....." -->
			<form id="smsForm" action="test.php" method="post">
				
				<div data-role="fieldcontain">
					<label for="mphone">Your UAD SMS Registered Phone Number:</label>
					<input type="tel" name="mphone" id="mphone" value="" required />
				</div>
				<div>
					<label for="smstext">Text Message:</label>
					<textarea name="smstext" id="smstext" value="" required ></textarea>
				</div>
				<div>
					<label for="username">Your Student Number:</label>
					<input type="number" name="username" id="username"value="" required >
				</div>
				<button name="send" type="submit" value="add">SEND</button>
				
			</form>
		</div><!-- /content -->
		
		<div data-role="footer" class="ui-bar" data-position="fixed">
            <a href="" data-rel="back" data-icon="back">Go Back..</a>
        </div><!-- /footer -->
		
	</body>
	
</html>