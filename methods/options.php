<?php

// Detail resource
if (isset($_GET['id'])) {
    header("Allow: GET, PUT, DELETE, HEAD, OPTIONS");

    // OK status
    http_response_code(200);
}

// Collection
else {
    header("Allow: GET, HEAD, POST, OPTIONS");

    // OK status
    http_response_code(200);
}