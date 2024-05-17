<!-- <?php

// include ("student.php");
include ("db.php");


$connection = new Connection();
$connection->connect();

// $connection->insertRec("hello","abc@gmail.com","1223","123",123);
$res = $connection->getData();

// $data=mysqli_fetch_assoc($res);
// var_dump($data);

if (isset($_REQUEST["btn_add_new"])) {
  echo "btn_add_new click";
  header("Location: success.php");
}


if (isset($_REQUEST["btn-delete"])) {
  $id = $_GET["id"];
  $connection->deleteRec($id);
  header("Location: index.php");
}

$studentRecord = null;
if (isset($_REQUEST["btn-edit"])) {
  $id = $_GET["id"];

  $studentRes = $connection->getStudentData($id);
  $studentRecord=mysqli_fetch_array($studentRes);
  // $connection->insertRec($name, $email, $pass, $number, $mark);
}

if (isset($_REQUEST["btn_add"])) {

  $name = $_GET["fullName"];
  $email = $_GET["email"];
  $pass = $_GET["pass"];
  echo $studentRecord==null? "Add  123 ":"Update ";

  // $connection->insertRec($name, $email, $pass, $number, $mark);
  // header("Location: index.php");

} 


if (isset($_REQUEST["btn_update_record"])) {

  $s_id = $_GET["s_id"];
  $name = $_GET["fullName"];
  $email = $_GET["email"];
  $pass = $_GET["pass"];
  

  $connection->updateRec($s_id,$name, $email, $pass);
  header("Location: index.php");

} 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form id="userForm">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type="button" onclick="submitUserForm()">Submit</button>
    </form>

    <script>
        function submitUserForm() {
            var formData = {
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            fetch('users.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => alert('User added successfully!'))
            .catch(error => alert('Error adding user: ' + error));
        }
    </script>
</body>
</html> -->
