<?php

// Check if Content-Type = application/x-www-form-urlencoded
if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
    // Check if id exists in url
    if (isset($_GET['id'])) {
        // Store value id from url
        $id = $_GET['id'];

        // Read input
        $input = file_get_contents("php://input");

        // Parse string and create array
        parse_str($input, $array);

        // Get all data from db to check if values exists
        $result = mysqli_query($con, "SELECT * FROM team WHERE id = $id");

        // Check if row affected
        if(mysqli_affected_rows($con) == 1) {
            // Check if fields and values exist and empty values arent allowed
            if (array_keys($array)[0] == 'name' && array_keys($array)[1] == 'nickname' &&
                array_keys($array)[2] == 'city' && !empty(array_values($array)[0]) &&
                !empty(array_values($array)[1]) && !empty(array_values($array)[2])) {

                // Check if values exists in db (Its only allowed to change all values with PUT)
                while ($row = mysqli_fetch_array($result)) {
                    if (array_values($array)[0] != $row['name'] && array_values($array)[1] != $row['nickname'] &&
                        array_values($array)[2] != $row['city']) {

                        // Get data array
                        $name = array_values($array)[0];
                        $nickname = array_values($array)[1];
                        $city = array_values($array)[2];

                        $sql = "UPDATE team SET name = '$name', nickname = '$nickname', city = '$city' WHERE team.id = $id"
                        or die(mysqli_error($con));

                        // Execute SQL statement
                        mysqli_query($con, $sql);

                        // Check if row affected
                        if(mysqli_affected_rows($con) == 1) {
                            // Success response
                            http_response_code(200);
                        }
                    }

                    else {
                        // Bad request when Content-Type is wrong
                        http_response_code(400);

                        // Output header
                        header('Content-type: application/json');
                        $message = array('Message' => 'One or more values already exist. Change all the values');
                        echo json_encode($message);
                    }
                }
            }

            else {
                http_response_code(400);

                $message = array("Message" => "Use name, nickname and city as keys to do a PUT request and empty values arent allowed.");

                // Output header
                header('Content-type: application/json');
                echo json_encode($message);
            }
        }

        else {
            http_response_code(400);

            $message = array("Message" => "Id doesnt exist");

            // Output header
            header('Content-type: application/json');
            echo json_encode($message);
        }
    }

    // Response when user didnt type an id
    else {
        http_response_code(400);

        // Output header
        header('Content-type: application/json');
        $message = array('Message' => 'Type an existing id.');
        echo json_encode($message);
    }
}

// Check if Content-Type = application/json
elseif ($_SERVER["CONTENT_TYPE"] == 'application/json') {
    // Check if id exists in url
    if (isset($_GET['id'])) {
        // Store value id from url
        $id = $_GET['id'];

        // Read JSON
        $jsonData = file_get_contents("php://input");

        // JSON object converts to php associative array
        $data = json_decode($jsonData, true);

        // Get all data from db to check if values exists
        $result = mysqli_query($con, "SELECT * FROM team WHERE id = $id");

        // Check if row affected
        if(mysqli_affected_rows($con) == 1) {
            // Check if keys exists and not empty values
            if (isset($data['name']) && isset($data['nickname']) && isset($data['city']) && !empty($data['name']) &&
                !empty($data['nickname']) && !empty($data['city'])) {

                // Check if values exists in db (Its only allowed to change all values with PUT)
                while ($row = mysqli_fetch_array($result)) {
                    if ($data['name'] != $row['name'] && $data['nickname'] != $row['nickname'] &&
                        $data['city'] != $row['city']) {

                        // Get footballteam data
                        $name = $data['name'];
                        $nickname = $data['nickname'];
                        $city = $data['city'];

                        $sql = "UPDATE team SET name = '$name', nickname = '$nickname', city = '$city' WHERE team.id = $id"
                        or die(mysqli_error($con));

                        // Execute SQL statement
                        mysqli_query($con, $sql);

                        // Check if row affected
                        if(mysqli_affected_rows($con) == 1) {
                            // Success response
                            http_response_code(200);
                        }
                    }

                    else {
                        // Bad request when Content-Type is wrong
                        http_response_code(400);

                        // Output header
                        header('Content-type: application/json');
                        $message = array('Message' => 'One or more values already exist. Change all the values');
                        echo json_encode($message);
                    }
                }
            }

            else {
                http_response_code(400);

                $message = array("Use name, nickname and city as keys to do a PUT request and give them all a value.",
                    "Use the right JSON format. To get the right JSON format, you can do a GET request on a detail resource like",
                    "http://localhost/Restful_api/footballteams/1 (use Accept: application/json), copy it, change the values and remove",
                    "the _links key and all associated keys and values.");

                // Output header
                header('Content-type: application/json');
                echo json_encode($message);
            }
        }

        else {
            http_response_code(400);

            $message = array("Message" => "Id doesnt exist");

            // Output header
            header('Content-type: application/json');
            echo json_encode($message);
        }
    }

    // Response when user didnt type an id
    else {
        http_response_code(400);

        // Output header
        header('Content-type: application/json');
        $message = array('Message' => 'Type an existing id.');
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