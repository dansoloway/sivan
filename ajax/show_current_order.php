<?php
require_once('../config.php');
require_once('../header.php');


 // dummy values
 $user_id = 1;
 $user_product_id = 1;

 // is this an insert or update? see if the relevant row is already in the DB
 $show_current_order = array(
    'user_id' => $user_id,
    'user_product_id' => $user_product_id,
);

$current_order = $db->select('user_products', '', $show_current_order, 'ORDER BY question_key')->results();

if($current_order){
    $ret_order = array();
    foreach($current_order as $prod){
        $local_array = array();
        $local_array['key'] = $prod['question_key_title'];
        $local_array['value'] = $prod['choice_value'];
        $ret_order[] = $local_array;
    }
    

    $ret = json_encode($ret_order);
}
else{
    $ret_array = array(
        'response' => 'no exist',
    );
    $ret = json_encode($ret_array);
}
echo $ret;