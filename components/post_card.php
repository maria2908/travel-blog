<?php
require_once('classes/Posts.php');
require_once('classes/User.php');

$user_id = $_SESSION['user_id'] ?? null;
$posts_class = new Posts();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'], $_POST['post_id']))
{
    $post_id = (int) $_POST['post_id'];

    if (!$posts_class->userLiked($user_id, $post_id)) {
        $posts_class->likePost($user_id, $post_id);
    } else {
        $posts_class->unlikePost($user_id, $post_id);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<section class="cards-wrapper">
    <?php foreach ($posts as $post): ?>
        <div>
            <form method="post" action="post.php" class="card-grid-space" style="all: unset;">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post->id) ?>">
                <input type="hidden" name="title" value="<?php echo htmlspecialchars($post->title) ?>">
                <input type="hidden" name="text" value="<?php echo htmlspecialchars($post->text) ?>">
                <input type="hidden" name="img" value="<?php echo htmlspecialchars($post->img) ?>">
                <input type="hidden" name="country" value="<?php echo htmlspecialchars($post->country) ?>">

                <button type="submit" class="card" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo $post->img ?>')">
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
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="heart">
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" style="display:flex; align-items:center;">
                        <input type="hidden" name="post_id" value="<?php echo $post->id ?>">
                        <span><?php echo $posts_class->getLikeCount($post->id); ?></span>
                        <button type="submit" name="like" value="1" style="background: none; border: none; cursor: pointer; padding-left: 3px">
                            <?php if ($posts_class->userLiked($user_id, $post->id)): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#b41d1d" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40" height="40"><path fill="#d6d6d6" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach ?>
</section>