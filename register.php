<?php
require_once('classes/User.php');
require_once('partials/head.php');
require_once('helpers.php');

$passwordValidation = [];
$emailError = '';
$user = new User();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $emailError = "Invalid email format";
    } else {


        $registerResult = $user->register($email, $password);
        if ($registerResult === true) {

            $passwordValidation = validatePassword($password);

            if ($passwordValidation === true) {
                if ($user->register($email, $password)) {
                    header("Location: login.php");
                    exit();
                }
            }
        } else {
            $emailError = $registerResult;
        }
    }
}
?>

<div class="sign-in-up">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="register.php" method="POST">
        <h3>Register Here</h3>

        <label for="email">Email</label>
        <input type="email" placeholder="Email or Phone" id="email" name="email" id="email" required>
        <?php if (!empty($emailError)): ?>
            <div style="color: red; font-size: 10px; margin-top: 5px;"><?= $emailError ?></div>
        <?php endif ?>

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password" id="password" required>
        <?php if (!empty($passwordValidation) && is_array($passwordValidation)): ?>
            <div style="color: red; font-size: 10px; margin-top: 5px;">
                <?php foreach ($passwordValidation as $error): ?>
                    <?= htmlspecialchars($error) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button>Register</button>
    </form>
</div>