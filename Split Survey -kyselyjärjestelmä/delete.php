<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinn�ytety�: Split Survey -kyselyj�rjestelm�
 */

	session_start();
	if(!isset($_SESSION['sahkoposti']))
	{
		die("Kirjaudu sis��n");
	}

	$Sahkoposti = $_SESSION['sahkoposti'];

	$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");

	if (!$connect)
  	{
  		die('ei voitu yhdist��: ' . mysql_error());
  	}

	$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

	mysql_query("DELETE FROM kayttajat WHERE Sahkoposti = '$Sahkoposti'");

	mysql_close($connect);

?> 

<html>
<body>
<br>
<a href="index.php">etusivulle</a>
</body>
</html>