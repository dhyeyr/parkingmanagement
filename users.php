<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type:application/json");


include ("connection.php");

$con = new Connection();
$con->connect();


$res = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = $_POST["password"];


    $errorMessage = "";

    if ($name == null) {
        $errorMessage = $errorMessage . " name,";
    }
    if ($email == null) {
        $errorMessage = $errorMessage . " email,";
    }
    if ($pass == null) {
        $errorMessage = $errorMessage . " password,";
    }
 

    if ($name != null && $email != null && $pass != null ) {
        $con = new Connection();
        $con->connect();
        $con->insertRec($name, $email, $pass);

        $res["data"] = "Successfully added";
        $usersData = [];
        // $sRec = $con->getStudentDataByType($iby);
        // while ($s = mysqli_fetch_assoc($sRec)) {
        //     array_push($usersData, $s);
        // }
        // $user=$gsbd;
        $res["result"] = true;

        $user["name"] = $name;
        $user["email"] = $email;
        $user["password"] = $pass;

    } else {
        $res["result"] = false;
        $res["message"] = $errorMessage . " parameter is missing";
    }





} else {
    $res["data"] = "Allow only Post";
}

echo json_encode($res);

?>