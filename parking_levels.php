<?php
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$pdo = $con->getPdo();
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['level_name'], $_POST['total_spots'], $_POST['available_spots'])) {
    $level_name = $_POST['level_name'];
    $total_spots = $_POST['total_spots'];
    $available_spots = $_POST['available_spots'];

    try {
        $con->insertParkingLevel($level_name, $total_spots, $available_spots);
        $response["result"] = true;
        $response["data"] = "Successfully added parking level";
    } catch (PDOException $e) {
        $response["result"] = false;
        $response["message"] = "Error: " . $e->getMessage();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['level_id'])) {
    $parking_levels = $con->getParkingLevels();
    echo json_encode($parking_levels);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['level_id'])) {
    $level_id = $_GET['level_id'];
    $parking_level = $con->getParkingLevelById($level_id);
    echo json_encode($parking_level);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {
    $input = file_get_contents('php://input'); // returns string
    parse_str($input, $_UPDATE);

    $level_id = $_UPDATE['level_id'] ?? null;
    $level_name = $_UPDATE['level_name'] ?? null;
    $total_spots = $_UPDATE['total_spots'] ?? null;
    $available_spots = $_UPDATE['available_spots'] ?? null;

    if ($level_id !== null && $level_name !== null && $total_spots !== null && $available_spots !== null) {
        try {
            $con->updateParkingLevel($level_id, $level_name, $total_spots, $available_spots);
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

    $level_id = $_DELETE['level_id'] ?? null;

    if ($level_id !== null) {
        try {
            $con->deleteParkingLevel($level_id);
            $response["result"] = "Success";
        } catch (PDOException $e) {
            $response["result"] = false;
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "Error: Missing level_id";
    }
} else {
    $response["result"] = "Error: Invalid request";
}

echo json_encode($response);
?>
