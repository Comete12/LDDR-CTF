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
                    $_SESSION['redirection']= 'index.php';
                    echo "<script>window.location.href = 'login.php';</script>";
                }
            ?>
        </div>
            
    </nav>

<h2 class="title">Wait... really ?</h2>
<h3 class="title">Here, take 10 free points.</h3>
<button onclick="window.location.href='rick.mp4'">CLAIM</button>


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