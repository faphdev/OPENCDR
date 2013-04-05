<?php
$currency = $siteconfiguration['currency'];
$currencySettings = $siteconfiguration['currencysettings'];
$maxTableHeight = 30;
$numberOfBlankSpaces = $maxTableHeight - count($nontaxdetails) - count($taxdetails) - 2;

$companyName = $companyinfo['companyname'];
$companyAddress1 = $companyinfo['address1'];
$companyAddress2 = $companyinfo['address2'];
$companyCity = $companyinfo['city'];
$companyState = $companyinfo['state'];
$companyPostal = $companyinfo['postal'];
$companyCountry = $companyinfo['country'];

$customerName = $billingaddress['Customer']['customername'];
$customerAddressLine1 = $billingaddress['Customerbillingaddressmaster']['address1'];
$customerAddressLine2 = $billingaddress['Customerbillingaddressmaster']['address2'];
$city = $billingaddress['Customerbillingaddressmaster']['city'];
$stateorprov = $billingaddress['Customerbillingaddressmaster']['stateorprov'];
$country = $billingaddress['Customerbillingaddressmaster']['country'];
$zipcode = $billingaddress['Customerbillingaddressmaster']['zipcode'];
$customerEmail = '';

$accountNumber = $customerid;
$invoiceNumber = $billingbatchmaster['Billingbatchmaster']['billingbatchid'] . '-' . $customerid;
$startPeriod = $billingdetailsummaries[0]['periodstartdate'];
$endPeriod = $billingdetailsummaries[0]['periodenddate'];
$invoiceDate =$billingbatchmaster['Billingbatchmaster']['billingdate'];
$dueDate=$billingbatchmaster['Billingbatchmaster']['duedate'];

$serviceperiod = '';
if(!empty($startPeriod) && !empty($endPeriod)){
	$serviceperiod = $startPeriod . ' to ' . $endPeriod;
}
?>
<html>
<head>
<style type="text/css">
table,td,th{border: 1px solid black;}
table{ border-collapse: collapse;}
.noborder {border: 0px solid white;}
th {background-color:#999999; align:center}
tr.top td { border-bottom-width: 0px; }
tr.middle td{ border-top-width: 0px;
				border-bottom-width: 0px;}
tr.bottom td { border-top-width: 0px; }
</style>
</head>
<body style="font-family:Arial, Gadget, sans-serif">

<h2>Invoice</h2><p>
<div style="float:top; margin:10px; width:900px;">
	<div style="float:left;">
		<b>Remit To: </b><br>
		<?php 
			if(empty($companyName) && empty($companyAddress1) && empty($companyAddress2) && empty($companyCountry) && empty($companyCity)
			&& empty($companyState)&& empty($companyPostal)){
				echo $this->Html->link( 'Setup your company address' ,array( 'controller' => 'Siteconfigurations'));
			}
			else{
				echo $companyName.'<br>';
				echo $companyAddress1.'<br>';
				echo $companyAddress2.'<br>';
				echo $companyCity . ' ' .$companyState .'<br>';
				echo $companyPostal.'<br>';
				echo $companyCountry.'<br>';
			}
		?>
	</div>
	
	<div style="float:right;">
		<?php echo $this->Html->image('company_logo.png', array('alt' => 'Put invoice logo in /app/webroot/img/company_logo.png'))?>
	</div>
	<div style="clear:both;">
	<hr color="#333333">
	</div>
</div>

<div style="clear:both; width:900px; margin:50px 0px 0px 0px;">
	<div style="float:left;">
		<?php 
			if( empty($customerAddressLine1) && empty($customerAddressLine2) 
				&& empty($city)&& empty($stateorprov)&& empty($zipcode)){
				echo $this->Html->link( 'Edit your customer billing address' ,array( 'controller' => 'Customers', 'action' => 'view', $customerid));
			}
			else{
				echo $customerName.'<br>';
				echo $customerAddressLine1.'<br>';
				echo $customerAddressLine2.'<br>';
				echo $city . ', ' . $stateorprov .'<br>';
				echo $zipcode.'<br>';
			}
		?>
	</div>
	
	<div style="float:right; width:500px;">
		<div style="float:left;">
		Account Number:<br>
		Invoice Number:<br>
		Service Period:<br>
		Invoice Date:<br>
		Due Date:<br>
		</div>
		<div style="text-align:right; float:right;">
		<?php echo $accountNumber;?><br>
		<?php echo $invoiceNumber;?><br>
		<?php echo $serviceperiod;?><br>
		<?php echo $invoiceDate;?><br>
		<?php echo $dueDate;?>
		</div>
	</div>
</div>

<table width=900>
<tr>
	<th>Date</th>
	<th>Quantity</th>
	<th style="width:250px">Billing Period</th>
	<th style="width:300px">Description</th>
	<th>Amount</th>
</tr>
<?php 
$subtotal = 0;
foreach($nontaxdetails as $item){
	$billingperiod = '';
	if(!empty($item['Billingbatchdetail']["periodstartdate"]) && !empty($item['Billingbatchdetail']["periodenddate"])){
		$billingperiod = 		$item['Billingbatchdetail']["periodstartdate"] 
								. ' to ' 
								. $item['Billingbatchdetail']["periodenddate"];
	}
	echo '<tr class="middle">';
	echo '<td align="center">'. $item['Billingbatchdetail']["periodenddate"] .'</td>';
	echo '<td align="center">'. $item['Billingbatchdetail']["lineitemquantity"] .'</td>';
	echo '<td align="center">'. $billingperiod .'</td>';
	echo '<td>'. $item['Billingbatchdetail']["lineitemdesc"] .'</td>';
	echo '<td align="right">'. $this->Number->currency($item['Billingbatchdetail']["lineitemamount"],$currency, $currencySettings) .'</td>';
	echo '</tr>';
	
	$subtotal += $item['Billingbatchdetail']["lineitemamount"];
}
?>
<?php
for($i = 0 ; $i < $numberOfBlankSpaces ; $i++){
	echo '<tr class="middle">
	<td><br></td><td></td><td></td><td></td><td></td>
	</tr>';
}
?>
<tr class="middle">
	<td></td><td></td><td></td><td>Subtotal</td><td align="right">
	<?php 
		echo $this->Number->currency($subtotal,$currency,$currencySettings);
	?></td>
</tr>
<?php 
$total = 0;
foreach($taxdetails as $item){
	echo '<tr class="middle">';
	echo '<td></td>';
	echo '<td align="center"></td>';
	echo '<td></td>';
	echo '<td>'. $item['Billingbatchdetail']["lineitemdesc"] .'</td>';
	echo '<td align="right">'. $this->Number->currency($item['Billingbatchdetail']["lineitemamount"],$currency,$currencySettings) .'</td>';
	echo '</tr>';
	$total += $item['Billingbatchdetail']["lineitemamount"];
}
$total += $subtotal;
?>
<tr class="top">
	<td></td><td></td><td></td><td>Total</td><td align="right"><?php echo $this->Number->currency($total, $currency,$currencySettings);?></td>
</tr>
</table>

</body>
</html>