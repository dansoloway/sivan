<?php
require_once('config.php');
require_once('header.php');


$dataArray = [
    'White', 'Tan', 'Adobe', 'Black', 'Brownstone',
    'Matte Black', 'Unfinished Pine', 'White Primed Pine',
    'Unfinished Vertical Grain Douglas Fir', 'Bronze Anodized',
    'Clear Anodized', 'Frost', 'Harmony', 'Bark', 'Black Bean'
];

foreach ($dataArray as $value) {
    $data = [
        'question_id' => 2,
        'type' => 'select_option',
        'display_value' => $value,
        'system_value' => $value,
        'leads_to' => 3
    ];
    
    $db_table = 'choices';
    $insert = $db->insert($db_table, $data)->getLastInsertId();

}

?>