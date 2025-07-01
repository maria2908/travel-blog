<?php
require_once('classes/User.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $user = new User();

    if ($user->login($email, $password)) {
        header("Location: index.php");
    } else {
        echo "Registration failed";
    }
}

require_once('partials/head.php');
require_once('alert.php');
?>



<div class="sign-in-up">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="login.php">

        <h3>Login Here</h3>

        <label for="email">Email</label>
        <input type="email" placeholder="Email or Phone" id="email" name="email" id="email">

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" id="password">

        <button>Log In</button>
        <a href="register.php">Sign up</a>
    </form>
</div>