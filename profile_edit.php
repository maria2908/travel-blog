<?php
session_start();
require_once('classes/User.php');
require_once('classes/Posts.php');
require_once('helpers.php');
require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance('cloudinary://233295889131347:c4EhYIk_BO1v1Lc7d1WSHZBZ--U@ducl1lqi0?secure=true');

$user = new User();
$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$posts_class = new Posts();
$user_posts = $posts_class->my_posts($user_id);
$action = $_POST['action'] ?? null;
$active_tab = $_GET['tab'] ?? 'personal_data';
$user_data = $user->getProfilData($user_id);
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'avif', 'webp'];
$img = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    
    if ($action === 'upload_image' && isset($_FILES['profile_img'])) {
        $filename = $_FILES['profile_img']['name'];
        $tmp_path = $_FILES['profile_img']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedTypes)) {

            $_SESSION['message'] = 'Only JPG, JPEG, PNG, GIF, AVIF and WEBP files are allowed.';
            $_SESSION['type_alert'] = 'error';
        } else {

            $original_name = pathinfo($filename, PATHINFO_FILENAME);

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
            }
            
            try {
                $user->setProfileImg($img, $user_id);
            
                
                $_SESSION['message'] = "Profile image was changed successfully";
                $_SESSION['type_alert'] = 'success';
            }catch (Exception $e) {
                $_SESSION['message'] = "Image upload failed: " . $e->getMessage();
                $_SESSION['type_alert'] = 'error';
            }

        }

    }

    if ($action === 'update_personal_data') {
        $fields = ['firstname', 'lastname', 'email', 'phone', 'country', 'city', 'instagram'];

        foreach ($fields as $field) {
            $new_value = htmlspecialchars(trim($_POST[$field] ?? ''));
            if ($new_value !== $user_data[$field]) {
                $user->setProfilData($field, $new_value, $user_id);
            }
        }        
    
    }
    
    if ($action === 'change_password') {
        $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        $current_passwordValidationError = validatePassword($current_password);
        $new_passwordValidationError = validatePassword($new_password);
        $confirm_passwordValidationError = validatePassword($confirm_password);

        if ($current_passwordValidationError !== true) {
            $current_passwordValidation = $current_passwordValidationError;

        } elseif ($new_passwordValidationError !== true) {
            $new_passwordValidation = $new_passwordValidationError;

        } elseif ($confirm_passwordValidationError !== true) {
            $confirm_passwordValidation = $confirm_passwordValidationError;

        } else {
            $password_change_result = $user->change_password($user_id, $current_password, $new_password, $confirm_password);

            if ($password_change_result === true) {

                $_SESSION['message'] = "Password changed successfully.";
                $_SESSION['type_alert'] = 'success';
                
            } else {
                $_SESSION['message'] = $password_change_result;
                $_SESSION['type_alert'] = 'error';
   
            }

        }
    }
}

$userProfileImage = $user->getProfileImg($user_id);
$profileImage = !empty($userProfileImage) ? $userProfileImage : 'uploads/user-icon/default.jpg';

require_once ('partials/head.php');
require_once('alert.php');
require_once('partials/header.php');
?>

<div class="edit_profile">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="settings">
        <div class="navigation">
            <div class="profile-container">
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    <input type="hidden" name="action" value="upload_image">
                    <img src="<?php echo $profileImage; ?>" class="profile-pic" id="profileImage">
                    <label for="fileInput" class="upload-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="#ffffff" d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                        </svg>
                    </label>
                    <input type="file" name="profile_img" id="fileInput" accept="image/*">
                </form>
            </div>
            <a class="<?= $active_tab === 'personal_data' ? 'active' : '' ?>" href="?tab=personal_data">Personal Data</a>
            <a class="<?= $active_tab === 'change_password' ? 'active' : '' ?>" href="?tab=change_password">Change Password</a>
            <a class="<?= $active_tab === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="edit">
            <?php if ($active_tab === 'personal_data'): ?>
                <form id="personal_data" method="POST">
                    <input type="hidden" name="action" value="update_personal_data">
                    <h3>Personal Data</h3>

                    <div class="user_data">
                        <div>
                            <label for="firstname">Firstname</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['firstname']) ?>" placeholder="Please enter post firstname"  name="firstname" id="firstname" required>
                        </div>
                        <div>
                            <label for="lastname">Lastname</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['lastname']) ?>" placeholder="Please enter post lastname" id="lastname" name="lastname" required>
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input type="email" value="<?= htmlspecialchars($user_data['email']) ?>" placeholder="Please enter post email" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="phone">Phone</label>
                            <input type="tel" value="<?= htmlspecialchars($user_data['phone']) ?>" placeholder="Please enter post phone" id="phone" name="phone" >
                        </div>
                        <div>
                            <label for="country">Country</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['country']) ?>" placeholder="Please enter post country" id="country" name="country" >
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['city']) ?>" placeholder="Please enter post city" id="city" name="city" >
                        </div>
                        
                        <div>
                            <label for="instagram">Instagram</label>
                            <input type="text" value="<?= htmlspecialchars($user_data['instagram']) ?>" placeholder="Please enter post instagram" id="instagram" name="instagram" >
                        </div>

                    </div>

                    <div class="btn_group">
                        <button type="submit">Save</button>
                        <button type="reset" id="cancelBtn">Cancel</button>
                    </div>
                </form>
            <?php elseif ($active_tab === 'change_password'): ?>
                <form id="change_password" method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <h3>Change Password</h3>
                    <div>
                        <label for="current_password">Current Password</label>
                        <input type="password" value="" placeholder="Please enter new password" id="current_password" name="current_password" required>
                        <?php if (!empty($current_passwordValidationError) && is_array($current_passwordValidationError)): ?>
                            <div style="color: red; font-size: 10px; margin-top: 5px;">
                                <?php foreach ($current_passwordValidationError as $error): ?>
                                    <?= htmlspecialchars($error) ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="new_password">New Password</label>
                        <input type="password" value="" placeholder="Please enter new password" id="new_password" name="new_password" required>
                        <?php if (!empty($new_passwordValidationError) && is_array($new_passwordValidationError)): ?>
                            <div style="color: red; font-size: 10px; margin-top: 5px;">
                                <?php foreach ($new_passwordValidationError as $error): ?>
                                    <?= htmlspecialchars($error) ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" value="" placeholder="Please enter new password to confirm it" id="confirm_password" name="confirm_password" required>
                        <?php if (!empty($confirm_passwordValidationError) && is_array($confirm_passwordValidationError)): ?>
                            <div style="color: red; font-size: 10px; margin-top: 5px;">
                                <?php foreach ($confirm_passwordValidationError as $error): ?>
                                    <?= htmlspecialchars($error) ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="btn_group">
                        <button type="submit">Save</button>
                        <button type="reset" id="cancelBtn">Cancel</button>
                    </div>
                </form>
            <?php endif; ?>


        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById("fileInput");
    fileInput.addEventListener("change", function() {
        document.getElementById("uploadForm").submit();
    });
</script>