<?php
    session_start();
    // ダイレクトアクセス防止
    if(empty($_SESSION['id']) || $_SESSION['id'] == 1){
        header('Location:login.php');
    }

    // 自店舗情報一覧
    require_once(ROOT_PATH .'/Controllers/ShopController.php');
    $add = new ShopController();
    $params = $add->index();
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
    <title>マイページ</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/header.php') ?>
    <!-- テーブル -->
    <div class="d-flex justify-content-center w-100">
        <div class="w-75 mt-5">
            <table class="table table-bordered table-striped table-danger border border-4 rounded-3 m-5">
                <tr>
                    <td class="w-25 fs-3 text-center">店舗名：</td>
                    <td class="fs-3"><?php echo $params['shop']['0']['shop_name'] ?></td>
                </tr>
                <tr>
                    <td class="w-25 fs-3 text-center">郵便番号：</td>
                    <td class="fs-3"><?php echo $params['shop']['0']['postal_code'] ?></td>
                </tr>
                <tr>
                    <td class="w-25 fs-3 text-center">住所：</td>
                    <td class="fs-3"><?php echo $params['shop']['0']['address'] ?></td>
                </tr>
                <tr>
                    <td class="w-25 fs-3 text-center">電話番号：</td>
                    <td class="fs-3"><?php echo $params['shop']['0']['tel'] ?></td>
                </tr>
                <tr>
                    <td class="w-25 fs-3 text-center">メールアドレス：</td>
                    <td class="fs-3"><?php echo $params['shop']['0']['email'] ?></td>
                </tr>
            </table>
            <div class="d-flex justify-content-end">
                <div class="me-5">
                    <a href="passcha.php" class="btn btn-primary">パスワードを変更する</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>