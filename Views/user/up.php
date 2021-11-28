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

    // 商品名抽出
    $param = $stock->findById();
    if($param['class'] == 1){
        $class = 'コーヒー豆';
    }elseif($param['class'] == 2){
        $class = 'ミルク';
    }elseif($param['class'] == 3){
        $class = '資材';
    }elseif($param['class'] == 4){
        $class = '食品';
    }
       
    
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
    <title>商品編集</title>
</head>
<body>
    <!-- ヘッダー -->
    <?php require_once(ROOT_PATH .'Views/layout/owner_header.php') ?>
    <!-- テーブル -->
    <div class=" d-flex justify-content-center">
        <form action="" method="POST" class="w-50 m-5">
            <table class="table table-bordered table-striped table-danger border border-4 fs-5 text-center">
                <tr>
                    <th>商品名</th>
                    <th>分類</th>
                </tr>
                <tr>
                    <td class="change_name"><?php echo $param['name'] ?></td>
                    <td class="change_class"><?php echo $class ?></td>
                </tr>
                <tr>
                    <th colspan="2">===========変更後===========</th>
                </tr>
                <tr>
                    <td><input type="text" name="name" class="name w-75"></td>
                    <td>
                    <select name="class" class="class">
                    <option value="1">コーヒー豆</option>
                    <option value="2">ミルク</option>
                    <option value="3">資材</option>
                    <option value="4">食品</option>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-end me-5">
                <span><?php if(isset($error)){echo '<p style="color:red;">'.$error.'</p>';} ?></span>
                <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
                <button type="button" class="update btn btn-primary me-5" value="<?php echo $_POST['id'] ?>">登録</button>
            </div>
            
        </form>
    </div>
    
</body>
</html>