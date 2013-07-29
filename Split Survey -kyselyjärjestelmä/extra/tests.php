<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinn‰ytetyˆ: Split Survey -kyselyj‰rjestelm‰
 */

define('BR', '<br />');
require('../FormTableGenerator.class.php');

session_start();

// Tietokantaan yhdist‰minen
$connect = mysql_connect("mysql1.sigmatic.fi","jmdproje_konami","NINNI123");
if (!$connect)
{
die("MySQL ei voitu yhdist‰‰");
}

$DB = mysql_select_db('jmdproje_kyselyjarjestelma');

if(!$DB)
{
die("MySQL ei voinut valita tietokantaa");
}

$FTG = new FormTableGenerator($link);

echo '<pre><b>Populating Class with Test Data...</b>'.BR.BR;
echo '---- FTG:_getTableInfo, _cleanUp {if Label field exists, clean up was successful ----</b>'.BR.BR;
var_dump($FTG->getTableInfo($anyTBl)); 

echo BR.BR.BR.'<b>Testing private functions:</b>'.BR.BR;

echo BR.BR.'<b>---- FTG:_formField ----</b>'.BR.BR;
echo $FTG->_formField('textfield', 'testfield');

echo BR.BR.'<b>---- FTG:_formPart ----</b>'.BR.BR;
var_dump(htmlentities($FTG->_formPart('formOpen', 'test_id', 'test_action', 'GET')));

echo BR.BR.'<b>---- INDIRECT: FTG:_getFTGXML ----</b>'.BR.BR;
var_dump($FTG->types);
var_dump($FTG->columnField);
var_dump($FTG->formField); //may produce white space, check source, should not be string(0) or null etc. anyways
var_dump($FTG->masterHtml);


echo BR.BR.BR.'<b>Testing Public Funtions</b>'.BR.BR;
var_dump($FTG->generateTable($anyTBl, 'Add'));
echo '</pre>';
?>