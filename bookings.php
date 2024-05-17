<?php
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['spot_id'], $_POST['vehicle_id'], $_POST['booking_start'])) {
    $user_id = $_POST['user_id'];
    $spot_id = $_POST['spot_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $booking_start = $_POST['booking_start'];
    $booking_end = $_POST['booking_end'] ?? null;

    try {
        $con->insertBooking($user_id, $spot_id, $vehicle_id, $booking_start, $booking_end);
        $response["result"] = true;
        $response["data"] = "Successfully added booking";
    } catch (PDOException $e) {
        $response["result"] = false;
        $response["message"] = "Error: " . $e->getMessage();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['booking_id'])) {
    $bookings = $con->getBookings();
    echo json_encode($bookings);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $booking = $con->getBookingById($booking_id);
    echo json_encode($booking);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {
    $input = file_get_contents('php://input'); // returns string
    parse_str($input, $_UPDATE);

    $booking_id = $_UPDATE['booking_id'] ?? null;
    $user_id = $_UPDATE['user_id'] ?? null;
    $spot_id = $_UPDATE['spot_id'] ?? null;
    $vehicle_id = $_UPDATE['vehicle_id'] ?? null;
    $booking_start = $_UPDATE['booking_start'] ?? null;
    $booking_end = $_UPDATE['booking_end'] ?? null;

    if ($booking_id !== null && $user_id !== null && $spot_id !== null && $vehicle_id !== null && $booking_start !== null) {
        try {
            $con->updateBooking($booking_id, $user_id, $spot_id, $vehicle_id, $booking_start, $booking_end);
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

    $booking_id = $_DELETE['booking_id'] ?? null;

    if ($booking_id !== null) {
        try {
            $con->deleteBooking($booking_id);
            $response["result"] = "Success";
        } catch (PDOException $e) {
            $response["result"] = false;
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "Error: Missing booking_id";
    }
} else {
    $response["result"] = "Error: Invalid request";
}

echo json_encode($response);
?>
