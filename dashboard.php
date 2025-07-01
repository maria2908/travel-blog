<?php
session_start();
require_once('partials/header.php');
require_once('classes/Posts.php');
require_once('classes/Countries.php');
require_once('classes/Topics.php');
require_once('helpers.php');


$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$posts_class = new Posts();
$user_posts = $posts_class->my_posts($user_id);

$country_class = new Countries();
$all_countries = $country_class->all_countries();

$topic_class = new Topics();
$all_topics = $topic_class->all_topics();


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    foreach ($_POST['save'] ?? [] as $post_id => $_) {
        $post_id = (int)$post_id;

        $title = htmlspecialchars(trim($_POST['title'][$post_id] ?? ''));
        $text = htmlspecialchars(trim($_POST['text'][$post_id] ?? ''));
        $img = htmlspecialchars(trim($_POST['img'][$post_id] ?? ''));
        $country_id = htmlspecialchars(trim($_POST['country_id'][$post_id] ?? ''));
        $topic_id = htmlspecialchars(trim($_POST['topic_id'][$post_id] ?? ''));
  
        $post = $posts_class->get_post_by_id($post_id); 
        if (
            $post->title !== $title ||
            $post->text !== $text ||
            $post->img !== $img ||
            $post->country_id !== $country_id ||
            $post->topic_id !== $topic_id
        ) {
            $posts_class->update_post($post_id, $title, $text, $img, $country_id, $topic_id);
            $_SESSION['message'] = "Post edited successfully.";
            $_SESSION['type_alert'] = 'success';

            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "You changed nothing";
            $_SESSION['type_alert'] = 'error';
        }
    }    

    foreach ($_POST['delete'] ?? [] as $post_id => $_) {
        $post_id = (int)$post_id;
        $posts_class->delete_post_by_id($post_id);

        $_SESSION['message'] = "Post deleted successfully.";
        $_SESSION['type_alert'] = 'success';

        header("Location: dashboard.php");
        exit();
    }
}

require_once ('partials/head.php');
require_once('alert.php');
?>
<div class="edit_profile">
    <div class="control">
        <div class="dashboard">
            <form method="POST" onsubmit="return confirmDelete(this);">
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Country</th>
                        <th>Topic</th>
                        <th>Activity</th>
                    </tr>
                    <?php foreach($user_posts as $user_post): ?>
                        <tr>                        
                            <td><input type="text" value="<?= htmlspecialchars($user_post->title) ?>"  name="title[<?php echo $user_post->id ?>]" id="title" /></td>
                            <td><textarea rows="4" cols="50" class="my-textarea" type="text" name="text[<?php echo $user_post->id ?>]" id="text" ><?= htmlspecialchars($user_post->text) ?></textarea></td>
                            <td><input type="text" value="<?= htmlspecialchars($user_post->img) ?>"   name="img[<?php echo $user_post->id ?>]" id="img" /></td>
                            <td>
                                <select name="country_id[<?= $user_post->id ?>]">
                                    <?php foreach($all_countries as $country): ?>
                                        <option value="<?= $country->id ?>" 
                                            <?= $user_post->country_id == $country->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($country->country) ?>
                                        </option>
                                    <?php endforeach; ?>`
                                </select>
                            </td>
                            <td>
                                <select name="topic_id[<?= $user_post->id ?>]">
                                    <?php foreach($all_topics as $topic): ?>
                                        <option value="<?= $topic->id ?>" 
                                            <?= $user_post->topic_id == $topic->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($topic->topic) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="display: flex;">
                                <button type="submit" style="width:100px; margin: 5px 0; background: green;" name="save[<?= $user_post->id ?>]">Save</button>
                                <button style="width:100px; margin: 5px 4px; background: red;" name="delete[<?= $user_post->id ?>]">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(form) {
    // Prüfen, ob ein Lösch-Button geklickt wurde
    const deleteClicked = [...form.querySelectorAll("button[name^='delete']")]
        .some(btn => btn.matches(':focus'));

    if (deleteClicked) {
        return confirm("Are you sure you want to delete this post?");
    }

    return true;
}
</script>