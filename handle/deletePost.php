<?php

require_once '../inc/connection.php';

if (isset($_POST['submit']) && isset($_GET['id'])){
    $id = (int) $_GET['id'];
    $query = "select id , image from posts where id = $id";
    $runQuery = mysqli_query($conn,$query);
    if (mysqli_num_rows($runQuery)==1 ){
        $post = mysqli_fetch_assoc($runQuery);
            unlink("../uploads/".$post['image']);
        
        $query = "DELETE FROM posts WHERE id=$id";
        $runQuery = mysqli_query($conn,$query);
        if($runQuery){
            $_SESSION['success'] = "post deleted successfuly";
            header("location:../index.php");  
        }else{

            $_SESSION['errors'] = ["error while delete"];
            header("location:../index.php"); 
        }
    } else{

        $_SESSION['errors'] = ["please choose correct operation"];
        header("location:../index.php"); 
    }


}else{
    $_SESSION['errors'] = ["please choose correct operation"];
    header("location:../index.php");
}
