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
    <div id="wrapper">
        <div id="menu">
            <ul id="main-menu">
                <li>
                    <?php
                        if(isset($_SESSION['admin'])) {

                            if($_SESSION['admin']['role_type'] == 1) {
                    ?>
                        <span>Admin management</span><i class="fas fa-caret-down"></i>
                             <ul id="sub-menu">
                                 <li class="nav-item"><a href="index.php?controller=admin&action=search">Search</a></li>
                                 <li class="nav-item"><a href="index.php?controller=admin&action=create">Create</a></li>
                             </ul>
                        <?php
                            }
                        ?>
                </li>
                <li>
                    <?php
                    ?>
                         <span>User management</span><i class="fas fa-caret-down"></i>
                             <ul id="sub-menu">
                                 <li class="nav-item"><a href="index.php?controller=user&action=search">Search</a></li>
                                 <li class="nav-item"><a href="">Create</a></li>
                             </ul>
                    <?php
                        } else {
                            header("Location: index.php?controller=admin&action=login");
                        }
                    ?>
                </li>
                <li><a href="index.php?controller=admin&action=logout">Logout</a></li>
            </ul>
        </div>
    