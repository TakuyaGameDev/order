<?php
    session_start();
    // ダイレクトアクセス防止
    if(empty($_SESSION['id']) || $_SESSION['id'] == 1){
        header('Location:login.php');
    }

    // 日付
    require_once(ROOT_PATH .'Views/other/date.php');

    // コントローラ
    require_once(ROOT_PATH .'Controllers/OrderController.php');
    $order = new OrderController();
    // コントローラ
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stock = new StockController;

    // 商品一覧
    $params = $stock->stock();
    // 発注履歴
    $history = $order->showorder_history();
       

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
    <title>発注履歴</title>
</head>
<body>
<!-- ヘッダー -->
<?php require_once(ROOT_PATH .'Views/layout/header.php') ?>
    <!-- テーブル -->
    <div class="w-100">
        <div class="">
            <table class="table table-bordered table-striped table-danger border border-4 rounded-3">
                <tr>
                    <th></th>
                    <?php for ($i=7; $i >= 1; $i--) :?> 
                        <th class="text-center"><?php echo $before[$i]."<br>"."発注数"  ?></th> 
                    <?php endfor; ?>
                </tr>
                <?php for ($x=0; $x <= 100; $x++) :?>
                    <?php if(empty($params['stock'][$x]['name'])) :?>
                        <?php exit ?>
                    <?php endif; ?>
                    <tr class="fs-5 text-center" >
                        <td style="width: 20%;"><?php echo $params['stock'][$x]['name']; ?></td>
                        <?php for ($i=0; $i <= 6; $i++) :?>
                            <td style="width: 11%;"><?php if(isset($history['history'][$x][$i]['order_count'])){echo $history['history'][$x][$i]['order_count'];}else{echo 0;}?></td>
                        <?php endfor; ?>                        
                    </tr>
                <?php endfor; ?>
            </table>
            <div class="d-flex justify-content-end">
                <a href="index.php" class="btn btn-primary me-5" role="button">　戻る　</a>
            </div>
        </div>
    </div>
</body>
</html>