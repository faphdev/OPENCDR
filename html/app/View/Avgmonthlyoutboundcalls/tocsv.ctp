<?php
header('Content-Type: application/csv'); 
header('Content-Disposition: attachment; filename="inboundvsoutboundreport.csv"');
?>

<?php
	$i = 0;
	foreach ($data as $row):
		echo implode(',',$row['Callspermonthpercarrier']);
		echo ',';
		echo implode(',',$row[0]);
		echo "\r\n";
	endforeach;
	
?>