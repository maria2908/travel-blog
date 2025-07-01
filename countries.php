<?php
session_start();
require_once('classes/Countries.php');

if (!isLoggedIn()) {
    header("Location: login.php");
    $_SESSION['message'] = 'You are not authorized. Please log in first to continue.';
    $_SESSION['type_alert'] = 'error';
    exit;
} else {
    $class_countries = new Countries();
    $countries = $class_countries->all_countries();
}

require_once('partials/head.php');
require_once('partials/header.php');
?>

<div class="posts">
    <div class="posts_header">
        <h1>Countries</h1>
        
    </div>
    <?php
    require_once('components/countries_card.php');
    ?>
</div>