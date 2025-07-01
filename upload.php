<?php 

require 'cloudinary_config.php';

use Cloudinary\Api\Upload\UploadApi;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image']['tmp_name'];

    try {
        $result = (new UploadApi())->upload($file);
        echo "Upload successful!<br>";
        echo "Image URL: <a href='{$result['secure_url']}' target='_blank'>{$result['secure_url']}</a>";
    } catch (Exception $e) {
        echo 'Upload failed: ' . $e->getMessage();
    }
}

?>