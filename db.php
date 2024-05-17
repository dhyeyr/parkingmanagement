

<?php

class Connection {
    private $pdo;

    public function connect() {
        $dsn = "mysql:host=localhost;dbname=parking management"; // Replace 'your_db' with your actual database name
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

     //user query
    public function insertRec($name, $email, $pass) {   
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $password = password_hash($pass, PASSWORD_DEFAULT); // Hashing the password
        $stmt->execute([$name, $email, $password]);
    }

    public function updateUser($username, $email, $password, $user_id) {
        $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $password = password_hash($password, PASSWORD_DEFAULT); // Hashing the password
        $stmt->execute([$username, $email, $password, $user_id]);
    }

    public function deleteuser($user_id) {       
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
    }
    
    public function getPdo() {
        return $this->pdo;
    }

//    vehicles query

    public function insertvehicles($user_id, $license_plate, $make,$model) {         
        $sql = "INSERT INTO vehicles (user_id, license_plate, make, model) VALUES (?, ?, ?,?)";
        $stmt = $this->pdo->prepare($sql);
        // $password = password_hash($pass, PASSWORD_DEFAULT); // Hashing the password
        $stmt->execute([$user_id, $license_plate, $make,$model]);
    }
    function updatevehicles($user_id,$license_plate,$make,$model){
        $this->connect();
        $q= "UPDATE `vehicles` SET `user_id`='$user_id',`license_plate`='$license_plate',`make`='$make', WHERE id=$id";
        
        $res=mysqli_query($this->conn,$q);
        echo $res;
    }

    public function deletevehicles($vehicle_id) {       
        $sql = "DELETE FROM vehicles WHERE vehicle_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$vehicle_id]);
    }

    // parking_level
    public function insertParkingLevel($level_name, $total_spots, $available_spots) {
        $sql = "INSERT INTO parking_levels (level_name, total_spots, available_spots) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$level_name, $total_spots, $available_spots]);
    }

    public function updateParkingLevel($level_id, $level_name, $total_spots, $available_spots) {
        $sql = "UPDATE parking_levels SET level_name = ?, total_spots = ?, available_spots = ? WHERE level_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$level_name, $total_spots, $available_spots, $level_id]);
    }

    public function deleteParkingLevel($level_id) {
        $sql = "DELETE FROM parking_levels WHERE level_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$level_id]);
    }

    public function getParkingLevels() {
        $sql = "SELECT * FROM parking_levels";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParkingLevelById($level_id) {
        $sql = "SELECT * FROM parking_levels WHERE level_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$level_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  // Parking spot operations
  public function insertParkingSpot($level_id, $spot_number, $is_occupied) {
    $sql = "INSERT INTO parking_spots (level_id, spot_number, is_occupied) VALUES (?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$level_id, $spot_number, $is_occupied]);
}

public function updateParkingSpot($spot_id, $level_id, $spot_number, $is_occupied) {
    $sql = "UPDATE parking_spots SET level_id = ?, spot_number = ?, is_occupied = ? WHERE spot_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$level_id, $spot_number, $is_occupied, $spot_id]);
}

public function deleteParkingSpot($spot_id) {
    $sql = "DELETE FROM parking_spots WHERE spot_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$spot_id]);
}

public function getParkingSpots() {
    $sql = "SELECT * FROM parking_spots";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getParkingSpotById($spot_id) {
    $sql = "SELECT * FROM parking_spots WHERE spot_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$spot_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

 // Booking operations
 public function insertBooking($user_id, $spot_id, $vehicle_id, $booking_start, $booking_end = null) {
    $sql = "INSERT INTO bookings (user_id, spot_id, vehicle_id, booking_start, booking_end) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$user_id, $spot_id, $vehicle_id, $booking_start, $booking_end]);
}

public function updateBooking($booking_id, $user_id, $spot_id, $vehicle_id, $booking_start, $booking_end = null) {
    $sql = "UPDATE bookings SET user_id = ?, spot_id = ?, vehicle_id = ?, booking_start = ?, booking_end = ? WHERE booking_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$user_id, $spot_id, $vehicle_id, $booking_start, $booking_end, $booking_id]);
}

public function deleteBooking($booking_id) {
    $sql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$booking_id]);
}

public function getBookings() {
    $sql = "SELECT * FROM bookings";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getBookingById($booking_id) {
    $sql = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$booking_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


  // Payment operations
  public function insertPayment($booking_id, $amount) {
    $sql = "INSERT INTO payments (booking_id, amount) VALUES (?, ?)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$booking_id, $amount]);
}

public function updatePayment($payment_id, $booking_id, $amount) {
    $sql = "UPDATE payments SET booking_id = ?, amount = ? WHERE payment_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$booking_id, $amount, $payment_id]);
}

public function deletePayment($payment_id) {
    $sql = "DELETE FROM payments WHERE payment_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$payment_id]);
}

public function getPayments() {
    $sql = "SELECT * FROM payments";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPaymentById($payment_id) {
    $sql = "SELECT * FROM payments WHERE payment_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$payment_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



}
?>