<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinn�ytety�: Split Survey -kyselyj�rjestelm�
 */



	session_start();

	// Kryptataan salasana 
	$Encrypt_salasana = md5($Salasana);
	$_SESSION['encrypt_salasana'] = $Encrypt_salasana;

	// Yhdist�minen tietokantaan
	$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");

	if (!$connect)
	{
		die("MySQL ei voitu yhdist��!");
	}

	$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

	if(!$DB)
	{
		die("MySQL ei voinut yhdist�� tietokantaan");
	}

	// Muuttujien m��rittely
	$Sahkoposti = $_GET['sahkoposti'];
	$Salasana = $_GET['salasana'];

	$Query = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti ='$Sahkoposti' AND Salasana = '$Salasana'");
	$NumRows = mysql_num_rows($Query);
	$_SESSION['sahkoposti'] = $Sahkoposti;
	$_SESSION['ecrypt_salasana'] = $Encrypt_salasana;

	// Tarkistetaan onko k�ytt�j� sy�tt�nyt kaikki tarvittavat tiedot

	if(empty($_SESSION['sahkoposti']) || empty($_SESSION['salasana']))
	{
		die("Palaa takaisin ja rekister�idy p��st�ksesi sivustolle");
	}

	if($Sahkoposti && $Salasana == "")
	{
		die("Anna k�ytt�j�tunnus ja salasana!");
	}

	if($Sahkoposti == "")
	{
		die("Anna k�ytt�j�tunnus!" . "</br>");
	}

	if($Salasana == "")
	{
		die("Anna salasana!");
		echo "</br>";
	}

	// K�ytt�j�tunnuksen ja salasanan tarkistaminen tietokannasta

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
		die("V��r� k�ytt�j�tunnus tai salasana!");
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



