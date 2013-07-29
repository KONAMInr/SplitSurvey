<?php

   	 session_start();

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
	$Sahkoposti = $_POST['sahkoposti'];
	$Salasana = $_POST['salasana'];

	// Kuvanpäivittämiskansio
   	$uploadDir = 'images/';

   	if(isset($_POST['Submit']))
   	{
    		$TiedostoNimi = $_FILES['kuva']['nimi'];
    		$TmpNimi = $_FILES['kuva']['tmp_nimi'];
    		$TiedostoKoko = $_FILES['kuva']['koko'];
    		$TiedostoTyyppi = $_FILES['kuva']['tyyppi'];
    		$TiedostoPolku= $uploadDir . $TiedostoNimi;
    		$Tulos = siirra_paivitettava_tiedosto($TmpNimi, $TiedostoPolku);

   	 	if (!Tulos) 
		{
    			echo "Virhe 404 kuvaa päivittäessä ";
    			exit;
    		}

   	 	if(!get_magic_quotes_gpc())
    		{
   			$TiedostoNimi = addslashes($TiedostoNimi);
    			$TiedostoPolku = addslashes($TiedostoPolku);
    		}

   		$Query = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti='$Sahkoposti' ( image ) VALUES ('$TiedostoPolku')";
    		mysql_query($Query) or die('Error, query failed');
    	}

?>

    <form name="Image" enctype="multipart/form-data" action="image.php" method="POST">
    <input type="file" name="Photo" size="2000000" accept="image/gif, image/jpeg, image/x-ms-bmp, image/x-png" size="26"><br/>
    <INPUT type="submit" class="button" name="Submit" value=" Submit ">
    &nbsp;&nbsp;<INPUT type="reset" class="button" value="Cancel">
    </form>