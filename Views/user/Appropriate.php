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

    // 適正
    require_once(ROOT_PATH .'Controllers/OrderController.php');
    $order = new OrderController;
    // 適正挿入
    $order->AppropriateIn();
    // 商品1週間分適正抽出
    $params = $order->showAppropriate();
    // 使用量抽出
    $useds = $order->used_count();
    
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stock = new StockController();
    // 在庫抽出
    // $params = $stock->stock();
    // 適正実験
    $results = $order->AppropriateAll();
    // var_dump($results);
    
?>
<!-- Ajax -->
<script>

</script>
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
    <title>適正量</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/header.php') ?>
    <!-- テーブル -->
    <div class="w-100">
        <div class="">
            <form action="" method="post">
                <table class="table table-bordered table-striped table-danger border border-4">
                    <tr class="">
                        <th></th>
                        <th colspan="2" class="text-center"><?php echo $today."<br>"."先週使用量/適正量" ?></th>
                        <?php for ($i=1; $i <= 6; $i++) :?> 
                            <th colspan="2" class="text-center"><?php echo $ago[$i]."<br>"."先週使用量/適正量" ?></th> 
                        <?php endfor; ?>
                    </tr>
                    <?php for ($x=1; $x <= 10; $x++) :?>
                        <tr class="fs-5 text-center" >
                            <td style="width: 20%;"><?php echo $name[$x]; ?></td>
                            <?php for ($i=0; $i <= 6; $i++) :?>
                                <!-- 使用量 -->
                                <td style="width: 5%;"><?php echo $useds['used'][$x][$i] ?></td>
                                <!-- 適正量 -->
                                <td style="width: 6%;"><input type="number" class="Appropriate<?php echo $x.'_'.$i;?>" name="Appropriate<?php echo $x.'_'.$i;?>" style="width: 50px;" value="<?php if(isset($params['Appropriate'][$x-1][$i]['Appropriate_count'])){echo $params['Appropriate'][$x-1][$i]['Appropriate_count'];}else{echo 0;} ?>"></td>
                            <?php endfor; ?>                        
                        </tr>
                    <?php endfor; ?>
                </table>
                <div class="d-flex justify-content-end me-5">
                    <span class="me-5">※発注の前に入力してください。</span>
                    <button id="send" type="submit" class="btn btn-primary me-5">　登録　</button>
                </div>
            </form>
        </div>
    </div>  
</body>
</html>