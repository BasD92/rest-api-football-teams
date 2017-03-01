<?php

// Check if Content-Type = application/x-www-form-urlencoded
if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
    // Check if fields exists and not empty values
    if (isset($_POST['name']) && isset($_POST['nickname']) && isset($_POST['city']) && !empty($_POST['name'])
        && !empty($_POST['nickname']) && !empty($_POST['city'])) {

        // Get data form
        $name = $_POST['name'];
        $nickname = $_POST['nickname'];
        $city = $_POST['city'];

        // Insert data to db
        $sql = "INSERT INTO team (name, nickname, city) VALUES ('$name', '$nickname', '$city')";

        // Execute SQL statement
        mysqli_query($con, $sql);

        // Check if row affected
        if(mysqli_affected_rows($con) == 1) {
            // Success response
            http_response_code(201);
        }
    }

    else {
        http_response_code(400);

        $message = array("Message" => "Use name, nickname and city to do a POST request and empty values arent allowed.");

        // Output header
        header('Content-type: application/json');
        echo json_encode($message);
    }
}

// Check if Content-Type = application/json
elseif ($_SERVER["CONTENT_TYPE"] == 'application/json') {
    // Read JSON
    $jsonData = file_get_contents("php://input");

    // JSON object converts to php associative array
    $data = json_decode($jsonData, true);

    // Check if keys exists and not empty values
    if (isset($data['name']) && isset($data['nickname']) && isset($data['city']) && !empty($data['name']) &&
        !empty($data['nickname']) && !empty($data['city'])) {

        // Get footballteam data
        $name = $data['name'];
        $nickname = $data['nickname'];
        $city = $data['city'];

        // Insert data to db
        $sql = "INSERT INTO team (name, nickname, city) VALUES ('$name', '$nickname', '$city')";

        // Execute SQL statement
        mysqli_query($con, $sql);

        // Check if row affected
        if(mysqli_affected_rows($con) == 1) {
            // Success response
            http_response_code(201);
        }
    }

    else {
        http_response_code(400);

        $message = array("Use name, nickname and city as keys to do a POST request and give them all a value.",
            "Use the right JSON format. To get the right JSON format, you can do a GET request on a detail resource like",
            "http://localhost/Restful_api/footballteams/1 (use Accept: application/json), copy it, change the values and remove",
            "the _links key and all associated keys and values.");

        // Output header
        header('Content-type: application/json');
        echo json_encode($message);
    }
}

else {
    // Bad request when Content-Type is wrong
    http_response_code(400);

    // Output header
    header('Content-type: application/json');
    $message = array('Message' => 'Wrong Content-Type');
    echo json_encode($message);
}