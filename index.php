<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Natrapharm(Sales-Update-Summary)</title>
</head>
<style>
    /* General Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    /* Login Container */
    .login-container {
        background-color: #3d3d3d;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        width: 300px;
    }

    /* Logo */
    .logo img {
        max-width: 120px;
        margin-bottom: 15px;
    }

    /* Input Groups */
    .input-group {
        position: relative;
        margin-bottom: 15px;
    }

    .input-group input {
        width: 80%;
        padding: 12px 40px 12px 10px;
        border: none;
        border-radius: 5px;
        outline: none;
    }

    .icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    /* Login Button */
    .login-btn {
        width: 80%;
        padding: 12px;
        background-color: #d72626;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .login-btn:hover {
        background-color: #b71c1c;
    }
</style>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="natrapharm_logo.png" alt="Natrapharm Logo">
        </div>
        <form action="homepage.php" method="post">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
                unset($_SESSION['error_message']);
            }
            ?>
            <div class="input-group">
                <input type="text" placeholder="User ID" name="username" required>
                <span class="icon">&#128100;</span>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
                <span class="icon">&#128274;</span>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>

</html>