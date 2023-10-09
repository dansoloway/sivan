<?php

require_once('config.php');
require_once('header.php');

// // Get the value of 'q' from the GET request
$q = isset($_GET['q']) ? $_GET['q'] : null;

// Check if 'q' is a valid integer
if (!is_numeric($q)) {
    echo "Invalid parameter 'q'. Please provide a valid question ID.";
    exit();
}

$whereConditions = array('id ='=> $q);
$question_data = $db->select('questions', '', $whereConditions)->results();
//var_dump($question_data);


$whereConditions = array('question_id ='=> $q);
$choices_data = $db->select('choices', '', $whereConditions)->results();



class FormGenerator {
    private $question_id; // Example property

    public function __construct($question_id) {
        // Initialize any properties or setup tasks here
        $this->question_id = $question_id;
    }

    // Method to generate a select box
    public function createSelect($label, $q, $display_values, $system_values) {
        $select_html = "<h1>" . $label . "</h1>";
        $select_html .= '<select id="question'.$q.'" data-q-title="'.$label.'" class="form-select" id="selectBox" aria-label="Default select example">';
        $select_html .= "<option>Please Choose</option>";
        foreach ($display_values as $key => $display_value) {
            $system_value = $system_values[$key];
            
            $selected = ''; // Default not selected     
            $select_html .= "<option value='$system_value' $selected>$display_value</option>";
        }
        
        $select_html .= '</select>';
        
        return $select_html;
    }

    // Method to arrange select data from an array of data
    public function arrangeSelectData($data) {
        $display_values = [];
        $system_values = [];
        
        foreach ($data as $item) {
            if ($item['type'] === 'select_option') {
                $display_values[] = $item['display_value'];
                $system_values[] = $item['choice_id'];
            }
        }
        
        return ['display_values' => $display_values, 'system_values' => $system_values];
    }

    // Method to get the current URL with the 'q' query parameter
    public function getCurrentUrlWithQuestionId() {
        $currentUrl = "http";
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
            $currentUrl .= "s";
        }
        $currentUrl .= "://";
        $currentUrl .= $_SERVER["HTTP_HOST"];
        $currentUrl .= $_SERVER["REQUEST_URI"];

        // Add or update the 'q' query parameter with $this->question_id
        $parsedUrl = parse_url($currentUrl);
        $query = $parsedUrl["query"] ?? "";
        parse_str($query, $queryParams);
        $queryParams["q"] = $this->question_id;

        // Rebuild the query string
        $updatedQuery = http_build_query($queryParams);

        // Reconstruct the URL with the updated query string
        $currentUrl = $parsedUrl["scheme"] . "://" . $parsedUrl["host"] . $parsedUrl["path"];
        if (!empty($updatedQuery)) {
            $currentUrl .= "?" . $updatedQuery;
        }

        return $currentUrl;
    }

}





$formGenerator = new FormGenerator($q); // Initialize the class with a value
$select_data = $formGenerator->arrangeSelectData($choices_data);
$select_label = $question_data[0]["question"];
$select_html = $formGenerator->createSelect($select_label, $q, $select_data['display_values'], $select_data['system_values']);

$currentUrl = $formGenerator->getCurrentUrlWithQuestionId();
echo $currentUrl;

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container">
        <div class="row">
        <div class="col-4">
                <h1>Responses</h1>
                <div id="current_order">

                </div>
  
            </div>

            <div class="col-8">
                <h1>Questions</h1>
                <?php echo $select_html; ?>
            </div>
        </div>
    </div>
   


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function fetchDataAndDisplay() {
    // Get a reference to the HTML container where you want to display the data
    const outputContainer = $("#current_order");

    // Make an AJAX request to fetch JSON data
    $.ajax({
        type: "GET", // You can change this to "POST" if needed
        url: "ajax/show_current_order.php",
        dataType: "json",
        success: function(data) {
            // Iterate through the JSON data and generate HTML
            $.each(data, function(index, item) {
                const paragraph = $("<p>").text(`${item.key}: ${item.value}`);
                outputContainer.append(paragraph);
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching data:", error);
        }
    });
}

$(document).ready(function() {

   

    

    fetchDataAndDisplay();


    $(".form-select").change(function() {
    // Get the ID of the select box
    var question_id = $(this).attr('id');
    var question_title = $(this).attr('data-q-title');
    //alert(questionTitle);

    // Get the selected key and value from the select box
    var choice_key = $(this).val(); // selectedKey is the value of the selected option
    var choice_value = $(this).find('option:selected').text(); // selectedValue is the text of the selected option

    // Create a data object to send via AJAX
    var data = {
        choice_key: choice_key,
        choice_value: choice_value,
        question_id: question_id,
        question_title: question_title,

    };

    // Send the data to update_order.php using AJAX
    $.ajax({
        type: "POST",
        url: "update_order.php",
        data: data,
        dataType: "json", // Specify that you expect JSON as the response
        success: function(response) {
            // Handle the response from the PHP script if needed
            console.log(response);
            location.href = "http://localhost/sivan/display.php?q=" + response.leads_to;
        },
        error: function(xhr, status, error) {
            // Handle any errors that occur during the AJAX request
            console.error(xhr.responseText);
        }
    });
});
   
});
</script>
  </body>

</html>
