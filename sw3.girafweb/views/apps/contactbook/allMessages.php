<!-- cbmessages.php -->
<div id="contactbookupperbar">
</div>

<div id="contactbook">
			
	<div id="newpost">
		<h3><a href="#">Nyt indlæg</a></h3>
		<div>
			<form name="newMessageForm" action="index.php?page=cbAddMessage" method="post" enctype="multipart/form-data">
			<input type="hidden" name="child" value=<?php echo '"' . $_GET["child"] . '"'; ?> id="newMessageChildId" />
			<table>
				<tr>
					<td>Oprettet af: </td><!-- Automantisk indsættes brugernavn-->
					<td><input type="text" name="user" value=<?php echo '"' . $userData->username . '"'; ?> readonly="readonly"/></td>
				</tr>
				<tr>
					<td>Overskrift: </td><!-- Overskrift, som skal være synlig fra oversigten-->
					<td><input name="subject" type="text" /></td>
				</tr>
				<tr>
					<td colspan="2"><textarea class="newpostinput" name="body"></textarea></td>
				</tr>
			<tr><td><input class="imageUpload" type="file" name="uploadImage0" id="uploadimage0" /></td></tr>
			<tr><td><input type="submit" name="submit" value="Send" /></td></tr>
			</table>
			</form>
		</div>
	</div>
	<!-- Here follows the complete accordian of contactbook messages. -->
	<div id="accordion">
		INSERT LIST OF MESSAGES HERE!
	</div>	
</div>
<div id="contactbooklowerbar">
</div>

<div id="readMessageDialog" title="%subject%">
<div id="messageDialogContents">This will write out some neat contactbook information!</div>
</div>
