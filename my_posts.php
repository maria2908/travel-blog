<?php
session_start();
require_once('classes/Posts.php');
require_once('helpers.php');

if (!isLoggedIn()) {
    header("Location: login.php");
    $_SESSION['message'] = 'You are not authorized. Please log in first to continue.';
    $_SESSION['type_alert'] = 'error';
    exit;
} else {
    $class_posts = new Posts();
    if (!empty($_SESSION['user_id']) && $_SESSION['user_id']) {
        $posts = $class_posts->my_posts($_SESSION['user_id']);
    }
}

require_once('partials/head.php');
require_once('partials/header.php');
?>


<div class="posts">
    <h1><a href="posts.php">My Posts</a></h1>

    <section class="cards-wrapper">
        <?php foreach ($posts as $post): ?>
            <form method="post" action="post.php" class="card-grid-space" style="all: unset;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post->id) ?>">
                <input type="hidden" name="title" value="<?php echo htmlspecialchars($post->title) ?>">
                <input type="hidden" name="text" value="<?php echo htmlspecialchars($post->text) ?>">
                <input type="hidden" name="img" value="<?php echo htmlspecialchars($post->img) ?>">
                <input type="hidden" name="label" value="<?php echo htmlspecialchars($post->label) ?>">

                <button type="submit" class="card" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./<?php echo $post->img ?>')">
                    <div>
                        <h1><?php echo $post->title ?></h1>
                        <p><?php if (strlen($post->text) > 70): ?>
                                <?php echo substr($post->text, 0, 70) . "..." ?>
                            <?php else: ?>
                                <?php echo $post->text ?>
                            <?php endif; ?></p>
                        <div class="tags">
                            <div class="tag"><?php echo $post->country ?></div>
                            <div class="tag"><?php echo $post->topic ?></div>
                        </div>
                    </div>
                </button>
            </form>
        <?php endforeach ?>
    </section>
</div>