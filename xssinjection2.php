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
                    $_SESSION['redirection']= 'xssinjection2.php';
                    echo "<script>window.location.href = 'login.php';</script>";
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
    setcookie("PASSWORD", "2v7vTZ4B1utlzfK");
    
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){ 
        echo "Erreur :". $e->getMessage();
    }

    $admin = isset($_GET['username']) ? $_GET['username'] : '';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $username = $_GET['username'];
        $password = $_GET['password'];
        if ($password && $username != "") {
            $req = $conn->prepare("SELECT * FROM xssinjection2 WHERE username = :username AND password = :password");
            $req->execute(['username' => $username, 'password' => $password]);
            $rep = $req->fetch();
    
            if ($rep) {
                $level = 8;
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
                $error_msg = "<p>Sorry {$admin}, username or password incorrect !</p>";
            }
        }
    }
    
    ?>

    <h2 class="title">Level 8 - XSS Injection</h2>
    <p class="title">Try to log as admin !</p>
    <form method="GET" action="">
        <label for="username">Username</label>
        <select name="username" id="username" required>
            <option value="">--Please choose--</option>
            <option value="manager">manager</option>
            <option value="admin">admin</option>
            <option value="John">John</option>
        </select>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password..." required>
        <br>
        <input type="submit" value="Log in" name="submit">
    </form>

    <?php
    if($error_msg){
        ?>
        <p><?php echo $error_msg; ?></p>
        <?php
    }
    ?>
    <div class="doc">
        <p>Download the documentation here : </p><button onclick="window.open('documentation_7&8.pdf')"><img src="download.png" width="30px"></button>
    </div>
    

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