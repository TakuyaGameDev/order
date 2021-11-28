<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/header.css" rel="stylesheet">
    <!-- BootstrapのCSS読み込み -->
    <link href="bootstrapcss/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード変更</title>
</head>
<body>
    <?php if(isset($_SESSION['id'])){ require_once(ROOT_PATH .'Views/layout/header.php'); } ?>
    <?php 
    if(isset($_POST['email'])){
        require_once(ROOT_PATH .'Controllers/ShopController.php');
        $passcha = new ShopController();
        $passcha->passcha();
    }
    ?>
    <div class="d-flex justify-content-center">
        <div class="m-5 w-50">
            <h3 class="d-flex justify-content-center m-5">パスワード変更</h3>
            <form action="" method="post">
                <table class="table table-bordered table-striped table-danger border border-4 rounded-3">
                    <tr>
                        <td class="w-25 fs-6 text-center">メールアドレス：</td>
                        <td><input type="email" name="email" class="w-100 fs-5"></td>
                    </tr>
                    <tr>
                        <td class="fs-6 text-center">変更したいパスワード：</td>
                        <td><input type="password" name="password" class="w-100 fs-5"></td>
                    </tr>
                    <tr>
                        <td class="fs-6 text-center">パスワード再入力：</td>
                        <td><input type="password" name="re_password" class="w-100 fs-5"></td>
                    </tr>
                </table>
                <?php if(empty($_SESSION['id'])){
                    echo '<div class="d-flex justify-content-center m-5">                
                            <a href="login.php" class="btn btn-secondary w-25 m-5">戻る</a>
                            <button type="submit" class="btn btn-primary w-25 m-5">登録</button>
                        </div>';
                }?>
                <?php if(isset($_SESSION['id'])){
                    echo '<div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary w-25 m-5">登録</button>
                        </div>';
                }?>
            </form>
        </div>
    </div>
</body>
</html>