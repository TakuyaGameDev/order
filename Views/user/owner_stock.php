<?php
    session_start();
    // ダイレクトアクセス防止
    if(empty($_SESSION['id']) || $_SESSION['id'] != 1){
        header('Location:login.php');
    }
    // 商品名読み込み
    require_once(ROOT_PATH .'Views/other/num_name.php');

    // コントローラ
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stock = new StockController;
    
    // 商品追加
    if(isset($_POST['name']) && isset($_POST['stock'])){
        $error = $stock->stock_add();
    }
    // 在庫数更新
    // $stock->stock_up();
    // 在庫抽出
    $params = $stock->stock();    
    
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
    <!--Ajax読み込み -->
    <script src="js/owner_ajax.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在庫管理</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/owner_header.php') ?>
    <!-- テーブル -->
    <table class="table table-bordered table-striped table-danger border border-4 fs-5 text-center">
        <tr style="height: 50px;">
            <th>商品名</th>
            <th>在庫数</th>
            <th>追加量</th>
            <th>追加</th>
            <th>削除</th>
            <th>変更</th>
        </tr>        
        <?php foreach($params['stock'] as $stock) : ?>           
            <tr class="id_<?php echo $stock['id']?>">
                <td><?php echo $stock['name'] ?></td>
                <td class="stock_count<?php echo $stock['id'] ?>"><?php echo $stock['stock_count'] ?></td>
                <!-- 在庫数追加 -->
                <td class="w-25"><input class="count_<?php echo $stock['id'] ?>" type="number"></td>
                <td><button type="button" class="stock_<?php echo $stock['id'] ?>" value="<?php echo $stock['id'] ?>">追加</button></td>
                <!-- 削除 -->
                <td><button type="button" class="delete_<?php echo $stock['id']?>" value="<?php echo $stock['id']?>">削除</button></td>
                <!-- 商品名変更 -->
                <form action="up.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $stock['id'] ?>">
                    <td><input type="submit" value="商品編集"></td>
                </form>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="6">======================商品追加======================</th>
        </tr>
        <tr>
            <th colspan="2">商品名</th>
            <th>在庫数</th>
            <th colspan="2">分類</th>
            <th>登録</th>
        </tr>

        <tr>
            <form action="" method="post">
                <td colspan="2"><input type="text" name="name" class="w-75"></td>
                <td><input type="number" name="stock"></td>
                <td colspan="2">
                <select name="class">
                <option value="1">コーヒー豆</option>
                <option value="2">ミルク</option>
                <option value="3">資材</option>
                <option value="4">食品</option>
                </td>
                <td><input type="submit" value="登録"></td>
            </form>
        </tr>
    </table>
    <?php if(isset($error)){echo '<p style="color:red;">'.$error.'</p>';}?>
</body>
</html>