<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 koudai.com, Inc. All Rights Reserved
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

$seg_list = Jieba::cut("怜香惜玉也得要看对象啊！");
var_dump($seg_list);

$seg_list = jieba::cut("我来到北京清华大学", true);
print "Full Mode:". "/ " . join(" ",$seg_list).PHP_EOL; //  全模式

$seg_list = jieba::cut("我来到北京清华大学", false);
print "Default Mode:". "/ " .join(" ",$seg_list).PHP_EOL; //  默認模式

$seg_list = jieba::cut("他来到了网易杭研大厦");
print join(" ",$seg_list).PHP_EOL;

$seg_list = Posseg::cut("这是一个伸手不见五指的黑夜。我叫孙悟空，我爱北京，我爱Python和C++。");
var_dump($seg_list);



/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
?>
