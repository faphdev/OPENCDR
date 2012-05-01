<?php
include 'DAL/table_cdr.php';
include 'DAL/table_callrecordmaster_tbr.php';
include 'config.php';

/*
To access the database from the server backup.nutxase.co.za

host : core.vsave.co.za

username: billing
password: koopista41

database name asteriskcdrdb
*/

$host = 'localhost';
$user = 'root';
$password = '123';
$database = 'cdr';
$port = '3307';
$cdr_table = new mysql_cdr($host, $user, $password, $database, $port);
$cdr_table->connect();
$cdrs = $cdr_table->SelectTop1000();

$failedRows = array();
$callmaster_table = new psql_callrecordmaster_tbr($connectstring);

$callmaster_table->Connect();
$zeroDuration = 0;
foreach($cdrs as $cdr){
	#insert into TBR
	
	$callid = $cdr['uniqueid'];
	$customerid = $cdr['accountcode'];
	$calldatetime = $cdr['calldate'];
	$duration = $cdr['billsec'];
	$originatingnumber = $cdr['src'];
	$destinationnumber = $cdr['dst'];
	$carrierid = $cdr['dstchannel'];
	if($duration == 0){
		$zeroDuration++;
		$cdr_table->Update(array('uniqueid'=>$callid), array('amaflags' => 100));
		continue;
	}
	$tbrRow = array('callid' => $callid,'customerid' => $customerid,
					'calldatetime' => $calldatetime,'duration' => $duration,
					'originatingnumber' => $originatingnumber,'destinationnumber' => $destinationnumber,
					'carrierid' => $carrierid);
	$insertResult = $callmaster_table->Insert($tbrRow);
	
	if(!$insertResult){
		$failedRows[] = $cdr;
		continue;
	}
	#update mysql cdr table
	$cdr_table->Update(array('uniqueid'=>$callid), array('amaflags' => 100));
	#if fail : 
	#	$failedRows[] = $cdr
}
#execute fnCategorizeCDR
$categorizeStatement = 'SELECT "fnCategorizeCDR"();';
$categorizeResult = pg_query($categorizeStatement);
$cdr_table->Disconnect();
$callmaster_table->Disconnect();

echo "Processed ". $callmaster_table->InsertedCount . " CDR<br>";
echo "Zero duration count : " . $zeroDuration . "<br>";
echo "Duplicate count : " . $callmaster_table->SkippedDuplicateCount . "<br>";
?>