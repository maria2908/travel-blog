<?php
session_start();
require_once('partials/head.php');
require_once('partials/header.php');
require_once('classes/Posts.php');
require_once('classes/Countries.php');
require_once('classes/Topics.php');
require_once('alert.php');


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


    //UPLOADING IMAGE

    $post_class = new Posts();

    $imagePath = $post_class->uploadImage($_FILES['featured_image']);

    if (strpos($imagePath, 'error') === false) {

        if ($post_class->create($title, $text, $imagePath, $user_id, $country_id, $topic_id, $create_date)) {
            $_SESSION['message'] = "Post created successfully.";
            $_SESSION['type_alert'] = 'success';

            header('Location: add_post.php');
            exit;
        } else {

            $_SESSION['message'] = "Something goes wrong";
            $_SESSION['type_alert'] = 'error';
            
            header('Location: add_post.php');
            exit;
        }
    }
}
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