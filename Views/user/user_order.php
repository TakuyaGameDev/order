<?php
    session_start();
    // ダイレクトアクセス防止
    if(empty($_SESSION['id']) || $_SESSION['id'] == 1){
        header('Location:login.php');
    }
    // 商品名読み込み
    require_once(ROOT_PATH .'Views/other/num_name.php');

    // 日付
    require_once(ROOT_PATH .'Views/other/date.php');
    
    // コントローラ
    require_once(ROOT_PATH .'Controllers/OrderController.php');
    $order = new OrderController();
    // 在庫挿入&翌日以降発注
    $errors = $order->order();
    // 在庫数一覧
    $stock_count = $order->showstock();
    
    // 発注数一覧
    $order = $order->showorder();
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stock = new StockController;

    // 商品一覧
    $names = $stock->stock();

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
    <title>発注</title>
</head>
<body>
<!-- ヘッダー -->
<?php require_once(ROOT_PATH .'Views/layout/header.php') ?>
    <!-- テーブル -->
    <div class="w-100">
        <div class="">
            <form action="" method="post">
                <table class="table table-bordered table-striped table-danger border border-4 rounded-3">
                    <tr>
                        <th></th>
                        <th class="text-center pe-0 ps-0"><?php echo $before[1]."<br>"."発注数" ?></th>
                        <th colspan="2" class="text-center"><?php echo $today."<br>"."在庫数/発注数" ?></th>
                        <?php for ($i=1; $i <= 6; $i++) :?> 
                            <th class="text-center"><?php echo $ago[$i]."<br>"."発注数"  ?></th> 
                        <?php endfor; ?>
                    </tr>
                    <?php for ($x=1; $x <=100; $x++) :?>
                        <?php if(empty($names['stock'][$x-1]['name'])) :?>
                            <?php continue ?>
                        <?php endif; ?>
                        <tr class="fs-5 text-center" >
                            <!-- 商品名 -->
                            <td style="width: 20%;"><?php echo $names['stock'][$x-1]['name']; ?></td>
                            <!-- 前日発注数 -->
                            <td><?php if(isset($order['order'][$x][0]['order_count'])){ echo $order['order'][$x][0]['order_count']; }elseif(empty($order['order'][$x][0]['order_count'])){ echo 0; } ?></td>
                            <!-- 当日在庫と発注 -->
                            <?php for ($i=0; $i <= 0; $i++) :?>
                                <td style="width: %; "><input type="number" name="stock<?php echo $x.'_'.$i;?>" style="width: 50px;" value="<?php if(isset($stock_count['stock'][$x][$i+1]['shop_stock_count'])){echo $stock_count['stock'][$x][$i+1]['shop_stock_count'];}else{echo 0;} ?>"></td>
                                <td style="width: %;"><input type="number" name="order<?php echo $x.'_'.$i;?>" style="width: 50px; <?php if(isset($errors['errors'][$x][$x])){ echo 'border: 2px solid red;';} ?>" value="<?php if(isset($order['order'][$x][$i+1]['order_count'])){echo $order['order'][$x][$i+1]['order_count'];}else{echo 0;} ?>"></td>
                            <?php endfor; ?> 
                            <!-- 翌日以降 -->
                            <?php for ($i=1; $i <= 6; $i++) :?>
                                <td style="width: %;"><input type="number" name="order<?php echo $x.'_'.$i;?>" style="width: 50px;" value="<?php if(isset($order['order'][$x][$i+1]['order_count'])){echo $order['order'][$x][$i+1]['order_count'];}else{echo 0;} ?>"></td>
                            <?php endfor; ?>                       
                        </tr>
                    <?php endfor; ?>
                </table>
                <div class="d-flex justify-content-end me-5">
                    <span class="me-3">※当日在庫数は適正量を登録後に入力してください。
                        <br>　翌日以降の発注数は入力可能です。
                        <br>　赤枠のところは在庫がない為、0になります。
                    </span>
                    <button type="submit" class="btn btn-primary" style="height: 50px;">　登録　</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>