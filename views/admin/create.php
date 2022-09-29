<?php
    require_once("views/layouts/header.php") ;
?>

<title>Admin - Create</title>
<link rel="stylesheet" href='public/css/create.css'>

<div id="wrapper-create">
    <h4>Admin - Create</h4>
    <form method="POST" action="" enctype="multipart/form-data">
       
        <div id="wrapper-create-sub">
            <div id="wrapper-create-form">
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                    <label class="file-upload"><input class="avatar" type="file" name="avatar" onchange="readURL(this);" value="<?php if(isset($_FILES['avatar']['name'])) echo $_FILES['avatar']['name'];?>">File Upload</label>
                    <label class="file-name ml-2"></label>
                </div>
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label"></label>
                    <label><img id="upload-file" src="<?php echo isset($_SESSION['admin']['upload']) ? $_SESSION['admin']['upload'] : 'public/uploads/empty.jpg'; ?>"></label>
                    <?php unset($_SESSION['admin']['upload']); ?>
                </div>
                   <?php if(isset($data['error-avatar'])) echo "<p class='error'>{$data['error-avatar']}</p>"; ?>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                    <input type="text" maxlength="255" class="form-control" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>">
                </div>
                    <?php if(isset($data['error-name'])) echo "<p class='error'>{$data['error-name']}</p>"; ?>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email*</label>
                    <input type="text" maxlength="255" class="form-control" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                </div>
                    <?php if(isset($data['error-email'])) echo "<p class='error'>{$data['error-email']}</p>"; ?>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password*</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                    <?php if(isset($data['error-password'])) echo "<p class='error'>{$data['error-password']}</p>"; ?>  
                <div class="form-group row">
                    <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify*</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm-password">
                </div>
                <?php if(isset($data['error-confirm-password'])) echo "<p class='error'>{$data['error-confirm-password']}</p>"; ?>
                <div class="form-group row">
                    <label for="role" class="col-sm-2 col-form-label">Role*</label>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_type" value="1" <?php (isset($_POST['role_type']) && $_POST['role_type'] == 1) ? "checked" : ""; ?>>Super Admin
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="role_type" value="2" <?php (isset($_POST['role_type']) && $_POST['role_type'] == 2) ? "checked" : ""; if(empty($_POST['role_type'])) echo "checked"; ?>>Admin
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group d-flex mt-4" style="justify-content: space-between">
            <button type="submit" class="btn btn-secondary" name="reset">Reset</button>
            <button type="submit" class="btn btn-primary" name="save">Save</button>
        </div>
    </form>
</div>

<script>
    $(".avatar").change(function(){
        $(".file-name").text(this.files[0].name);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#upload-file')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
    require_once("views/layouts/footer.php") ;
?>