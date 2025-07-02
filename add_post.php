<?php
session_start();
require_once('classes/Posts.php');
require_once('classes/Countries.php');
require_once('classes/Topics.php');
require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance('cloudinary://233295889131347:c4EhYIk_BO1v1Lc7d1WSHZBZ--U@ducl1lqi0?secure=true');

$country_class = new Countries();
$all_counties = $country_class->all_countries();

$topic_class = new Topics();
$all_topics = $topic_class->all_topics();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $text = $_POST['text'];
    $user_id = $_SESSION['user_id'];
    $country_id = $_POST['country_id'];
    $topic_id = $_POST['topic_id'];
    $create_date = date('Y-m-d H:i:s');

    $img = null;

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {

        $tmp_path = $_FILES['featured_image']['tmp_name'];
        $original_name = pathinfo($_FILES['featured_image']['name'], PATHINFO_FILENAME);

        try {
            $upload = new UploadApi();
            $result = $upload->upload($tmp_path, [
                'public_id' => $original_name,
                'use_filename' => true,
                'overwrite' => true
            ]);

            $img = $result['secure_url']; 
        } catch (Exception $e) {
            $_SESSION['message'] = "Image upload failed: " . $e->getMessage();
            $_SESSION['type_alert'] = 'error';
            header('Location: add_post.php');
            exit;
        }
    }

    $post_class = new Posts();

    if ($post_class->create($title, $text, $img, $user_id, $country_id, $topic_id, $create_date)) {
        $_SESSION['message'] = "Post created successfully.";
        $_SESSION['type_alert'] = 'success';

        header('Location: my_posts.php');
        exit;
    } else {
        $_SESSION['message'] = "Something went wrong.";
        $_SESSION['type_alert'] = 'error';
    }

    header('Location: add_post.php');
    exit;
}

require_once('partials/head.php');
require_once('alert.php');
require_once('partials/header.php');
?>

<div class="new_post">
    <div class="sign-in-up add_classs">
        <div class="background">
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <form method="POST" enctype="multipart/form-data" action="add_post.php">
            <h3>Add New Post</h3>

            <label for="title">Title</label>
            <input type="text" placeholder="Please enter post title" id="title" name="title" id="title" required>
            <?php if (!empty($emailError)): ?>
                <div style="color: red; font-size: 10px; margin-top: 5px;"><?= $emailError ?></div>
            <?php endif ?>

            <div class="selects">
                <div>
                    <label for="country" id="country">Country</label>
                    <select name="country_id" required>
                        <option value="" disabled selected hidden>Select an Country</option>
                        <?php foreach ($all_counties as $country): ?>
                            <option value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="topic" id="topic">Topic</label>
                    <select name="topic_id" required>
                        <option value="" disabled selected hidden>Select an Topic</option>
                        <?php foreach ($all_topics as $topic): ?>
                            <option value="<?php echo $topic->id ?>"><?php echo $topic->topic ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <label for="text">Text</label>
            <textarea class="my-textarea" type="text" placeholder="Please enter post text" id="textarea" name="text" required></textarea>

            <input name="featured_image" type="file" class="browse-button" id="featured_image">
       
            <button type="submit">Add Post</button>
        </form>
    </div>
</div>