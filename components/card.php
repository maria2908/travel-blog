<section>
    <form method="post" action="post.php" class="card-grid-space" style="all: unset;">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($post->id) ?>">
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($post->title) ?>">
        <input type="hidden" name="text" value="<?php echo htmlspecialchars($post->text) ?>">
        <input type="hidden" name="img" value="<?php echo htmlspecialchars($post->img) ?>">
        <input type="hidden" name="label" value="<?php echo htmlspecialchars($post->country) ?>">

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
</section>
    