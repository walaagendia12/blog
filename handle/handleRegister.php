<?php

require_once '../inc/connection.php';

if (! isset($_POST['submit'])){
    header("location:../register.php");
}

$name = trim(htmlspecialchars($_POST['name']));
$email = trim(htmlspecialchars($_POST['email']));
$password = trim(htmlspecialchars($_POST['password']));
$phone = trim(htmlspecialchars($_POST['phone']));


//validatin
$errors = [];

//name (required , string , 50)
if (empty($name)){
    $errors[] = "name is required";
}elseif(is_numeric($name)){
    $errors[]= "name must be strimg";
}elseif(strlen($name)>100){
    $errors[] = "name must be less than 100 char";
}

//email (required , email )
if (empty($email)){
    $errors[] = "email is required";
}elseif(! filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[]= "email not falid";
}
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email already in use";
}

//password (required , >=6)
if (empty($password)){
    $errors[] = "password is required";
}elseif(strlen($password)<6){
    $errors[] = "name must be more than or equal 6 char";
}
//phone (int , 15)
if(! is_string($phone)){
    $errors[]= "phone not correct";
}elseif(strlen($phone) < 11){
    $errors[] = "phone not valid";
}
$query2 = "SELECT * FROM users WHERE phone='$phone'";
$result2 = mysqli_query($conn, $query2);
if (mysqli_num_rows($result) > 0) {
    $errors[] = "phone already in use";
}


// hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

//INSERT
if(empty($errors)){
    $query = "insert into users (`name`,`email`,`password`,`phone`)
     values ('$name','$email','$passwordHash','$phone')";
     $runQuery = mysqli_query($conn,$query);
    if($runQuery){
        $_SESSION['success'] = "your account created successfly";

         header("location:../login.php");


    }else{
        $_SESSION['errors'] = ['error while insert'];
        
        header("location:../register.php");

    }

}else{
    $_SESSION['errors'] = $errors;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['phone'] = $phone;
    header("location:../register.php");
}