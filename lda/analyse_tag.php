<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 koudai.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 ini_set ('memory_limit', '4096M');
 $file_theta = './data/model-final.theta';
 $file_phi = './data/model-final.phi';
 $file_word_map = './data/wordmap.txt';

 $document_no = 22;
 $content_theta = file_get_contents($file_theta);
 $content_theta_array = explode("\n",$content_theta);
 foreach($content_theta_array as $key=>$val){
    $content_theta_array[$key] = explode(' ',$val);
 }

 $topic_no = 0;
 foreach($content_theta_array[$document_no] as $key=>$val ){
    if($content_theta_array[$document_no][$key] > $content_theta_array[$document_no][$topic_no] )
    {
        $topic_no = $key;
    }
 }
 echo $topic_no.PHP_EOL;

 
 $content_word_map = file_get_contents($file_word_map); 
 $content_word_map_array = explode("\n",$content_word_map);
 foreach($content_word_map_array as $key=>$val){
    $tmp = explode(' ',$val);
    if(!empty($tmp[0]) && !empty($tmp[1]))
    {
        $content_word_map_array[$tmp[1]] = $tmp[0];
    }
 }
 ksort($content_word_map_array);
 print_r(array_slice($content_word_map_array,0,100));


 $word_no = 3;
 $content_phi = file_get_contents($file_phi);
 $content_phi_array = explode("\n",$content_phi);
 foreach($content_phi_array as $key=>$val){
    $content_phi_array[$key] = explode(' ',$val);
 }

 $content_phi_array_tmp = array();
 foreach($content_phi_array[$topic_no] as $k=>$v){
    $content_phi_array_tmp[] = array("k"=>$k,"v"=>$v);
 }  

 $content_phi_array_tmp_new = array_sort($content_phi_array_tmp,'v','desc');
 //print_r(array_slice($content_phi_array[$topic_no],0,10));    
 //print_r(array_slice($content_phi_array_tmp_new,0,10));    

 $top_word_array = array_slice($content_phi_array_tmp_new,0,10);
 foreach($top_word_array as $k=>$v){
    $index = $v['k']; 
    echo $content_word_map_array[$index].PHP_EOL;
 }
 

function array_sort($list,$field,$sortby='asc'){ 
    if(is_array($list)){
         $refer = $resultSet = array();
         foreach ($list as $i => $data)
             $refer[$i] = &$data[$field];
             switch ($sortby) {
                 case 'asc': // 正向排序
					asort($refer);
                    break;
                 case 'desc':// 逆向排序
                    arsort($refer);
					break;
                 case 'nat': // 自然排序
                    natcasesort($refer);
                break;
                }
                 foreach ( $refer as $key=> $val)
                     $resultSet[] = &$list[$key];
                return $resultSet;
             }
}





/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
?>
