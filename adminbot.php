<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <?php
    $servername ="localhost";
    $username ="DBuser9_62";
    $password ="root";
    $dbname ="DB9db62";
    $error_msg="";
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){ 
        echo "Erreur :". $e->getMessage();
    }

    $query = $conn->prepare("SELECT * FROM messages WHERE is_read_by_admin = 0");
    $query->execute();
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        echo "{$message['message']}";
        $stmt = $conn->prepare("UPDATE messages SET is_read_by_admin = 1 WHERE message = :message");
        $stmt->execute([':message' => $message['message']]);
    }
    ?> 
</body>
</html>
