<?php

	// --------------- Kuvan lisäys profiilisivulle update_profile_pic.php ?? -------

       session_start();

	// Yhdistäminen tietokantaan
	$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");

	if (!$connect)
	{
		die("MySQL could not connect!");
	}

	$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

	if(!$DB)
	{
		die("MySQL ei voinut yhdistää tietokantaan");
	}

    	$Query = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti='$Sahkoposti';
   	$Tulos = mysql_query($Query) or die(mysql_error());

   	while($row = mysql_fetch_array($Tulos))
   	{
   		 echo "<img border=\"0\" src=\"".$row['Image']."\" width=\"102\" alt=\"Your Name\" height=\"91\">";
    	}

	// --------------------------------------------------------------------------


	if(isset($_GET['sahkoposti']))
	{
		$Sahkoposti = $_GET['sahkoposti'];
	}

	else
	{
		echo "Kyseistä käyttäjätunnusta ei löydy";
	}

	// Etsitään käyttäjä
	$connect = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti='$Sahkoposti'");
	
	if($Query->mysql_num_rows !=1)
	{
		echo "Kyseistä käyttäjää ei löydy";
	}

	else
	{
		$row = $Query->fetch_object();
		$Sahkoposti = $row->Sahkoposti;
		$Etunimi = $row->Etunimi;
		$Sukunimi = $row->Sukunimi;
		$Yritys = $row->Yritys;
		$Ika = $row->Ika;
		$Sukpuoli = $row->Sukupuoli;
		// ??????? $pBio = Query->my_sql_num_rows("SELECT * FROM kayttajat WHERE Sahkoposti ='$Sahkoposti'");
		$Query = mysql_query("SELECT * FROM kayttajat WHERE Sahkoposti='$Sahkoposti' AND sSana='$Salasana'");
		
		if($pBio->num_rows == 1)
		{
			$row = $pBio->fetch_object();
			$Etunimi = $row->Etunimi;
			$Sukunimi = $row->Sukinimi;
			$Yritys = $row->Yritys;
			$Ika = $row->Ika;
			$Sukupuoli = $row->Sukupuoli;
		}

		else
		{
			$Etunimi = "n/a";
			$Sukunimi = "n/a";
			$Yritys = "n/a";
			$Ika = "n/a";
			$Sukupuoli = "n/a";
		}

		$_SESSION['sisalla'] = true;
		$_SESSION['sahkoposti'] = $Sahkoposti;
 
		if(isset($_SESSION['sisalla']) && $_SESSION['sahkoposti'] == $Sahkoposti)
              {
              	$pSivu = true;
              }

	}
?>

<body>
	<header class = "container_12">
		<h1>Profiilisivusto</h1>
	</header>

	<?php if(isset($pSivu)):?>
	<nav>
		<ul>
			<li><a href = "update.php">Muokkaa profiilia</a></li>
			<li><a href = "update_profile_pic.php">Muokkaa profiilikuvaa</a></li>
			<li><a href = "delete.php">Poista tunnus</a></li>
			<li><a hfref = "logout.php">Kirjaudu ulos</a></li>
		</ul>
	</nav>

	<?php endif; ?>

	<div id = "main" class = "container_12">

		<aside class = "grid_3">
			img src = "profiili.png"/>
		</aside>

		<section id = "primary" class = "grid_9">
			<div id = "bio">
			<h2><?php echo $Sahkoposti?>n profiilisivusto</h2>
			<p>
		</section>

	</div>