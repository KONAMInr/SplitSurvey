<?php include "../connect-to-database.php";  ?>

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

    	date_default_timezone_set('Europe/Helsinki');
    	$timezone = date_default_timezone_get();
    	$mod_date = strtotime($timezone+ 2days");
    	$newDate = $timezone,$mod_date);

   	$sqlCommand = "SELECT * FROM eventcalender WHERE eventDate='$newDate'" ;
   	$Query = mysql_query($sqlCommand) or die(mysql_error());
  	$count = mysql_num_rows($query);

	if ($count >= 1)
	{
       	while($row = mysql_fetch_array($query)){
            	$ID = $row["ID"];
    		$schedule_title = $row["Title"];
    		$schedule_description = $row["Detail"];
    		$importance_level = $row["importance_level"];
    		$meeting_datetime = $row["eventDate"];
    		$contacts_involved = $row["contacts_involved"];
    		$meeting_occurred = $row["meeting_occurred"];

    		$mid = mysql_insert_id();
    		$search_output .= "<ul>
                <li>
                    <h4>".$schedule_title."</h4>
                    <p><b>Time: ".$meeting_datetime."</b></p>
                    <p>People/Persons involved: ".$contacts_involved."</p>
                    <p>Meeting Occurred?: ".$meeting_occurred."</p>
                    <a href='uniqueMeeting.php?ID=".$ID."'>View more details of this meeting</a>
                    <p><a href='editschedulePage.php?mid=$ID'>Edit This Meeting</a></p>
                    <p><a href='scheduleList.php?deleteid=$ID'>Delete this meeting</a></p>
                </li><br/>                  
            </ul>";

            $sendMail = true;
        }

}

	if($sendMail){  
    	$admin = "SELECT * FROM admin" ;
    	$queryAdmin = mysql_query($admin) or die(mysql_error());
    	$adminCount = mysql_num_rows($queryAdmin);
    	$recipients = array();
        	if ($count >= 1)
		{
            		while($row = mysql_fetch_array($queryAdmin)){

            		$subject ='A-CRM; UpComing Activities';
            		$msg = $search_output; 
            		$to = $row['email_address'];
            		mail($to, $subject, $msg);
          	}   
        }
}

?>