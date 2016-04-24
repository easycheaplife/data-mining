<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 koudai.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
ini_set('memory_limit', '1024M');
require_once "../jieba-php/src/vendor/multi-array/MultiArray.php";
require_once "../jieba-php/src/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once "../jieba-php/src/class/Jieba.php";
require_once "../jieba-php/src/class/Finalseg.php";
require_once "../jieba-php/src/class/Posseg.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Posseg;

Jieba::init();
Finalseg::init();
Posseg::init();

$dbh = new PDO('mysql:host=127.0.0.1;dbname=youyi', "youyi", "youyi@123"); 

function getAllDisease(){
	global $dbh;
    $sth = $dbh->prepare('SELECT id, doctorIntro FROM  tblDoctor order by id limit 30000');
    $sth->execute();
	$res = array();
	$fileName = './data/intro.dat';
	$stream = fopen($fileName, "a+");
	$fileColumn = 10000/*count(file($fileName))*/;
	fwrite($stream, $fileColumn . "\n");
	while ($row = $sth->fetch(PDO::FETCH_ASSOC) ){
		$stream = fopen($fileName, "a+");
		$pattern = ('/[\n\r\t]/i'); 
		$pattern = ('~[^\p{Han}]~u'); 
		$row['doctorIntro'] = trim($row['doctorIntro']);
		$row['doctorIntro'] = preg_replace($pattern,' ',$row['doctorIntro']); 
		echo strlen($row['doctorIntro']).PHP_EOL;
		$content = '';
		$contentArray = array();
		if(0 != strlen($row['doctorIntro'])){
			$seg_list = Posseg::cut($row['doctorIntro']);
			foreach($seg_list as $val){
				if(
					$val['tag'] != 'd' &&	//	副词
					$val['tag'] != 'b' &&	//	区别词
					$val['tag'] != 'm' &&	//	数词
					$val['tag'] != 'v' &&	//	动词
					$val['tag'] != 'vn' &&	//	名动词
					$val['tag'] != 't' &&	//	时间词
					$val['tag'] != 'u' &&	//	助词
					$val['tag'] != 'uj' &&	//	助词
					$val['tag'] != 'ul' &&	//	助词
					$val['tag'] != 'c' &&	//	连词
					$val['tag'] != 'p'		//	介词

				){
					if(
						$val['word'] != '男' &&
						$val['word'] != '人' &&
						$val['word'] != '女'
					)
					{
						array_push($contentArray,$val['word']);
					}
					$contentArray = array_flip(array_flip($contentArray));
				}
			}
			$content = implode($contentArray,' ');
			//var_dump($seg_list);
			echo $content.PHP_EOL;
			fwrite($stream, $content . "\n");
		}
		$res[] = $row;
	}	
}		

getAllDisease();
//test();
?>
