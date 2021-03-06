<?php
class Tieredoriginationratemaster extends AppModel {
	var $name = 'Tieredoriginationratemaster';
	var $useTable = 'tieredoriginationratemaster';
	var $primaryKey = 'rowid';
	var $actsAs = array('ImportCsv');
	var $validate = array(
		'effectivedate' =>
					array(
							'valid-date' => array(
								'rule' => 'anyDate',
								'required' => true,
								'allowEmpty' => false,
								'last' => true,
								'message' => 'Must be a valid date field'
							),
							'unique' => array(
								'rule'=>array('checkUnique', array('customerid', 'effectivedate','tier')), 
								'message'=>'A Tier/Effective Date pair already exists for that customer' 
							)
					)
	);
	
	function loadtype($line, $type = null){
		$data = str_getcsv($line);
		$item['customerid'] = $type;
		$item['effectivedate'] = $data[0];
		$item['tier'] = $data[1];
		$item['retailrate'] = $data[2];
		
		return $item;
	}
}
