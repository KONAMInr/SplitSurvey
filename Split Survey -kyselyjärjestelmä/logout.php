<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinn�ytety�: Split Survey -kyselyj�rjestelm�
 */




	session_start();

	unset($_SESSION['sahkoposti']);

	header('Location: index.php');

?>