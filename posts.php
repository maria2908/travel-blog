<?php
session_start();
require_once('classes/Posts.php');

$class_posts = new Posts();

$country = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['country'])) {
    $country = htmlspecialchars($_POST['country']);
} elseif (!empty($_GET['country'])) {
    $country = htmlspecialchars($_GET['country']);
}

if (!empty($country)) {
    $posts = $class_posts->all_posts($country);
} else {
    $posts = $class_posts->all_posts();
}

require_once('partials/head.php');
require_once('partials/header.php');
?>


<div class="posts">
    <div class="posts_header">
        <h1 style="text-decoration: underline;"><a href="posts.php">All Posts</a></h1>
        <form class="form" method="post" action="posts.php">
            <label for="search">
                <input class="input" type="text" name="country" required="" placeholder="Select a country you're interested in" id="search">
                <div class="fancy-bg"></div>
                <div class="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="r-14j79pv r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-4wgw6l r-f727ji r-bnwqim r-1plcrui r-lrvibr">
                        <g>
                            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                        </g>
                    </svg>
                </div>
                <button class="close-btn" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </label>
        </form>
    </div>
    <?php
    require_once('components/post_card.php');
    ?>
</div>