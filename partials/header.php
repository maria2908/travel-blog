<?php
require_once('helpers.php');

$currentPage = basename($_SERVER['PHP_SELF']);

$navItems = [
    'index.php'     => 'Main',
    'posts.php'     => 'Posts',
    'countries.php' => 'Countries',
];

if (isLoggedIn()) {
    $navItems['my_posts.php'] = 'My posts';
}

function navItem(string $page, string $label, string $currentPage): string {
    $active = $page === $currentPage ? 'active' : '';
    return "<li class=\"$active\"><a href=\"$page\">$label</a></li>";
}
?>

<header>
    <nav class="header">
        <ul class="list menu">
            <?php foreach ($navItems as $file => $label) {
                echo navItem($file, $label, $currentPage);
            }?>
        </ul>

        <ul class="list log">
            <?php if (isLoggedIn()): ?>
                <?php if ($currentPage !== 'add_post.php'): ?>
                    <a href="add_post.php" class="add_post">
                        <p>Add New Post</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32v144H48c-17.7 0-32 14.3-32 32s14.3 32 32 32h144v144c0 17.7 14.3 32 32 32s32-14.3 32-32V288h144c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </a>
                <?php endif; ?>

                <li><a href="logout.php">Logout</a></li>
                <a class="profile" href="profile_edit.php">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#ffffff" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512h388.6c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                    </svg>
                </a>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Sign up</a></li>
            <?php endif; ?>


        </ul>
    </nav>
</header>
