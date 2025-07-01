<?php
session_start();
require_once('classes/Countries.php');

$class_countries = new Countries();
$countries = $class_countries->all_countries();

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