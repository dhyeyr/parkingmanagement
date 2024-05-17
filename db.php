

<?php

 class Connection{
    public $host="localhost";
    public $pass="";
    public $username="root";
    public $dbname="parking management";
    public $conn;

    function connect(){
        $c=mysqli_connect($this->host,$this->username,$this->pass,$this->dbname);

        if($c){
            $this->conn=$c;
        }else{
            echo "Error";
        }
    }

    function insertuser($fullname,$email,$password){
        $this->connect();
    
        $q= "INSERT INTO `users`(`username`, `email`, `password`) VALUES ('$fullname','$email','$password')";
        $res=mysqli_query($this->conn,$q);
        echo $res;
    }

    function updateRec($id,$fullname,$email,$password,$number,$mark){
        $this->connect();
        $q= "UPDATE `student` SET `fulll_name`='$fullname',`email`='$email',`password`='$password',`number`='$number',`marks`='$mark' WHERE id=$id";
        
        $res=mysqli_query($this->conn,$q);
        echo $res;
    }

    function login($email){
        $this->connect();
        $q= "select * from student where email='$email'";
        // echo $q;
        $res=mysqli_query($this->conn,$q);

        return $res;
    }

    function deleteRec($id){
        $this->connect();
    
        $q= "delete from student where id=$id";
    
        $res=mysqli_query($this->conn,$q);
        return $res;
        
    }

    function getStudentData($id){
        $this->connect();
        $q="select * from student where id=$id";
        $res=mysqli_query($this->conn,$q);
        return $res;
    }

    function getStudentDataByType($insertby){
        $this->connect();
        $q="select * from student where insert_by=$insertby";
       
        $res=mysqli_query($this->conn,$q);
        return $res;
    }

    function getData(){
        $this->connect();

        $q= "SELECT * From student";
    
        $res=mysqli_query($this->conn,$q);
        return $res;
    }

 }

?>