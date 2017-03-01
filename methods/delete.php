<?php

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM team WHERE id = $id";

    // Execute SQL statement
    mysqli_query($con, $sql);

    // Check if row exists
    if(mysqli_affected_rows($con) == 1) {
        // Output header
        header('Content-type: application/json');
        $data = array('Message' => 'Resource deleted.');
        echo json_encode($data);

        http_response_code(204);
    }

    // Response when resource doesnt exist
    else {
        http_response_code(400);

        // Output header
        header('Content-type: application/json');
        $data = array('Message' => 'Resource doesnt exist.');
        echo json_encode($data);
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