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
            $req = $conn->prepare("DELETE FROM users WHERE email = :email");
            $req->execute(['email' => $_SESSION['email']]);
            $req = $conn->prepare("DELETE FROM completed_levels WHERE email = :email");
            $req->execute(['email' => $_SESSION['email']]);
            session_unset();
            session_destroy();
            header("location:index.php");
        }
        ?>

    <div class="login-page">
        <div class="login">
            <h1>ARE YOU SURE ?</h1>
            <p style="text-align: center; margin: 0;">this will erase all your data</p>
            <div class="del">
                <form method="POST" action="">
                    <input type="submit" value="YES">
                </form>
                <button onclick="window.location.href='account.php'">NO</button>
            </div>
        </div>   
    </div>
        
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