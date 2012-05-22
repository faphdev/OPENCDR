<?php

include_once 'config.php';
	include_once $path . 'lib/Page.php';
	include_once $path . 'conf/ConfigurationManager.php';
	include_once $path . 'lib/localizer.php';
	$manager = new ConfigurationManager();
	$connectstring = $manager->BuildConnectionString();
	$locale = $manager->GetSetting('region');
	$region = new localizer($locale);

$htmltable = <<<HEREDOC
<table id="listcostumer-table" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr>
<th>Date</th>
<th>Peak</th>
<th>Average</th>
</tr>
</thead>
<tbody>
HEREDOC;

	$csv_output = "";
	$csv_hdr = "Date|Peak|Average";
	$csv_hdr .= "\n";

	$db = pg_connect($connectstring);

	$query = "select cast(calldatetime as date) as \"Date\", max(concurrentcalls) as \"Peak\", avg(concurrentcalls) as \"Average\" from concurrentcalls
                 group by cast(calldatetime as date)
                 order by cast(calldatetime as date);";
	
	$result = pg_query($query);

	while($myrow = pg_fetch_assoc($result)) {

$htmltable .= <<<HEREDOC
<tr>
<td>{$region->FormatDate($myrow['Date'])}</td>
<td>{$myrow['Peak']}</td>
<td>{$myrow['Average']}</td>
</tr>\n
HEREDOC;

$csv_output .= $myrow['Date']. "|". $myrow['Peak']. "|". $myrow['Average']. "\n";

} 

$htmltable .= '
	    </tbody>
	    <tfoot>
	    	<tr>
		    <td colspan="3"></td>
	    	</tr>
	    </tfoot>
		</table>';

?>
<head>
<?php echo GetPageHead("Concurrent Calls - Peak and Average per Day", "reports.php")?>
</head>

<div id="body">

	<form name="export" action="exportpipe.php" method="post">
   	<input type="submit" class="btn orange export" value="Export table to CSV">
		<input type="hidden" value="<?php echo htmlspecialchars($query);?>" name="queryString">
		<input type="hidden" value="reportexport.csv" name="filename">
	</form>

	<?php echo $htmltable; ?>

</div>

<?php echo GetPageFoot("","");?>