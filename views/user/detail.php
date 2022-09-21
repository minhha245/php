
    <DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href='public/css/all.css'>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <link rel="stylesheet" href="public/css/detail.css">
    <p class="alert-success bg-green"><?php echo isset($_SESSION['user']['loginFB-success']) ? LOGIN_FB_SUCCESSFUL : ""; unset($_SESSION['user']['loginFB-success']); ?></p>
    <title>User - Detail</title>
        <div id="wrapper-detail">
            <div class="form-group row">
                <label for="avatar" class="col-sm-2 col-form-label">ID</label>
                <?php echo $data['facebook_id']; ?>
            </div>

            <div class="form-group row">
                <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
                <img src="<?php echo UPLOADS_USER . $data['avatar']; ?>">
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <label for="name" class="col-sm-2 col-form-label"><?php echo $data['name']; ?></label>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <label for="name" class="col-sm-2 col-form-label"><?php echo $data['email']; ?></label>
            </div>
            <div class="form-group row">
            <a class="btn btn-primary" href="index.php?controller=user&action=logout">Logout</a>
            </div>
        </div>

<?php
    require_once("views/layouts/footer.php") ;
?>