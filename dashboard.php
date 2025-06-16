<?php
session_start();
require_once ('partials/head.php');
require_once('partials/header.php');
require_once('classes/Posts.php');
require_once('helpers.php');
require_once('alert.php');

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$posts_class = new Posts();
$user_posts = $posts_class->my_posts($user_id);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fields = ['title', '' ];
}

?>
<div class="edit_profile">
    <div class="control">
        <div class="dashboard">
            <form method="POST">
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
                            <td><input type="text" value="<?php echo $user_post->title ?>"  name="title" id="title" /></td>
                            <td><textarea rows="4" cols="50" class="my-textarea" type="text" name="description" id="description" ><?php echo $user_post->text ?></textarea></td>
                            <td><input type="text" value="<?php echo $user_post->img ?>"   name="img" id="img" /></td>
                            <td><input type="text" value="<?php echo $user_post->country ?>"   name="country" id="country" /></td>
                            <td><input type="text" value="<?php echo $user_post->topic ?>"   name="topic" id="topic" /></td>
                            <td style="display: flex;"><button type="submit" style="width:100px; margin: 5px 0;">Save</button><button style="width:100px; margin: 5px 4px;">Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
</div>
