<?php
    include_once('../../config/init.php');
    include_once($BASE_DIR .'database/users.php');  

    if (!$_POST['username'] || !$_POST['nome'] || !$_POST['password'] || !$_POST['repeat_password'] || !$_POST['email'] || !$_POST["pais"]) {
        $_SESSION['error_messages'][] = 'All fields are mandatory';
        $_SESSION['form_values'] = $_POST;
        header("Location: $BASE_URL" . 'pages/users/register.php');
        exit;
    }

    $nome = strip_tags($_POST['nome']);
    $username = strip_tags($_POST['username']);
    $password = $_POST['password'];
    $r_password = $_POST['repeat_password'];
    $email = $_POST["email"];
    $pais = $_POST["pais"];

    //Implement Later
    //$photo = $_FILES['photo'];
    //$extension = end(explode(".", $photo["name"]));

    if($password != $r_password){
        $_SESSION['error_messages'][] = 'Password and Repeated Password mismatch';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    if(accountAlreadyExists($username)){
        $_SESSION['error_messages'][] = 'Username Already in Use';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    createUser($nome, $username, $password, $email, $pais);
    $_SESSION["username"] = $username;
    $_SESSION["name"] = $name;
    $_SESSION["password"] = $password;
    header("Location: $BASE_URL");
?>
