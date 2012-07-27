<?php
	include_once 'config.php';
	include_once $path . 'lib/Page.php';
	include_once $path . 'conf/ConfigurationManager.php';
	$manager = new ConfigurationManager();
	$connectstring = $manager->BuildConnectionString();

 if (isset($_POST['submit'])) {

     $customerid = pg_escape_string($_POST['customerid']);
     $customername = pg_escape_string($_POST['customername']);
     $lrn = pg_escape_string($_POST['lrn']);
     $cnam = pg_escape_string($_POST['cnam']);
     $calltype = pg_escape_string($_POST['calltype']);
     $bcycle = pg_escape_string($_POST['bcycle']);
	 $customertype = pg_escape_string($_POST['customertype']);


	$db = pg_connect($connectstring);
        if (!$db) {
         die("Error in connection: " . pg_last_error());
     }

                                      
     $sql = "update customermaster set customername = '$customername', customertype ='$customertype',lrndiprate = '$lrn', cnamdiprate = '$cnam', indeterminatejurisdictioncalltype = '$calltype', billingcycle = '$bcycle' where customerid = '$customerid';";
     $result = pg_query($db, $sql);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
    
     echo "<body><font color='red'>Data successfully updated!</font></body>";
    
     pg_free_result($result);
    
     pg_close($db);
 }

 else {
	$rowid = $_GET['rowid'];

	$db = pg_connect($connectstring);
        if (!$db) {
         die("Error in connection: " . pg_last_error());
     }
	
     $sql = "select * from customermaster where rowid = '$rowid';";

     $result = pg_query($sql);

     $myrow = pg_fetch_assoc($result);
	
     pg_free_result($result);
    
     pg_close($db);

 }	
 ?>
<head>
<?php echo GetPageHead("Update Customer", "listcustomers.php")?>
</head>
   <div id="body">  
	<table>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="standardform">
      <tr><td><label>CustomerID:</label></td>		<td><input type="text" name="customerid" value="<?php if (isset($_POST['submit'])) { echo $customerid; } else {echo $myrow['customerid'];} ?>" READONLY STYLE="border: 0px"></td></tr>
      <tr><td><label>Customer Name:</label></td>		<td><input type="text" name="customername" value="<?php if (isset($_POST['submit'])) { echo $customername; } else {echo $myrow['customername'];} ?>" ></td></tr>
      <tr><td><label>Customer Type:</label></td><td>
      <select name="customertype">
		  <option value="Wholesale" <?php if (isset($_POST['submit'])) { if ($customertype == "Wholesale") { echo "selected"; }} ?>>Wholesale</option>
		  <option value="Retail" <?php if (isset($_POST['submit'])) { if ($customertype == "Retail") { echo "selected"; }} ?>>Retail</option>
      </select></td></tr>
	  <tr><td><label>LRN Dip Rate:</label></td><td><input type="text" name="lrn" value="<?php if (isset($_POST['submit'])) { echo $lrn; } else {echo $myrow['lrndiprate'];} ?>" ></td></tr>      
      <tr><td><label>CNAM Dip Rate:</label></td><td><input type="text" name="cnam" value="<?php if (isset($_POST['submit'])) { echo $cnam; } else {echo $myrow['cnamdiprate'];} ?>" ></td></tr>
      <tr><td><label>Indeterminate Call Type:</label></td><td>
      <select name="calltype">
      <option value="5" 
		<?php 
			if (isset($_POST['submit'])) { 
				if ($calltype == "5") { 
					echo "selected";  } 
			} else { 
				if ($myrow['indeterminatejurisdictioncalltype'] == "5") {
					 echo "selected";  }
			}
?> >Intrastate</option>
      <option value="10" <?php 
			if (isset($_POST['submit'])) { 
				if ($calltype == "10") { 
					echo "selected";  } 
			} else { 
				if ($myrow['indeterminatejurisdictioncalltype'] == "10") {
					 echo "selected";  }
			}
?>>Interstate</option>
      </select></td></tr>
      <tr><td><label>Billing Cycle:</label></td>		<td><input type="text" name="bcycle" value="<?php if (isset($_POST['submit'])) { echo $bcycle; } else {echo $myrow['billingcycle'];} ?>" ></td></tr>        
      <tr><td><input type="submit" name="submit" value="Save"></td><td></td></tr>
    </form> 
</table>
</div>
   <?php echo GetPageFoot("","");?>