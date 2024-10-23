<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDDR-CTF</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="wrapper">
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
                        echo "<li><a href='login.php'>Login</a></li>";
                    }
                ?>
            </div>
                
        </nav>

        <h1>Welcome to LDDR - CTF !</h1>

        <div class="main">
            <div id="leaderboard" class="leaderboard">
                <form method="POST" action="">
                    <input type="text" id="username" name="username" placeholder="Enter a username..." required>
                    <input type="submit" value="Display" name="submit">
                </form>
                <table class="leadtable"> 
                    <tr> 
                        <td style="font-weight: bold;">Rank</td>
                        <td style="font-weight: bold;">Username</td>
                        <td><img src="skull.png" width="35px" height="35px"></td> 
                    </tr>
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
                        }

                        if($_SERVER["REQUEST_METHOD"] == "POST"){
                            $username = $_POST['username'];
                            if($username != ""){
                                $query = "SELECT rank, username, points FROM users WHERE username='$username'";
                                $stmt = $conn->query($query);
                
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    foreach ($row as $key => $value) {
                                        echo "<td style='background-color: red;'>{$value}</td>";
                                    }
                                    echo "</tr>";
                                }}}

                        $stmt = $conn->query("SELECT username, points FROM users ORDER BY points DESC");
                        $rank = 1;

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $username = $row['username'];
                            $updateStmt = $conn->prepare("UPDATE users SET rank = :rank WHERE username = :username");
                            $updateStmt->execute(['rank' => $rank, 'username' => $username]);
                            if($rank<=10){
                                echo "<tr><td>{$rank}</td>";
                                foreach ($row as $key => $value) {
                                    echo "<td>{$value}</td>";
                                }
                                echo "</tr>";
                            }
                            $rank+=1;
                        }


                    ?>    
                </table>
            </div>
            <table class="lvltable">
                <tr>
                    <td id="lvl1">
                        <div class="lvl-td">
                            <h3>Level 1 - Source Code</h3>
                            <button onclick="window.location.href='sourcecode.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_1.png">
                                <p>5
                                    <?php
                                    $level = 1;
                                    $email = $_SESSION['email'];
                                    $stmt = $conn->prepare("SELECT * FROM completed_levels WHERE email = :email AND level = :level");
                                    $stmt->execute(['email' => $email, 'level' => $level]);
                        
                                    if ($stmt->rowCount() == 0) {
                                        echo "<img src='skull.png'>";
                                    }else{
                                        echo "<img src='skull2.png'><script>document.getElementById('lvl1').style='border-bottom:2px solid green;'</script>";
                                    }
                                    ?>
                                    </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl2">
                        <div class="lvl-td">
                            <h3>Level 2 - Source Code</h3>
                            <button onclick="window.location.href='sourcecode2.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_1.png">
                                <p>5
                                    <?php
                                    $level = 2;
                                    $email = $_SESSION['email'];
                                    $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                    $stmt = $conn->prepare($checkLevelQuery);
                                    $stmt->execute(['email' => $email, 'level' => $level]);
                        
                                    if ($stmt->rowCount() == 0) {
                                        echo "<img src='skull.png'>";
                                    }else{
                                        echo "<img src='skull2.png'><script>document.getElementById('lvl2').style='border-bottom:2px solid green;'</script>";
                                    }
                                    ?>
                                    </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl3">
                        <div class="lvl-td">
                            <h3>Level 3 - Obfuscation</h3>
                            <button onclick="window.location.href='obfuscation.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_2.png">
                                <p>10
                                <?php
                                $level = 3;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl3').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="lvl4">
                        <div class="lvl-td">
                            <h3>Level 4 - SQL Injection</h3>
                            <button onclick="window.location.href='sqlinjection.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_3.png">
                                <p>15
                                <?php
                                $level = 4;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl4').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl5">
                        <div class="lvl-td">
                            <h3>Level 5 - SQL Injection</h3>
                            <button onclick="window.location.href='sqlinjection2.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_3.png">
                                <p>15
                                <?php
                                $level = 5;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl5').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl6">
                        <div class="lvl-td">
                        <h3>Level 6 - SQL Injection</h3>
                            <button onclick="window.location.href='sqlinjection3.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_4.png">
                                <p>20
                                <?php
                                $level = 6;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl6').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                <td id="lvl7">
                        <div class="lvl-td">
                        <h3>Level 7 - XSS Injection</h3>
                            <button onclick="window.location.href='xssinjection.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_4.png">
                                <p>20
                                <?php
                                $level = 7;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl7').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl8">
                        <div class="lvl-td">
                        <h3>Level 8 - XSS Injection</h3>
                            <button onclick="window.location.href='xssinjection2.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_5.png">
                                <p>25
                                <?php
                                $level = 8;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl8').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td id="lvl9">
                        <div class="lvl-td">
                        <h3>Level 9 - XSS Injection</h3>
                            <button onclick="window.location.href='xssinjection3.php'" class="play">PLAY</button>
                            <div class="difficulty">
                                <img src="difficulty_5.png">
                                <p>25
                                <?php
                                $level = 9;
                                $email = $_SESSION['email'];
                                $checkLevelQuery = "SELECT * FROM completed_levels WHERE email = :email AND level = :level";
                                $stmt = $conn->prepare($checkLevelQuery);
                                $stmt->execute(['email' => $email, 'level' => $level]);
                    
                                if ($stmt->rowCount() == 0) {
                                    echo "<img src='skull.png'>";
                                }else{
                                    echo "<img src='skull2.png'><script>document.getElementById('lvl9').style='border-bottom:2px solid green;'</script>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 2px solid gray;">
                        <div class="lvl-td">
                            <h2>Coming soon...</h2>
                        </div>
                    </td>
                    <td style="border-bottom: 2px solid gray;">
                        <div class="lvl-td">
                            <h2>Coming soon...</h2>
                        </div>
                    </td>
                    <td style="border-bottom: 2px solid gray;">
                        <div class="lvl-td">
                            <h2>Coming soon...</h2>
                        </div>
                    </td>
                </tr>
            </table>
    </div>
    <footer>
        <p>TM Yohann 2024</p>
        <p>Download files <a href="https://github.com/Comete12/LDDR-CTF">here</a>.</p>
    </footer>
    
    <script>
        const burgerIcon = document.querySelector(".burger-icon");
        const offScreenMenu = document.querySelector(".off-screen-menu");

        burgerIcon.addEventListener("click", () => {
            burgerIcon.classList.toggle("active");
            offScreenMenu.classList.toggle("active");
        });
    </script>
</body>
</html>