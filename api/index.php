<?php
$calificationsJSON = '[
    {"id": 1, "student": "Robert Smith", "calification": 5},
    {"id": 2, "student": "Jane Atkinson", "calification": 6},
    {"id": 3, "student": "Mary Johnson", "calification": 7}
]';

// Instantiate the class responsible for implementing a micro application
$app = new \Phalcon\Mvc\Micro();

// Define routes
$app->get('/calification', 'getAllCalifications');
$app->get('/calification/{id}', 'getCalification');

// Route handler functions
function sendJSONResponse($status, $result) {
    header('Content-Type: application/json');
    http_response_code($status);
    $jsonResponse = array('result' => $result);
    echo json_encode($jsonResponse);
}

function getAllCalifications() {
    global $calificationsJSON;
    $califications = json_decode($calificationsJSON);
    $result = [];

    foreach ($califications as $calification) {
        $calificationData = array(
            'id' => $calification->id,
            'student' => $calification->student,
            'calification' => $calification->calification
        );
        array_push($result, $calificationData);
    }

    if (count($result)) {
        sendJSONResponse(200, $result);
        return 1;
    } else {
        sendJSONResponse(204, array('message' => 'No data'));
        return 0;
    }
}

function getCalification($id) {
    global $calificationsJSON;
    $califications = json_decode($calificationsJSON);

    foreach ($califications as $calification) {
        if ($calification->id == $id) {
            sendJSONResponse(200, array(
                'student' => $calification->student,
                'calification' => $calification->calification
            ));
            return 1;
        }
    }

    sendJSONResponse(204, array('message' => 'No data'));
    return 0;
}

$app->notFound('notFound');

function notFound() {
    sendJSONResponse(401, array('message' => 'Not found'));
    return 0;
}

// Handle the request
$app->handle();
?>
