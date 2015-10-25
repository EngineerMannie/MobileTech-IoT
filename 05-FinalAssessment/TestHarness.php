
<form id="smsForm" action="ehealthpost.php" method="post">
				
	<div data-role="fieldcontain">
		<label for="mphone">Phone Number:</label>
		<input type="phone" name="originator" id="originator" value="" required />
	</div>
	<div>
		<label for="smstext">Text Message:</label>
		<textarea name="body" id="body" value="" required ></textarea>
	</div>
	<div>
		<label for="username">dat & time</label>
		<input type="date" name="receivedat" id="receivedat"value="" required >
	</div>
	<button name="send" type="submit" value="add">SEND</button>
				
</form>