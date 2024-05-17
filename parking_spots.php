<?php
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['level_id'], $_POST['spot_number'])) {
    $level_id = $_POST['level_id'];
    $spot_number = $_POST['spot_number'];
    $is_occupied = $_POST['is_occupied'] ?? false;

    try {
        $con->insertParkingSpot($level_id, $spot_number, $is_occupied);
        $response["result"] = true;
        $response["data"] = "Successfully added parking spot";
    } catch (PDOException $e) {
        $response["result"] = false;
        $response["message"] = "Error: " . $e->getMessage();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['spot_id'])) {
    $parking_spots = $con->getParkingSpots();
    echo json_encode($parking_spots);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['spot_id'])) {
    $spot_id = $_GET['spot_id'];
    $parking_spot = $con->getParkingSpotById($spot_id);
    echo json_encode($parking_spot);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {
    $input = file_get_contents('php://input'); // returns string
    parse_str($input, $_UPDATE);

    $spot_id = $_UPDATE['spot_id'] ?? null;
    $level_id = $_UPDATE['level_id'] ?? null;
    $spot_number = $_UPDATE['spot_number'] ?? null;
    $is_occupied = $_UPDATE['is_occupied'] ?? null;

    if ($spot_id !== null && $level_id !== null && $spot_number !== null && $is_occupied !== null) {
        try {
            $con->updateParkingSpot($spot_id, $level_id, $spot_number, $is_occupied);
            $response["result"] = "Success";
            http_response_code(200);
        } catch (PDOException $e) {
            $response["result"] = false;
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "Error: Missing parameter(s)";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $input = file_get_contents('php://input'); // returns string
    parse_str($input, $_DELETE);

    $spot_id = $_DELETE['spot_id'] ?? null;

    if ($spot_id !== null) {
        try {
            $con->deleteParkingSpot($spot_id);
            $response["result"] = "Success";
        } catch (PDOException $e) {
            $response["result"] = false;
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "Error: Missing spot_id";
    }
} else {
    $response["result"] = "Error: Invalid request";
}

echo json_encode($response);
?>
