<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 koudai.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
ini_set('memory_limit', '1024M');
require_once "./jieba-php/src/vendor/multi-array/MultiArray.php";
require_once "./jieba-php/src/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once "./jieba-php/src/class/Jieba.php";
require_once "./jieba-php/src/class/Finalseg.php";
require_once "./jieba-php/src/class/Posseg.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Posseg;

Jieba::init();
Finalseg::init();
Posseg::init();


function getAllDisease(){
	$res = array();
	$fileName = './sample.txt';
	$handle = @fopen($fileName, "r");
	$fileName = './sample.dat';
	$stream = fopen($fileName, "a+");
	fwrite($stream, "[");
	while (($row = fgets($handle, 4096)) !== false ){
		$stream = fopen($fileName, "a+");
		$pattern = ('/[\n\r\t]/i'); 
		$pattern = ('~[^\p{Han}]~u'); 
		$row = trim($row);
		$row = preg_replace($pattern,' ',$row); 
		echo ($row).PHP_EOL;
		$content = '';
		fwrite($stream, $content . "\n");
		$contentArray = array();
		if(0 != strlen($row)){
			$seg_list = Posseg::cut($row);
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
					array_push($contentArray, '"' . $val['word'] . '"');
					$contentArray = array_flip(array_flip($contentArray));
				}
			}
			$content = implode($contentArray,',');
			$content = '[' . $content . '],';
			//var_dump($seg_list);
			echo $content.PHP_EOL;
			fwrite($stream, $content);
		}
		$res[] = $row;
	}	
}		

getAllDisease();
//test();
?>
