<?php
require_once("views/layouts/header.php") ;
?>
    <title>User - Search</title>
    <link rel="stylesheet" href='public/css/search.css'>

    <!--            Form Search-->
    <div id="form_search">
        <form method="GET" action="">
            <input type="" style="display: none" name="controller" value="user">
            <input type="" style="display: none" name="action" value="search">
            <span class="input-space">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="<?php if(isset($_GET['email'])) echo $_GET['email'] ?>" id="email" maxlength="50">
                     </span>
            <span class="input-space">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="<?php if(isset($_GET['name'])) echo $_GET['name'] ?>" id="name">
                     </span>
            <div class="form-group d-flex mt-4" style="justify-content: space-between">
                <button type="submit" class="btn btn-secondary" name="reset">Reset</button>
                <button type="submit" class="btn btn-primary" name="search">Search</button>
            </div>
        </form>
    </div>

    <!--Pagging-->
    <?php
        require_once "views/layouts/pagging.php";
    ?>

    <!--Data Table-->
    <div id="data_table">
    <?php
                    if(isset($_SESSION['alert']['update-success'])) {
                        echo '<p class="alert-success bg-green">'.$_SESSION['alert']['update-success'].'</p>';
                    } else {
                         echo ""; 
                    }
                    if(isset($_SESSION['alert']['update-fail'])) {
                        echo '<p class="alert-success bg-green">'.$_SESSION['alert']['update-fail'].'</p>';
                    } else {
                         echo ""; 
                    }
                    if(isset($_SESSION['alert']['delete-success'])) {
                        echo '<p class="alert-success bg-green">'.$_SESSION['alert']['delete-success'].'</p>';
                    } else {
                         echo ""; 
                    }
                    if(isset($_SESSION['alert']['delete-fail'])) {
                        echo '<p class="alert-success bg-green">'.$_SESSION['alert']['delete-fail'].'</p>';
                    } else {
                         echo ""; 
                    }
                    unset($_SESSION['alert']);
                ?>
        <table class="table table-striped table-hover table-condensed">
            <tr>
                <th><a href="index.php?controller=user&action=search<?php echo $add_url_search . '&column=id&sort=' . $sort; ?>">ID <i class="fas fa-sort"></i></a></th>
                <th>Avatar</th>
                <th><a href="index.php?controller=user&action=search<?php echo $add_url_search . '&column=name&sort=' . $sort; ?>">Name <i class="fas fa-sort"></i></a></th>
                <th><a href="index.php?controller=user&action=search<?php echo $add_url_search . '&column=email&sort=' . $sort; ?>">Email <i class="fas fa-sort"></i></a></th>
                <th><a href="index.php?controller=user&action=search<?php echo $add_url_search . '&column=status&sort=' . $sort; ?>">Status <i class="fas fa-sort"></i></a></th>
                <th>Action</th>
            </tr>
            <tr>
                <?php
                if(is_array($data)){
                foreach ($data as $value){
                ?>
                <td><?php echo $value['id'] ?></td>
                <td><img src="<?php echo UPLOADS_USER . $value['avatar']; ?>"></td>
                <td><?php echo $value['name'] ?></td>
                <td><?php echo $value['email'] ?></td>
               
                <td><?php echo($value['status']==STATUS_BANNER) ? 'Banned':'Active';?></td>
                <td>
                    <span class="btn btn-success"><a href="index.php?controller=user&action=edit&id=<?php echo $value['id']?>">Edit</a></span>
                    <span class="btn btn-danger"><a href="index.php?controller=user&action=delete&id=<?php echo $value['id']?>" onclick="return confirm('Are you sure?')";>Delete</a></span>
                </td>
            </tr>
            <?php
            }
            }else{
                echo "<td colspan='6' style='background: #e0e0e0'>$data</td>";
            }
            ?>
        </table>
    </div>
<?php
require_once("views/layouts/footer.php") ;
?>