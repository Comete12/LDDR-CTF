<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                        echo "<script>window.location.href = 'index.php';</script>";
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

        $req = $conn->prepare("SELECT username, points, rank FROM users WHERE email = :email");
        $req->execute(['email' => $_SESSION['email']]);
        $rep = $req->fetch();
        if($rep){
            echo "<form class='acc' method='POST' action=''>
                    <label for='username'>Username :</label>
                    <input type='text' name='username' value='" . htmlspecialchars($rep['username']) . "'>
                    <input type='submit' value='EDIT'>
                    <p>Rank : " . htmlspecialchars($rep['rank']) . "</p>
                    <p>Points : " . htmlspecialchars($rep['points']) . "</p>
                </form>";
        }
        ?>
        <div class="bdel">
            <p>DELETE ACCOUNT </p><button onclick="window.location.href='deleteaccount.php'">HERE</button>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            if ($username != "") {
                $req = $conn->prepare("UPDATE users SET username = :username WHERE email = :email");
                $req->execute(['username' => $username, 'email' => $_SESSION['email']]);
                header("location:index.php");
            }
        }

        ?>

        <script>
            function $(id) {
                return document.getElementById(id)
            }

            const hamMenu = document.querySelector(".burger-icon");
            const offScreenMenu = document.querySelector(".off-screen-menu");

            hamMenu.addEventListener("click", () => {
                hamMenu.classList.toggle("active");
                offScreenMenu.classList.toggle("active");
            });
    </script>
</body>
</html>