<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include("db.php");

$con = new Connection();
$con->connect();
$pdo = $con->getPdo(); 
$res = [];

//insert
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["username"] ?? null;
    $email = $_POST["email"] ?? null;
    $pass = $_POST["password"] ?? null;

    $errorMessage = "";

    if ($name === null) {
        $errorMessage .= " name,";
    }
    if ($email === null) {
        $errorMessage .= " email,";
    }
    if ($pass === null) {
        $errorMessage .= " password,";
    }

    if ($name !== null && $email !== null && $pass !== null) {
        try {
            $con->insertRec($name, $email, $pass);
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
} else {
    $res["data"] = "Allow only POST";
}
echo json_encode($res);

// Get all user
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['user_id'])) {
    $stmt = $pdo->query('SELECT user_id,username, email, password FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
    exit;
}

// Get a single user by ID
// if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
//     $stmt = $pdo->prepare('SELECT username, email, passsword FROM users WHERE user_id = ?');
//     $stmt->execute([$_GET['user_id']]);
//     $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
//     echo json_encode($vehicle);
//     exit;
// }
    
//update 
if ($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH") {

    $input = file_get_contents('php://input'); // returns string

    parse_str($input, $_UPDATE);

    $user_id = $_UPDATE['user_id'];
    $username = $_UPDATE['username'];
    $email = $_UPDATE['email'];
    $password = $_UPDATE['password'];
    $con->updateuser($username,$email,$password,$user_id,);    
    $response["result"]="Success";
    http_response_code(201);
   

} else {
    $response["result"] = "Error Only Get Allow";
    
}
echo json_encode($response);

// Delete a user by ID
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    $input = file_get_contents('php://input'); // returns string

    parse_str($input, $_DELETE);

    $user_id = $_DELETE['user_id'];

    

    $res=$con->deleteuser($user_id);    
    $response["result"]="Success";

} else {
    $response["result"] = "Error Only DELETE Allow";
}

echo json_encode($response);

?>
