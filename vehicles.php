

<?php
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$pdo = $con->getPdo(); // Make sure to get the PDO instance

$res = [];

// Create a new vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $license_plate = $_POST["license_plate"] ?? null;
    $make = $_POST["make"] ?? null;
    $model = $_POST["model"] ?? null;

    $errorMessage = "";

    if ($user_id === null) {
        $errorMessage .= " user_id,";
    }
    if ($license_plate === null) {
        $errorMessage .= " license_plate,";
    }
    if ($make === null) {
        $errorMessage .= " make,";
    }
    if ($model === null) {
        $errorMessage .= " model,";
    }
    if ($user_id !== null && $license_plate !== null && $make !== null && $model !== null) {
        try {
            $stmt = $pdo->prepare('INSERT INTO vehicles (user_id, license_plate, make, model) VALUES (?, ?, ?, ?)');
            $stmt->execute([$user_id, $license_plate, $make, $model]);
            $res["result"] = true;
            $res["data"] = "Successfully added";
        } catch (PDOException $e) {
            $res["result"] = false;
            $res["message"] = "Error: " . $e->getMessage();
        }
    } else {
        $res["result"] = false;
        $res["message"] = $errorMessage . " parameter is missing";
    }
    echo json_encode($res);
    exit;
}

// Get all vehicles
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    $stmt = $pdo->query('SELECT vehicle_id, user_id, license_plate, make, model FROM vehicles');
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($vehicles);
    exit;
}

// Get a single vehicle by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT vehicle_id, user_id, license_plate, make, model FROM vehicles WHERE vehicle_id = ?');
    $stmt->execute([$_GET['id']]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($vehicle);
    exit;
}
//update 
if ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {

    $input = file_get_contents('php://input'); // returns string

    parse_str($input, $_UPDATE);

    $user_id = $_UPDATE['user_id'];
    $license_plate = $_UPDATE['license_plate'];
    $make = $_UPDATE['make'];
    $model = $_UPDATE['model'];
    $con->insertvehicles($user_id,$license_plate,$make,$model);    
    $response["result"]="Success";
    http_response_code(201);
   

} else {
    $response["result"] = "Error Only Get Allow";
    
}
echo json_encode($response);

// Delete a vehicle by ID
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    $input = file_get_contents('php://input'); // returns string

    parse_str($input, $_DELETE);

    $vehicle_id = $_DELETE['vehicle_id'];

    

    $res=$con->deletevehicles($vehicle_id);    
    $response["result"]="Success";

} else {
    $response["result"] = "Error Only DELETE Allow";
}

echo json_encode($response);
?>

