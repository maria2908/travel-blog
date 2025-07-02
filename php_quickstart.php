<?php

require __DIR__ . '/vendor/autoload.php';

// Use the Configuration class 
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Background;
use Cloudinary\Tag\ImageTag;

// Configure an instance of your Cloudinary cloud
Configuration::instance('cloudinary://233295889131347:c4EhYIk_BO1v1Lc7d1WSHZBZ--U@ducl1lqi0?secure=true');

$upload = new UploadApi();
$result = $upload->upload($tmp_path, [
    'public_id' => $original_name,
    'use_filename' => true,
    'overwrite' => true
]);

$img = $result['secure_url']; 

// Upload the image
// $upload = new UploadApi();
// echo '<pre>';
// echo json_encode(
//     $upload->upload('https://res.cloudinary.com/demo/image/upload/flower.jpg', [
//         'public_id' => 'flower_sample',
//         'use_filename' => true,
//         'overwrite' => true]),
//     JSON_PRETTY_PRINT
// );
// echo '</pre>';

// // Get the asset details
// $admin = new AdminApi();
// echo '<pre>';
// echo json_encode($admin->asset('flower_sample', [
//     'colors' => true]), JSON_PRETTY_PRINT
// );
// echo '</pre>';


// Create the image tag with the transformed image
// $imgtag = (new ImageTag('flower_sample'))
//     ->resize(Resize::pad()
//         ->width(400)
//         ->height(400)
//         ->background(Background::predominant())
//     );

// echo $imgtag;
// The code above generates an HTML image tag similar to the following:
//  <img src="https://res.cloudinary.com/demo/image/upload/b_auto:predominant,c_pad,h_400,w_400/flower_sample">