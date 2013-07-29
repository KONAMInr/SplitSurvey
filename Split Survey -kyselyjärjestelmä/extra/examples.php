<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */

require('../FormTableGenerator.class.php');

session_start();

// Tietokantaan yhdistäminen
$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");
if (!$connect)
{
die("MySQL ei voitu yhdistää");
}

$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

if(!$DB)
{
die("MySQL ei voinut valita tietokantaa");
}

$FTG = new FormTableGenerator($link);

echo $FTG->generatePage('anyTBL', 'Add'); //any tbl, literally, any table (though make sure all types are accounted for, see manual)
echo $FTG->generatePage('anyTBL', 'Edit');

echo $FTG->generateTable('anyTBl', 'Add'); //string representation

?>