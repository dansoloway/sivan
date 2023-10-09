<?php

require('inc/class.pdowrapper.php');
// require('inc/class.pdohelper.php');
//db
$dbCreds = array(
    'user' => 'root',
    'password' => 'solaR3625',
    'database' => 'sivan',
    'host' => 'localhost',
);
$dbConfig = array(
    "host"=>$dbCreds['host'],
    "dbname"=>$dbCreds['database'],
    "username"=>$dbCreds['user'],
    "password"=>$dbCreds['password']
);

$db = new PdoWrapper($dbConfig);
$db->setErrorLog(true);



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Initialize an empty array to store the values
    $dataArray = [];

    // Retrieve and sanitize the values from the POST request
    $question = isset($_POST["question"]) ? htmlspecialchars($_POST["question"]) : "";
    $media = isset($_POST["media"]) ? htmlspecialchars($_POST["media"]) : "";
    $type = isset($_POST["type"]) ? htmlspecialchars($_POST["type"]) : "";
    $leads_to = isset($_POST["leads_to"]) ? intval($_POST["leads_to"]) : 0; // Convert to integer

    // Add the values to the array
    $dataArray["question"] = $question;
    $dataArray["media"] = $media;
    $dataArray["type"] = $type;
    $dataArray["leads_to"] = $leads_to;

    // Output the array for testing purposes (remove this in production)
    //print_r($dataArray);
    $db_table = 'questions';
    $insert = $db->insert($db_table, $dataArray)->getLastInsertId();

    echo $insert;

} else {
    // Redirect to the form page if accessed directly without a POST request
    //header("Location: your-form-page.php"); // Replace with the actual form page URL
    die("oops");
}
?>
