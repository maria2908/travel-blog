<?php
session_start();
require_once('partials/head.php');
require_once('partials/header.php');
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

?>


<div class="posts">
    <div class="container">
        <img class="post_img" src="<?php echo $post->img ?>" />
        <h1><?php echo $post->title ?></h1>
        <p><?php echo $post->text ?></p>
    </div>
</div>