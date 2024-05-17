<?php
require 'db.php';

// Create a new vehicle
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $data->user_id;
    $license_plate = $data->license_plate;
    $make = $data->make;
    $model = $data->model;
    
    $stmt = $pdo->prepare('INSERT INTO vehicles (user_id, license_plate, make, model) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$user_id, $license_plate, $make, $model])) {
        echo json_encode(['message' => 'Vehicle created']);
    } else {
        echo json_encode(['message' => 'Error creating vehicle']);
    }
}

// Get all vehicles
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    $stmt = $pdo->query('SELECT vehicle_id, user_id, license_plate, make, model FROM vehicles');
    $vehicles = $stmt->fetchAll();
    echo json_encode($vehicles);
}

// Get a single vehicle by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT vehicle_id, user_id, license_plate, make, model FROM vehicles WHERE vehicle_id = ?');
    $stmt->execute([$_GET['id']]);
    $vehicle = $stmt->fetch();
    echo json_encode($vehicle);
}

// Update a vehicle by ID
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id'])) {
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $data->user_id;
    $license_plate = $data->license_plate;
    $make = $data->make;
    $model = $data->model;
    
    $stmt = $pdo->prepare('UPDATE vehicles SET user_id = ?, license_plate = ?, make = ?, model = ? WHERE vehicle_id = ?');
    if ($stmt->execute([$user_id, $license_plate, $make, $model, $_GET['id']])) {
        echo json_encode(['message' => 'Vehicle updated']);
    } else {
        echo json_encode(['message' => 'Error updating vehicle']);
    }
}

// Delete a vehicle by ID
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('DELETE FROM vehicles WHERE vehicle_id = ?');
    if ($stmt->execute([$_GET['id']])) {
        echo json_encode(['message' => 'Vehicle deleted']);
    } else {
        echo json_encode(['message' => 'Error deleting vehicle']);
    }
}
?>
