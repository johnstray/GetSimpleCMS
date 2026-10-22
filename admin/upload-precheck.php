<?php

/**
 * upload precheck, used to check if file already exists and upload is allowed before wasting bandwidth
 * For use with GSUPLOADER
 *
 * @since  3.4
 * @package GetSimple
 * @subpackage Files
 */

include('inc/common.php');
login_cookie_check();

$path = $_GET['path'] ?? '';
$filename = $_POST['filename'] ?? '';

if (
    !is_string($path) || !is_string($filename)
    || strpos($path, "\0") !== false || strpos($filename, "\0") !== false
) {
    http_response_code(400);
    die(json_encode(array('error' => 'invalid path')));
}

$name = clean_img_name($filename);
$destination = GSDATAUPLOADPATH . tsl($path) . $name;

if (!filepath_is_safe($destination, GSDATAUPLOADPATH, true, true)) {
    http_response_code(400);
    die(json_encode(array('error' => 'invalid path')));
}

echo json_encode(array('file_exists' => file_exists($destination)));
