<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */



	$kysymyssarja1 = $_GET['kysymyssarja1'];
	$kysymyssarja2 = $_GET['kysymyssarja2'];
	$kysymyssarja3 = $_GET['kysymyssarja3'];
	$kysymyssarja4 = $_GET['kysymyssarja4'];
	$kysymyssarja5 = $_GET['kysymyssarja5'];
	$kysymyssarja6 = $_GET['kysymyssarja6'];
	$kysymyssarja7 = $_GET['kysymyssarja7'];
	$kysymyssarja8 = $_GET['kysymyssarja8'];
	$kysymyssarja9 = $_GET['kysymyssarja9'];
	$kysymyssarja10 = $_GET['kysymyssarja10'];

	$valinta = 0;

	foreach($_POST['kysymyssarja'] as $kSarja)
	{
		$valinta++;
	}

?>
		
	