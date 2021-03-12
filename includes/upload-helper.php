<?php

require 'dbhandler.php';
session_start();

define('KB', 1024);
define('MB', 1048576);

if(isset($_POST['prof-submit'])){

    $uname = $_SESSION['uname'];
    $file = $_FILES['prof-image'];
    $file_name = $file['name'];
    $file_tmp_name = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed = array('jpg', 'jpeg', 'svg', 'png');

    if($file_error !== 0){
        header("Location: ../profile.php?error=UploadError");
        exit();
    }

    if(!in_array($ext, $allowed)){
        header("Location: ../profile.php?error=UnsupportedFileType");
        exit();
    }

    if($file_size > 4*MB){
        header("Location: ../profile.php?error=fileSizeExceeded");
        exit();
    }

    else {
        $new_name = uniqid('', true).".".$ext;

        $destination = '../CS-230-Lab-2021/profiles/'.$new_name;
        $moveDest = '../profiles/'.$new_name;
        $sql = "UPDATE profiles SET profpic='$destination' WHERE uname='$uname'";
        mysqli_query($conn, $sql);

        move_uploaded_file($file_tmp_name, $moveDest);
        header("Location: ../profile.php?success=UploadWin");
        exit();
    }


}else{
    header("Location: ../profile.php");
    exit();
}