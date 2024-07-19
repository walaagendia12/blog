<?php
require_once '../inc/connection.php';

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['body']));

    $errors = [];
    // title
    if (empty($title)) {
        $errors[] = "title is required";
    } elseif (is_numeric($title)) {
        $errors[] = "title must be string";
    }
    // body
    if (empty($body)) {
        $errors[] = "body is required";
    } elseif (is_numeric($body)) {
        $errors[] = "body must be string";
    }


    //check
    $query = "SELECT id, image FROM posts WHERE id = $id";
    $runQuery = mysqli_query($conn,$query);
    if (mysqli_num_rows($runQuery)==1) {
        $post =mysqli_fetch_assoc($runQuery);
        $oldImage = $post['image'];


        //check image
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $imageName = $image['name'];
            $imagetmpName = $image['tmp_name'];
            $size_image = $image['size'] / (1024 * 1024);
            $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $error = $image['error'];
            // validation
            $array_ext = ["png", "jpg", "jpeg", "gif"];
            if ($error != 0) {
                $errors[] = "image is required";
            } elseif (!in_array($ext, $array_ext)) {
                $errors[] = "image not correct";
            } elseif ($size_image > 1) {
                $errors[] = "image large size";
            }

            $new_name = uniqid() . ".$ext";
        } else {
            $new_name = $oldImage;
        }
        //update
        if (empty($errors)) {
            $query = "update posts set `title`='$title', `body`='$body', `image`='$new_name' where id = $id ";
            $runQuery = mysqli_query($conn,$query);
            if($runQuery){
                if (!empty($_FILES['image']['name'])) {
                    unlink("../uploads/$oldImage");
                    move_uploaded_file($imagetmpName, "../uploads/$new_name");
                }

                $_SESSION['success'] = "post updated successfully";
                header("Location: ../viewPost.php?id=$id");
            } else {
                $_SESSION['errors'] = ['error while updating post'];
                header("Location: ../editPost.php?id=$id");
            }
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['title'] = $title;
            $_SESSION['body'] = $body;
            header("Location: ../editPost.php?id=$id");
        }
    } else {
        $_SESSION['errors'] = ["post not found"];
        header("Location: ../index.php");
    }
} else {
    $_SESSION['errors'] = ["please choose correct operation"];
    header("Location: ../index.php");
}





