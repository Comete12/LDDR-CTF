<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection</title>
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
                        $_SESSION['redirection']= 'sqlinjection2.php';
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
    
    try{
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){ 
        echo "Erreur :". $e->getMessage();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $password = $_POST['password'];
        if($password != ""){
            $req = $conn->query("SELECT * FROM sqlinjection WHERE username ='admin' AND password='$password'");
            $rep = $req->fetch();
            if($rep){
                $level = 5;
                $email = $_SESSION['email'];
                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                $stmt = $conn->prepare($checkLevelQuery);
                $stmt->execute(['email' => $email, 'level' => $level]);

                if ($stmt->rowCount() == 0) {
                    $sql = "UPDATE users SET points = points + 15 WHERE email = :email";
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
            }else{
                $error_msg = "Password incorrect !";
            }
        
        }
    }
    ?>
    <h2 class="title">Level 5 - SQL Injection</h2>
    <p class="title">Welcome back admin !</p>
    <form method="POST" action="">
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
        <p>Download the documentation here : </p><button onclick="window.open('documentation_5.pdf')"><img src="download.png" width="30px"></button>
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