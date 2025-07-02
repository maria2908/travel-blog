<?php
session_start();
require_once 'helpers.php';
require_once 'classes/Posts.php';
require_once 'classes/Database.php';


$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT posts.id, title, text, posts.img, country, topic FROM posts INNER JOIN country ON posts.country_id = country.id INNER JOIN topic ON posts.topic_id = topic.id  ORDER BY create_date DESC LIMIT 6");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_OBJ);


require_once 'partials/head.php';
require_once 'partials/header.php';
?>

<div style="margin:10px;">

    <div class="start">
        <h1>Share Your Journey</h1>
        <div class="subtitle">
            <div>
                <h2>Have a story to tell?</h2>
                <p class='text'>Share your travel experiences with the community.</p>
                <a href="add_post.php">
                    <button class="button">
                        <p class="button__text">
                            <span style="--index: 0;">A</span>
                            <span style="--index: 1;">D</span>
                            <span style="--index: 2;">D</span>
                            <span style="--index: 3;"> </span>
                            <span style="--index: 4;">P</span>
                            <span style="--index: 5;">O</span>
                            <span style="--index: 6;">S</span>
                            <span style="--index: 7;">T</span>
                            <span style="--index: 8;"> </span>
                            <span style="--index: 9;">A</span>
                            <span style="--index: 10;">D</span>
                            <span style="--index: 11;">D</span>
                            <span style="--index: 12;"> </span>
                            <span style="--index: 13;">P</span>
                            <span style="--index: 14;">O</span>
                            <span style="--index: 15;">S</span>
                            <span style="--index: 16;">T</span>
                        </p>

                        <div class="button__circle">
                            <svg
                                viewBox="0 0 14 15"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                class="button__icon"
                                width="14">
                                <path
                                    d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"
                                    fill="#000000"></path>
                            </svg>

                            <svg
                                viewBox="0 0 14 15"
                                fill="none"
                                width="14"
                                xmlns="http://www.w3.org/2000/svg"
                                class="button__icon button__icon--copy">
                                <path
                                    d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"
                                    fill="#000000"></path>
                            </svg>
                        </div>
                    </button>
                </a>
            </div>

            <div class='main_card'>
                <h2>Fresh Reads This Week</h2>
                <p>Discover new stories, insider tips, and travel hacks to inspire your next adventure.
                Whether you're planning a getaway or just daydreaming, we’ve got you covered.</p>
                <a href="posts.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2 160 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-306.7L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="about">
            <h1>Welcome to Miles & Memories</h1>
            <p>Miles & Memories – Your Passport to Inspiration
                Whether you're planning your next big adventure or just love exploring the world from your screen, you've landed in the right place.

                Miles & Memories is a travel blog dedicated to sharing real stories, hidden gems, and cultural highlights from across Europe and beyond. From cozy villages in Romania to sun-drenched islands in Greece, we bring you authentic travel experiences, photo guides, and personal tips — all in one beautifully curated space.

                Browse our latest posts, explore destinations by country, or get inspired for your next escape.
                Let’s travel smarter, slower, and with more wonder. </p>
        </div>
        <div>
            <h1>Posts</h1>
            <div class="cards-wrapper">
                <?php foreach ($posts as $post): ?>
                    <?php include 'components/card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<?php
require_once('footer.php');
?>