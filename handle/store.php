<?php

require_once '../inc/connection.php';
if(isset($_POST['submit'])){

    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['body']));
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imagetmpName = $image['tmp_name'];
    $size_image = $image['size']/(1024*1024);
    $ext =strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $error = $image['error'];
    $new_name = uniqid().".$ext";
    

    //validation
    $errors = [];
    //title
    if(empty($title)){
        $errors[] = "title is required";
    }elseif(is_numeric($title)){
        $errors[] = "title must be string";
    }
    //body
    if(empty($body)){
        $errors[] = "body is required";
    }elseif(is_numeric($body)){
        $errors[] = "body must be string";
    }

    //image
    $array_ext = ["png","jpg","jpeg","gif"];
    if($error != 0){
        $errors[] = "image is required";
    }elseif(! in_array($ext, $array_ext)){
        $errors[] = "image not correct";
    }elseif($size_image > 1){
        $errors[] = "image large size";
    }
     //////////////////////////////////////////////
    if(empty($errors)){
        $query = "insert into posts (`title`,`body`,`image`,`user_id`) 
        values('$title','$body','$new_name',1)";
        $runQuery = mysqli_query($conn, $query);
        if($runQuery){
            move_uploaded_file($imagetmpName, "../uploads/$new_name");
            $_SESSION['success'] = "post add successfuly";
             header("location:../index.php");
        }else{
            $_SESSION['errors'] = ['error while add post'];
             header("location:../addPost.php");
        }


    }else{
        $_SESSION['errors'] = $errors;
        $_SESSION['title'] = $title;
        $_SESSION['body'] = $body;
        header("location:../addPost.php");


    }




}else{
    header("location:../addPost.php");
}