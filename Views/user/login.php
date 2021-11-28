<?php
    session_start();
    // セッションが残っていたら
    if(isset($_SESSION['id'])){
        unset($_SESSION['id']);
    }
    // ログイン処理
    require_once(ROOT_PATH .'Controllers/ShopController.php');
    $login = new ShopController();
    $error = $login->login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/login.css" rel="stylesheet">
    <!-- BootstrapのCSS読み込み -->
    <link href="bootstrapcss/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="js/bootstrap.min.js"></script>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body class="d-flex justify-content-center" style="height: 830px;">
    <div class="bg-light w-50 m-5 rounded-circle" style="height: 680px;">
        <div class="p-5 d-flex flex-column">
            <form action="" method="post" class="">
                <div class="d-flex justify-content-center">
                    <img src="img/logo.png" alt="">
                </div>
                <?php if(isset($error['error'])){echo '<p style="color: red;" class="d-flex justify-content-center">＊メールアドレスかパスワードが違います。</p>';}?>
                <div class="d-flex justify-content-center">
                    <input type="email" name="email" placeholder="メールアドレス" class="w-50 fs-4">
                </div>
                <div class="d-flex justify-content-center">
                    <input type="password" name="password" placeholder="パスワード" class="w-50 mt-5 mb-5 fs-4">
                </div>
                <div class="d-flex justify-content-center m-2">
                    <button type="submit" class="btn btn-primary w-25">ログイン</button>
                </div>
                <div class="d-flex justify-content-center m-3">
                    <a href="passcha.php">パスワードを忘れた方はこちら</a>
                </div>
                <div class="d-flex justify-content-center m-3">
                    <a href="add.php">新規登録</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>