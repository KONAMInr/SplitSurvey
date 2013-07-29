<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */



	session_start();

	// Kryptataan salasana 
	$Encrypt_salasana = md5($Salasana);
	$_SESSION['encrypt_salasana'] = $Encrypt_salasana;

	// Yhdistäminen tietokantaan
	$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");

	if (!$connect)
	{
		die("MySQL ei voitu yhdistää!");
	}

	$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

	if(!$DB)
	{
		die("MySQL ei voinut yhdistää tietokantaan");
	}

	// Muuttujien määrittely
	$Sahkoposti = $_GET['sahkoposti'];
	$Salasana = $_GET['salasana'];

	$Query = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti ='$Sahkoposti' AND Salasana = '$Salasana'");
	$NumRows = mysql_num_rows($Query);
	$_SESSION['sahkoposti'] = $Sahkoposti;
	$_SESSION['ecrypt_salasana'] = $Encrypt_salasana;

	// Tarkistetaan onko käyttäjä syöttänyt kaikki tarvittavat tiedot

	if(empty($_SESSION['sahkoposti']) || empty($_SESSION['salasana']))
	{
		die("Palaa takaisin ja rekisteröidy päästäksesi sivustolle");
	}

	if($Sahkoposti && $Salasana == "")
	{
		die("Anna käyttäjätunnus ja salasana!");
	}

	if($Sahkoposti == "")
	{
		die("Anna käyttäjätunnus!" . "</br>");
	}

	if($Salasana == "")
	{
		die("Anna salasana!");
		echo "</br>";
	}

	// Käyttäjätunnuksen ja salasanan tarkistaminen tietokannasta

	if($NumRows != 0)
	{
		while($Row = mysql_fetch_assoc($Query))
	{

	$Database_Sahkoposti = $Row['sahkoposti'];
	$Database_Salasana = $Row['salasana'];
	}
}
	else
	{
		die("Väärä käyttäjätunnus tai salasana!");
	}

	if($Sahkoposti == $Database_Sahkoposti && $Salasana == $Database_Salasana)
	{

		// Kirjautuminen on onnistunut
		echo "Tervetuloa " . $_SESSION['sahkoposti'] . "!";
	}


?>

<html>
<body>
<br>
<a href="profile.php">Oma profiili</a>
<a href="index.php">Etusivulle</a>
</body>
</html>



