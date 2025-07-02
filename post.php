<?php
session_start();
require_once('classes/Posts.php');
$class_post = new Posts();

$id = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
} elseif (!empty($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
}


if (!empty($id)) {
    $post = $class_post->one_post($id);
} else {
    echo "Error";
}

require_once('partials/head.php');
require_once('partials/header.php');
?>


<div class="posts">
    <div class="container" style="margin-top: 40px;">
        <a href="posts.php" class="back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#ffffff" d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
        </a>
        <img class="post_img" src="<?php echo $post->img ?>" />
        <h1><?php echo $post->title ?></h1>
        <p><?php echo $post->text ?></p>
    </div>
</div>