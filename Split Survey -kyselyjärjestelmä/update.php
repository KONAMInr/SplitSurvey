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

?> 

<link rel="stylesheet" type="text/css" href="StyleUpdate.css">
<div id="StyleSheet" class="UpdateForm">
<form id="form" name="form" method="get" action="update.php">
<h1>Muokkaa tietojasi</h1>
<p>Experience the new way to create surveys</p>

<label>S�hk�postiosoite</label>
<input type="text" name="sahkoposti" id="sahkoposti" />
<br>

<label>Etunimi</label><br>
<input type="text" name="etunimi" id="etunimi" />

<labe>Sukunimi</label>
<input type="text" name="sukunimi" id="sukunimi" />

<label>Yritys</label>
<input type="text" name="yritys" id="yritys" />

<label>Ik�si</label>
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
			die("Salasanat eiv�t t�sm�� kesken��n");  
		}
	}


	if($_Get["ika"] != NULL)
	{
		$Ika = $_GET["ika"];
		$Query = mysql_query("UPDATE kayttajat SET Ika = '$Ika' WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("M��rit� ik�!");
	}

	if($_GET["sukupuoli"] != NULL)
	{
		$Sukupuoli = $_POST["sukupuoli"];
		$Query = mysql_query("UPDATE kayttajat SET Sukupuoli = '$Sukupuoli'  WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("M��rit� sukupuoli!");
	}

	if($_GET["sahkoposti"] != NULL)
	{
		$Sahkoposti = $_POST["sahkoposti"];
		$Query = mysql_query("UPDATE kayttajat SET Sahkoposti = '$Sahkoposti' WHERE Sahkoposti = '$Sahkoposti'");
	}

	else
	{
		die("M��rit� s�hk�posti!");
	}

?>
