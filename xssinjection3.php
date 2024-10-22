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

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
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
        echo "read by admin !";
        $deleteQuery = $conn->prepare("DELETE FROM messages WHERE message = :message");
        $deleteQuery->execute(['message' => $message]);
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
                echo "Cookie: {$cookie['cookie']}";
                $deleteCookies = $conn->prepare("DELETE FROM cookies WHERE username = 'admin'");
                $deleteCookies->execute();
            }
        }
    }
    


?>
<h2>Post a message</h2>
    <form action="" method="POST">
        <textarea name="message"></textarea><br>
        <input type="submit" value="Envoyer">
    </form>
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