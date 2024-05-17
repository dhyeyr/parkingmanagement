<?php
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'], $_POST['amount'])) {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    try {
        $con->insertPayment($booking_id, $amount);
        $response["result"] = true;
        $response["data"] = "Successfully added payment";
    } catch (PDOException $e) {
        $response["result"] = false;
        $response["message"] = "Error: " . $e->getMessage();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['payment_id'])) {
    $payments = $con->getPayments();
    echo json_encode($payments);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $payment = $con->getPaymentById($payment_id);
    echo json_encode($payment);
    exit;
} elseif ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {
    $input = file_get_contents('php://input'); // returns string
    parse_str($input, $_UPDATE);

    $payment_id = $_UPDATE['payment_id'] ?? null;
    $booking_id = $_UPDATE['booking_id'] ?? null;
    $amount = $_UPDATE['amount'] ?? null;

    if ($payment_id !== null && $booking_id !== null && $amount !== null) {
        try {
            $con->updatePayment($payment_id, $booking_id, $amount);
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

    $payment_id = $_DELETE['payment_id'] ?? null;

    if ($payment_id !== null) {
        try {
            $con->deletePayment($payment_id);
            $response["result"] = "Success";
        } catch (PDOException $e) {
            $response["result"] = false;
            $response["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $response["result"] = "Error: Missing payment_id";
    }
} else {
    $response["result"] = "Error: Invalid request";
}

echo json_encode($response);
?>
