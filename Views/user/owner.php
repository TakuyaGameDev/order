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

    // 店舗一覧
    require_once(ROOT_PATH .'Controllers/ShopController.php');
    $shops = new ShopController();
    $params = $shops->findAll();
    
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
    <!-- アイコン -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <!--Ajax check.js読み込み -->
    <script src="js/owner_ajax.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者マイページ</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/owner_header.php') ?>
    <!-- テーブル -->
    <div class="d-flex justify-content-center mt-5">
        <div class=" w-75">
            <table class="table table-bordered table-striped table-danger border border-4 text-center">
                <tr>
                    <th>店舗名</th>
                    <th>店舗詳細</th>
                    <th>発注詳細</th>
                    <th>準備完了</th>
                </tr>
                <?php foreach($params['shops'] as $shop) : ?>
                <tr>
                    <td><?php echo $shop['shop_name'] ?></td>
                    <form action="shop.php" method="post">
                        <td><input type="submit" class="" value="店舗詳細"></td>
                        <input type="hidden" name="id" value="<?php echo $shop['id'] ?>">
                    </form>
                    <form action="shop_order.php" method="post">
                        <td><input type="submit" class="" value="発注詳細"></td>
                        <input type="hidden" name="id" value="<?php echo $shop['id'] ?>">
                    </form>
                    <!-- checkボタン -->
                    <td>                
                        <button type="button" class="btn_<?php echo $shop['id']; ?> re" value="<?php echo $shop['id']; ?>">
                            <!-- 自分がチェックした投稿にはチェックのスタイルを常に保持する -->
                            <i class="fa-check-square fa-lg px-16<?php
                                if($shop['flg'] == 1){ //チェック押したらチェックが塗りつぶされる
                                    echo ' fas active';
                                }else{ //チェックを取り消したら塗りつぶしのスタイルが取り消される
                                    echo ' far';
                                }; ?>"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <!-- リセットボタン -->
            <div class=" d-flex justify-content-end m-5">
                <button type="button" class="reset btn btn-primary" value="0">リセット</button>
            </div>
        </div>
    </div>
</body>
</html>