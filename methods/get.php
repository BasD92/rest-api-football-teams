<?php

// Show collection
if(!isset($_GET['id']) && $_SERVER["HTTP_ACCEPT"] == 'application/json') {
    // Get all results
    $result = mysqli_query($con, "SELECT * FROM team");

    // Get all rows
    $allRows = mysqli_num_rows($result);

    // Create array rows
    $rows = array();

    // Create array for fetched results
    $json = array();

    // When user set a start and limit in url
    if (isset($_GET['start']) && isset($_GET['limit'])) {

        // Set offset to 1 when user type a number less then 1
        if ($_GET['start'] < 1) {
            $offset = 1;
        }

        else {
            // Start variable
            $offset = $_GET['start'] - 1;
        }

        // limit variable
        $limit = $_GET['limit'];

        // Get data with limit and offset
        $result = mysqli_query($con, "SELECT * FROM team ORDER BY id LIMIT $limit OFFSET $offset");

        // Get rows
        $limitRows = mysqli_num_rows($result);

        // Get all pages (Return next highest integer value)
        $allPages = ceil($allRows / $limitRows);

        // Get current page (Return next highest integer value)
        $currentPage = ceil($_GET['start'] / $limit);

        // Get previous page
        $previousPage = $currentPage - 1;

        // Set previous page to 1 when currentpage is already 1
        if ($previousPage < 1) {
            $previousPage = 1;
        }

        // Get next page
        $nextPage = $currentPage + 1;

        // Get start previous page
        $subtractPreviousPage = $previousPage - 1;
        $startPreviousPage = $subtractPreviousPage * $limitRows + 1;

        // Get start next page
        $subtractNextPage = $nextPage - 1;
        $startNextPage = $subtractNextPage * $limitRows + 1;

        // Get start last page
        $subtractLastPage = $allPages - 1;
        $startLastPage = $subtractLastPage * $limitRows + 1;

        // Fetch results from db, give key names and store in arrays
        while ($row = mysqli_fetch_assoc($result)) {
            $json['id'] = $row['id'];
            $json['name'] = $row['name'];
            $json['nickname'] = $row['nickname'];
            $json['city'] = $row['city'];

            // Link to detail resource and collection
            $json['_links'] = array('self' => array('href' => 'http://localhost/Restful_api/footballteams/' . $row['id']),
                'collection'=>array('href' => 'http://localhost/Restful_api/footballteams/'));

            // Store fetched results with key names from db in rows[] array
            $rows[] = $json;
        }
        header("Content-Type: application/json");

        // Bundle arrays
        $data = array('items' => $rows, '_links' => array('self' => array('href' => 'http://localhost/Restful_api/footballteams/')),
            'pagination' => array('currentPage' => $currentPage,
                'currentItems' => $limitRows,
                'totalPages' => $allPages,
                'totalItems' => $allRows,
                '_links' => array('first' => array('page' => 1,
                    'href' => 'http://localhost/Restful_api/footballteams/?start=1&limit=' . $limitRows),
                    'last' => array('page' => $allPages,
                        'href' => 'http://localhost/Restful_api/footballteams/?start=' . $startLastPage . '&limit=' . $limitRows),
                    'previous' => array('page' => $previousPage,
                        'href' => 'http://localhost/Restful_api/footballteams/?start=' . $startPreviousPage . '&limit=' . $limitRows),
                    'next' => array('page' => $nextPage,
                        'href' => 'http://localhost/Restful_api/footballteams/?start=' . $startNextPage . '&limit=' . $limitRows))));

        // Show as JSON object
        echo json_encode($data);

        // OK status
        http_response_code(200);
    }

    // When user dont set a start and limit in url (Show all items)
    else {
        // Fetch results from db, give key names and store in arrays
        while ($row = mysqli_fetch_assoc($result)) {
            $json['id'] = $row['id'];
            $json['name'] = $row['name'];
            $json['nickname'] = $row['nickname'];
            $json['city'] = $row['city'];

            // Link to detail resource and collection
            $json['_links'] = array('self' => array('href' => 'http://localhost/Restful_api/footballteams/' . $row['id']),
                'collection'=>array('href' => 'http://localhost/Restful_api/footballteams/'));

            // Store fetched results with key names from db in rows[] array
            $rows[] = $json;
        }

        header("Content-Type: application/json");

        // Bundle arrays
        $data = array('items' => $rows, '_links' => array('self' => array('href' => 'http://localhost/Restful_api/footballteams/')),
            'pagination' => array('currentPage' => 1,
                'currentItems' => $allRows,
                'totalPages' => 1,
                'totalItems' => $allRows,
                '_links' => array('first' => array('page' => 1,
                    'href' => 'http://localhost/Restful_api/footballteams/'),
                    'last' => array('page' => 1,
                        'href' => 'http://localhost/Restful_api/footballteams/'),
                    'previous' => array('page' => 1,
                        'href' => 'http://localhost/Restful_api/footballteams/'),
                    'next' => array('page' => 1,
                        'href' => 'http://localhost/Restful_api/footballteams/'))));

        // Show as JSON object
        echo json_encode($data);

        // OK status
        http_response_code(200);
    }
}

// Show detail resource
elseif (isset($_GET['id']) && $_SERVER["HTTP_ACCEPT"] == 'application/json') {
    $id = $_GET['id'];

    $sql = "SELECT * FROM team WHERE id = $id";

    // Execute SQL statement
    $result = mysqli_query($con, $sql);

    // Check if row exist
    if (mysqli_num_rows($result) == 1) {

        // Create array rows (This time 1 row for detail resource)
        $row1 = array();

        // Create array for fetched results
        $json = array();

        // Fetch results from db, give key names and store in arrays
        while ($row = mysqli_fetch_assoc($result)) {
            $json['id'] = $row['id'];
            $json['name'] = $row['name'];
            $json['nickname'] = $row['nickname'];
            $json['city'] = $row['city'];

            // Link to detail resource and collection
            $json['_links'] = array('self' => array('href' => 'http://localhost/Restful_api/footballteams/' . $row['id']),
                'collection'=>array('href' => 'http://localhost/Restful_api/footballteams/'));

            // Store fetched results with key names from db
            $row1 = $json;
        }

        // Output header
        header('Content-type: application/json');

        // Show as JSON object
        echo json_encode($row1);

        // OK status
        http_response_code(200);
    }

    else {
        // Bad request when row doesnt exist
        http_response_code(400);

        // Output header
        header('Content-type: application/json');
        $message = array('Message' => 'Resource doesnt exist.');
        echo json_encode($message);
    }
}

else {
    // Not acceptable (wrong accept header)
    http_response_code(406);

    // Output header
    header('Content-type: application/json');
    $message = array('Message' => 'Only application/json is allowed as Accept-header');
    echo json_encode($message);
}