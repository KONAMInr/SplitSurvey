<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */




	session_start();

	unset($_SESSION['sahkoposti']);

	header('Location: index.php');

?>