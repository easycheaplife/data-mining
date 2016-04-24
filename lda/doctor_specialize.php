<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 koudai.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
$dbh = new PDO('mysql:host=127.0.0.1;dbname=youyi', "youyi", "youyi@123"); 
function getAllDisease(){
	global $dbh;
    $sth = $dbh->prepare('SELECT specialize FROM  tblDoctor limit 100000');
    $sth->execute();
	$res = array();
	$fileName = './data/specialize.dat';
	$stream = fopen($fileName, "a+");
	$fileColumn = 10000/*count(file($fileName))*/;
	fwrite($stream, $fileColumn . "\n");
	while ($row = $sth->fetch(PDO::FETCH_ASSOC) ){
		$stream = fopen($fileName, "a+");
		$pattern = ('/[\n\r\t]/i'); 
		$pattern = ('~[^\p{Han}]~u'); 
		$row['specialize'] = trim($row['specialize']);
		$row['specialize'] = preg_replace($pattern,' ',$row['specialize']); 
		echo strlen($row['specialize']) . '---' . $row['specialize'] .PHP_EOL;
		if(0 != strlen($row['specialize']) && "   " != $row['specialize'] ){
			fwrite($stream, $row['specialize'] . "\n");
		}
		$res[] = $row;
	}	
}		

getAllDisease();
//test();
?>
