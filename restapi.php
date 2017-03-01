<?php

// Database connection
require_once('connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Request method variable
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "GET" :

        include 'methods/get.php';

        break;

    case "POST" :

        include 'methods/post.php';

        break;

    case "PUT" :

        include 'methods/put.php';

        break;

    case "DELETE" :

        include 'methods/delete.php';

        break;

    case "HEAD" :

        include 'methods/head.php';

        break;

    case "OPTIONS" :

        include 'methods/options.php';

        break;

    default :
        // Method not allowed
        http_response_code(405);
}

// Close connection
mysqli_close($con);