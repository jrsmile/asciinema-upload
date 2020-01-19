<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

require_auth();

if(ini_get('file_uploads')){
    error_log('file_uploads is set to "1". File uploads are allowed.',0);
} else{
    error_log('Warning! file_uploads is set to "0". File uploads are NOT allowed.',0);
}

$tempFolder = ini_get('upload_tmp_dir');
 
error_log('Your upload_tmp_dir directive has been set to: "' . $tempFolder . '"',0);
 
if(!is_dir($tempFolder)){
    throw new Exception($tempFolder . ' does not exist!');
} else{
    error_log('The directory "' . $tempFolder . '" does exist.',0);
}
 
if(!is_writable($tempFolder)){
    throw new Exception($tempFolder . ' is not writable!');
} else{
    error_log('The directory "' . $tempFolder . '" is writable. All is good.',0);
}

$post_max_size = ini_get('post_max_size');
 
error_log('Your post_max_size directive has been set to: "' . $post_max_size . '"',0);

$upload_max_filesize = ini_get('upload_max_filesize');
 
error_log('Your upload_max_filesize directive has been set to: "' . $upload_max_filesize . '"',0);

$enable_post_data_reading = ini_get('enable_post_data_reading');
 
error_log('Your enable_post_data_reading directive has been set to: "' . $enable_post_data_reading . '"',0);

$response = array();
$upload_dir = 'uploads/';
$server_url = 'http://127.0.0.1';
if(isset($_FILES)) {
error_log(var_export($_FILES,true), 0);
if($_FILES['asciicast'])
{
    $avatar_name = $_FILES["asciicast"]["name"];
    $avatar_tmp_name = $_FILES["asciicast"]["tmp_name"];
    $error = $_FILES["asciicast"]["error"];
    if($error > 0){
        $response = array(
            "status" => "error",
            "error" => true,
            "message" => "Error uploading the file!"
        );
    }else
    {
        $random_name = rand(1000,1000000)."-".$avatar_name;
        $upload_name = $upload_dir.strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);

        if(move_uploaded_file($avatar_tmp_name , $upload_name)) {
            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Cast uploaded successfully: ".$server_url."/".$upload_name."",
                "url" => $server_url."/".$upload_name
              );
        }else
        {
            $response = array(
                "status" => "error",
                "error" => true,
                "message" => "Error uploading the file!"
            );
        }
    }

}else{
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "No file was sent!"
    );
}
}
// echo json_encode($response);

function require_auth() {
	$AUTH_USER = 'user';
	$AUTH_PASS = 'uid';
	header('Cache-Control: no-cache, must-revalidate, max-age=0');
	$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
	$is_not_authenticated = (
		!$has_supplied_credentials /* ||
		$_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
		$_SERVER['PHP_AUTH_PW']   != $AUTH_PASS */
	);
	if ($is_not_authenticated) {
		header('HTTP/1.1 401 Authorization Required');
		header('WWW-Authenticate: Basic realm="Access denied"');
                error_log("User has not authenticated.",0);
		exit;
	}
        error_log('User: "' . $_SERVER['PHP_AUTH_USER'] . '"',0);
        error_log('Password: "' . $_SERVER['PHP_AUTH_PW'] . '"',0);
}


?>
