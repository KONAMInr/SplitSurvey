<?php


/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */



	session_start();

	if(!isset($_SESSION['sahkoposti']))
	{
		die("Kirjaudu sisään");
	}

	$Sahkoposti = $_SESSION['sahkoposti'];

	$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");

	if (!$connect)
  	{
  		die('ei voitu yhdistää: ' . mysql_error());
  	}

	$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

?> 

<link rel="stylesheet" type="text/css" href="StyleUpdate.css">
<div id="StyleSheet" class="UpdateForm">
<form id="form" name="form" method="get" action="update.php">
<h1>Muokkaa tietojasi</h1>
<p>Experience the new way to create surveys</p>

<label>Sähkäpostiosoite</label>
<input type="text" name="sahkoposti" id="sahkoposti" />
<br>

<label>Etunimi</label><br>
<input type="text" name="etunimi" id="etunimi" />

<labe>Sukunimi</label>
<input type="text" name="sukunimi" id="sukunimi" />

<label>Yritys</label>
<input type="text" name="yritys" id="yritys" />

<label>Ikäsi</label>
<input type="radio" name="ika" id ="ika" selected>alle 18<br><br>
<input type="radio" name="ika" id="ika"/> yli 18</br></br>
<div class="spacer"></div>

<label>Sukupuolesi</label>
<input type="radio" name="sukupuoli" id="sukupuoli" selected>Mies<br><br>
<input type="radio" name="sukupuoli" id="sukupuoli"/> Nainen</br></br>
<input type="submit" value="Muokkaa"><br><br>
</a>
</form>

<a href="profile.php">Takaisin profiiliini</a>

<?php

	if($_GET["salasana"] != NULL)
	{
		if ($_GET["salasana"] == $_GET["Tarkista_salasana"]) 
		{
		 	$Salasana = $_GET["salasana"];
		 	$Query = mysql_query("UPDATE kayttajat SET Salasana = '$Salasana' WHERE Sahkoposti = '$Sahkoposti'");
		}

		else 
		{
			die("Salasanat eivät täsmää keskenään");  
		}
	}


	if($_Get["ika"] != NULL)
	{
		$Ika = $_GET["ika"];
		$Query = mysql_query("UPDATE kayttajat SET Ika = '$Ika' WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("Määritä ikä!");
	}

	if($_GET["sukupuoli"] != NULL)
	{
		$Sukupuoli = $_POST["sukupuoli"];
		$Query = mysql_query("UPDATE kayttajat SET Sukupuoli = '$Sukupuoli'  WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("Määritä sukupuoli!");
	}

	if($_GET["sahkoposti"] != NULL)
	{
		$Sahkoposti = $_POST["sahkoposti"];
		$Query = mysql_query("UPDATE kayttajat SET Sahkoposti = '$Sahkoposti' WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("Määritä sähköposti!");
	}

?>
