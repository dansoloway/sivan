<?php
require_once('config.php');
require_once('header.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected key and value from the POST data
    $choice_key = isset($_POST["choice_key"]) ? $_POST["choice_key"] : "";
    $choice_value = isset($_POST["choice_value"]) ? $_POST["choice_value"] : "";
    $question_id = isset($_POST["question_id"]) ? $_POST["question_id"] : "";
    $question_title = isset($_POST["question_title"]) ? $_POST["question_title"] : "";

    
    // dummy values
    $user_id = 1;
    $user_product_id = 1;
    // Perform any processing or database operations with the selected key and value here

    //clean the key. question1 --> 1
    $cleaned_q_key = explode("question", $question_id);
    $clean_key = $cleaned_q_key[1];
    

    $return_values = array();

    // is this an insert or update? see if the relevant row is already in the DB
    $check_dupe_values = array(
        'user_id' => $user_id,
        'user_product_id' => $user_product_id,
        'question_key' => $clean_key,
    );

    $check_dupe = $db->select('user_products', '', $check_dupe_values)->results();
    
    // insert or update
    if($check_dupe){  // update if there is a dupe
        // update
        $update_values = array('choice_value' => $choice_value, 'choice_key' => $choice_key);
        $update_q = $db->update('user_products', $update_values, $check_dupe_values)->affectedRows();
        //echo "UPDATED" . $update_q; 
        $return_values['status'] = 'updated';   
    } 
    else{
        $insert_values = array(   // insert if no dupe
            'user_id' => $user_id,
            'user_product_id' => $user_product_id,
            'question_key' =>  $clean_key,
            'question_key_title' => $question_title,
            'choice_key' => $choice_key,
            'choice_value' => $choice_value,
        );
        //var_dump($insert_values);
        $insert_now = $db->insert("user_products", $insert_values)->getLastInsertId();
        $return_values['status'] = 'inserted';
    }
    
    // find the new question
    $where_next_q = array(
        'choice_id' => $choice_key
    );
    $next_q = $db->select('choices', 'leads_to', $where_next_q)->results();
    //var_dump($next_q[0]);

    //echo $next_q[0]['leads_to'];
    $return_values['leads_to'] = $next_q[0]['leads_to'];

    $json_response = json_encode($return_values);

    // // Send the response back to the client
    // $response = "Selected Key: $selectedKey, Selected Value: $selectedValue, ID: $selectBoxId";
    // echo $response;
    echo $json_response;
    
} else {
    // Handle cases where the request method is not POST
    http_response_code(400); // Bad Request
    echo "There was a problem.";
}
?>