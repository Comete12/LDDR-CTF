<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="off-screen-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href='login.php'>Login</a></li>
        </ul>
    </div>

    <nav>
        <span class="burger-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </nav>
    <?php
    session_start();

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
    }

    if($_SESSION['redirection']){
        echo "<h2 style='text-align: center;'>Please Login !</h2>";
    }

    if(isset($_POST['submit'])){
        extract($_POST);
        if($email != "" && $password != ""){
            $req = $conn->prepare("SELECT * FROM users WHERE email = :email AND password= :password");
            $req->execute(['email' => $email, 'password' => $password]);
            $rep = $req->fetch();
            if($rep){
                $points = $rep['points'];
                $username = $rep['username'];
                $_SESSION['points'] = $points;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                if($_SESSION['redirection']){
                    echo "<script>window.location.href = '" . $_SESSION['redirection'] . "';</script>";
                } else{
                    echo "<script>window.location.href = 'index.php';</script>";
                }
                exit;

            }else{
                $error_msg = "E-mail or password incorect !";
            }
        
        }
    }
    ?>
    <div class="login-page">
        <div class="login">
            <form method="POST" action="">
                <div class="login-form">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Enter your e-mail..." required>
                    <br>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password..." required>
                    <br>
                    <?php
                        if($error_msg){
                    ?>
                    <p><?php echo $error_msg; ?></p>
                    <?php
                        }
                    ?>    
                </div>
                
                <div class="login-submit">
                    <input type="submit" value="Submit" name="submit">
                    <p>No account yet ? <a href="signin.php">Sign in</a></p>
                </div>
                
            </form>
        </div>
        
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