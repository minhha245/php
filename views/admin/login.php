<!DOCTYPE html>
<html>
    <head>
    <!-- <base href="http://localhost/php/"/> -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Admin - Login</title>
        <link rel="stylesheet" href="public/css/all.css">
        <link rel="stylesheet" href="public/css/login.css">
       
    </head>
    <body>
    <?php
                        if(isset($_SESSION['admin']['login'])) {
                            header("Location: index.php?controller=admin&action=search");
                        }
                    ?>
        <div id="login">
            <form method="POST" action="">
                <h2 style="text-align: center">Login Admin</h2>
                <span class="input-space">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} else{echo '';} ?>" id="email" maxlength="50">
                    <p class="error"><?php if(isset($data['error-empty-email'])){echo $data['error-empty-email'];} ?></p>
                 </span>

                <span class="input-space">
                    <label for="password">Password</label>
                    <input type="password" name="password" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} else{echo '';} ?>" id="password">
                    <p class="error"><?php if(isset($data['error-empty-password'])){echo $data['error-empty-password'];} ?></p>
                 </span>

               
                <input id="submit" type="submit" name="login" value="Login">
                <p class="error error_login"><?php if(isset($data['error-login'])){echo $data['error-login'];} ?></p>
            </form>
        </div>
    </body>
</html>
