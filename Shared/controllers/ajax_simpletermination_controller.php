<?php
$path = $_SERVER["DOCUMENT_ROOT"].'/Shared/';
include_once $path . 'lib/FileUtils/SimpleterminationRateFileImporter.php';
include_once $path . 'DAL/table_simpleterminationratemaster.php';
include_once $path . 'conf/ConfigurationManager.php';
$manager = new ConfigurationManager();
$connectstring = $manager->BuildConnectionString();

$customerid = $_GET['customerid'];
	$table = new psql_simpleterminationratemaster($connectstring);
	$table->Connect();
	$myFile = 'php://input';
	header('Content-Type: text/html');
	header('Content-Length: ' . $_SERVER['HTTP_X_FILESIZE']);
	if(empty($myFile)){
		trigger_error("Please choose a file");
	}
	else{
		$FileImporter = new SimpleterminationRateFileImporter($table, $customerid);
		$FileImporter->Open($myFile);
		while($line = $FileImporter->ImportLine()){
			echo $line;
		}
		$FileImporter->Close();
	}
?>