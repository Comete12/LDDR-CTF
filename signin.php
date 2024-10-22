<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
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
    <div class="login-page">
        <div class="login">
             <form method="POST" action="process.php">
                <div class="login-form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username..." required>
                    <br>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Enter your e-mail..." required>
                    <br>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password..." required>
                </div>
                <div class="login-submit">
                    <input type="submit" value="Submit" name="submit">
                    <p>Already have an account ? <a href="login.php">Log in</a></p>
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