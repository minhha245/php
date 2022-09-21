<?php
    require_once('vendor/facebook/graph-sdk/src/Facebook/autoload.php');
    $fb = new Facebook\Facebook([
        'app_id' => APP_ID,
        'app_secret' => APP_SECRET,
        'default_graph_version' => DEFAULT_GRAPH_VERSION,
    ]);
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('http://localhost/mvc_php/index.php?controller=user&action=loginViaFB', $permissions);
?>
<DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>User - Login</title>
        <link rel="stylesheet" href="public/css/all.css">
        <link rel="stylesheet" href="public/css/login.css">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    </head>
    <body>
        <div id="login">
            <form method="POST" action="">
                <h2 style="text-align: center">Login User</h2>
                <span class="input-space">
                    <label for="email">Email</label>
                    <input type="text" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} else{echo '';} ?>" id="email" maxlength="50">
                    <p class="error"><?php if(isset($data['error-empty-email'])){echo $data['error-empty-email'];} ?></p>
                 </span>

                <span class="input-space">
                    <label for="password">Password</label>
                    <input type="password" name="password" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} else{echo '';} ?>" id="password">
                    <p class="error"><?php if(isset($data['error-empty-password'])){echo $data['error-empty-password'];} ?></p>
                 </span>

                <p class="error error_login"><?php if(isset($data['error-login'])){echo $data['error-login'];} ?></p>
                <input type="submit" name="login" value="Login">
                
                <a href="<?php echo $loginUrl ?>" style="margin-top: 20px; display: block; text-align: center; color: blue;"><i class="fab fa-facebook-square" style="margin-right: 5px; font-size: 25px"></i>Login with Facebook</a>
            </form>
        </div>
    </body>
</html>
