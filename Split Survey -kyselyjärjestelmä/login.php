<?php
	
	session_start();
	if(isset($_SESSION['sahkoposti']))
	{
		header('Location: update.php');
	}

?>

<link rel="stylesheet" type="text/css" href="StyleLogin.css">
<div id="StyleSheet" class="LoginForm">
<form id="form" name="form" method="get" action="login_valid.php">
<h1>Kirjaudu sis��n</h1>
<p>Experience the new way to create surveys</p>

<label>S�hk�postiosoite
<span class="small">S�hk�posti toimii k�ytt�j�tunnuksena</span>
</label>
<input type="text" name="sahkoposti" id="sahkoposti" />

<label>Salasana
<span class="small">Min. koko 6 merkki�</span>
</label>
<input type="password" name="salasana" id="password />

<input type="submit" value="Kirjaudu"><br><br>
<div class="spacer"></div>
</form>
</div>