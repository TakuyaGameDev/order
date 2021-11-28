<?php
    session_start();
    // ダイレクトアクセス防止
    if(empty($_SESSION['id']) || $_SESSION['id'] != 1){
        header('Location:login.php');
    }
    // 商品名読み込み
    require_once(ROOT_PATH .'Views/other/num_name.php');

    // 日付
    require_once(ROOT_PATH .'Views/other/date.php');

    // 店舗情報一覧
    require_once(ROOT_PATH .'/Controllers/ShopController.php');
    $shop = new ShopController();
    $params = $shop->shop();

    // 発注一覧
    require_once(ROOT_PATH .'Controllers/OrderController.php');
    $order = new OrderController();
    $order_count = $order->order_count();

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
    <title>発注詳細</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/owner_header.php') ?>
    <!-- テーブル -->
    <table class="table table-bordered table-striped table-danger border border-4 text-center fs-5">
        <tr>
            <th colspan="8" class="fs-4 text-center"><?php echo $params['shop'][0]['name']."：".$params['shop'][0]['shop_name'] ?></th>
        </tr>
        <tr>
            <td>商品名</td>
            <td><?php echo $today ?><br>発注数</td>
            <?php for ($i=1; $i <= 6; $i++) : ?>
                <td><?php echo $ago[$i] ?><br>発注数</td>
            <?php endfor; ?>
        </tr>
        <?php for ($x=1; $x <= 10; $x++) : ?>
            <tr>
                <td><?php echo $name[$x] ?></td>
                <?php for ($i=0; $i <= 6; $i++) : ?>
                    <td><?php if(isset($order_count['order_count'][$x][$i]['order_count'])){ echo $order_count['order_count'][$x][$i]['order_count'];}else{echo 0;} ?></td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
    <div class="d-flex justify-content-end">
        <div class="me-5">
            <a href="owner.php" class="btn btn-primary">　戻る　</a>
        </div>
    </div>
</body>
</html>