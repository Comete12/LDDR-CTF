<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDDR-CTF - Source Code</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="off-screen-menu">
        <ul>
            <li><a href="index.php">Home<img class="off-screen-img" src="home.png"></a></li>
                <?php
                session_start();
                if(isset($_SESSION['username'])) {
                    echo 
                        "<li><a href='account.php'>Account<img class='off-screen-img' src='account.png'></a></li>
                        <li><a href='logout.php'>Logout<img class='off-screen-img' src='logout.png'></a></li>";
                } else {
                    echo "<li><a href='login.php'>Login<img class='off-screen-img' src='login.png'></a></li>";
                }
                ?>
        </ul>
    </div>

    <nav>
        <span class="burger-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
        <div class="nav-points">
            <?php
                session_start();
                if(isset($_SESSION['username'])) {
                    echo 
                        "<li><img src='skull.png' height='35px' width='35px'>". (isset($_SESSION['points']) ? $_SESSION['points'] : '') ."</li>";
                } else {
                    $_SESSION['redirection']= 'xssinjection3.php';
                    echo "<script>window.location.href = 'login.php';</script>";
                }
            ?>
        </div>
            
    </nav>

    <h2 class="title">This level is not available online. Please refer to the TM for more information.</h2>
    <h3 class="title">Welcome back admin</h3>
    <form action="" method="POST">
        <label for="password">Password : </label>
        <input name="password"></input><br>
        <input type="submit" value="Envoyer" name="submit_password">
    </form>

    <h2 class="title">Post a message</h2>
    <form action="" method="POST">
        <textarea name="message"></textarea><br>
        <input type="submit" value="Envoyer" name="submit_message">
    </form>

    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['submit_password'])){
            $password = $_POST['password'];
            if ($password != "") {
                $req = $conn->prepare("SELECT * FROM xssinjection3 WHERE username = 'admin' AND password = :password");
                $req->execute(['password' => $password]);
                $rep = $req->fetch();

                if ($rep) {
                    $level = 9;
                    $email = $_SESSION['email'];
                    $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                    $stmt = $conn->prepare($checkLevelQuery);
                    $stmt->execute(['email' => $email, 'level' => $level]);

                    if ($stmt->rowCount() == 0) {
                        $sql = "UPDATE users SET points = points + 25 WHERE email = :email";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(['email' => $email]);

                        $pointsQuery = "SELECT points FROM users WHERE email = :email";
                        $stmt = $conn->prepare($pointsQuery);
                        $stmt->execute(['email' => $email]);
                        $pointsResult = $stmt->fetch();
                        if ($pointsResult) {
                            $_SESSION['points'] = $pointsResult['points'];
                        }

                        $insertLevelQuery = "INSERT INTO completed_levels (email, level) VALUES (:email, :level)";
                        $stmt = $conn->prepare($insertLevelQuery);
                        $stmt->execute(['email' => $email, 'level' => $level]);

                        echo "
                        <script>
                            alert('You got it!');
                            window.location.href = 'index.php';
                        </script>";
                    } else {
                        echo "
                        <script>
                            alert('You have already completed this level.');
                            window.location.href = 'index.php';
                        </script>";
                    }
                } else {
                    $error_msg = "Password incorrect!";
                }
            }
        } 
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['submit_message'])){
            $message=$_POST['message'];
            $query = $conn->prepare("INSERT INTO `messages`(`message`, `is_read_by_admin`) VALUES (:message,0)");
            $query->execute(['message' => $message]);
            $isreadQuery = $conn->prepare("SELECT is_read_by_admin FROM messages WHERE message = :message");
            $isreadQuery->execute(['message' => $message]);
            $isreadQuery = $isreadQuery->fetch();
            while($isreadQuery['is_read_by_admin'] == 0){
                sleep(1);
                $isreadQuery = $conn->prepare("SELECT is_read_by_admin FROM messages WHERE message = :message");
                $isreadQuery->execute(['message' => $message]);
                $isreadQuery = $isreadQuery->fetch();
            }
            echo "read by admin !   ";
            $deleteQuery = $conn->prepare("DELETE FROM messages WHERE message = :message");
            $deleteQuery->execute(['message' => $message]);
        }
    }

    if (isset($_GET['cookie'])) {
        $cookie = $_GET['cookie'];
        $query = $conn->prepare("INSERT INTO cookies (cookie, username) VALUES (:cookie,'admin')");
        $query->execute(['cookie' => $cookie]);
    } else{
        $query = $conn->prepare("SELECT * FROM cookies");
        $query->execute();
        $cookies = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($cookies)){
            foreach ($cookies as $cookie) {
                echo "{$cookie['cookie']}";
                $deleteCookies = $conn->prepare("DELETE FROM cookies WHERE username = 'admin'");
                $deleteCookies->execute();
            }
        }
    }
    
?>

    <script>
        const hamMenu = document.querySelector(".burger-icon");
        const offScreenMenu = document.querySelector(".off-screen-menu");

        hamMenu.addEventListener("click", () => {
            hamMenu.classList.toggle("active");
            offScreenMenu.classList.toggle("active");
        });
        
    </script>
</body>
</html>