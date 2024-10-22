<?php

$servername ="localhost";
$username ="DBuser9_62";
$password ="root";
$dbname ="DB9db62";

try{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){ 
    echo "Erreur :". $e->getMessage();
    exit();
}

if(isset($_POST['submit'])){
    extract($_POST);
    
    if (!empty($username) && !empty($email) && !empty($password)) {
        $req = $conn->prepare("INSERT INTO users VALUES (:username, :email, :password, :points, :rank)");
        $req->execute(["username" => $username,"email" => $email, "password"=> $password, "points" => 0, "rank" => 0]);   
        header("Location: login.php");
    }
}
?>