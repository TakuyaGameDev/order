<?php
session_start();
// header('Location:login.php');
// JSON形式はUTF-8じゃないとダメ
header("Content-type: application/json; charset=UTF-8");
// チェックボタンオンオフ
if(isset($_POST['id'])){
    require_once(ROOT_PATH .'Controllers/ShopController.php');
    $shops = new ShopController();
    $param = $shops->flg($_POST['id']);
    
    echo json_encode($param); // 通信を成功させるためにとりあえず返している
}
// チェックボタンリセット
if(isset($_POST['reset'])){
    require_once(ROOT_PATH .'Controllers/ShopController.php');
    $shops = new ShopController();
    $reset = $shops->flg_reset();

    echo json_encode($reset);
}
// 商品削除
if(isset($_POST['delete'])){
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stocks = new StockController();
    $delete = $stocks->delete($_POST['delete']);

    echo json_encode($delete);
}
// 商品編集
if(isset($_POST['stock_name'])){
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stocks = new StockController();
    $update = $stocks->up_name($_POST['stock_name'],$_POST['stock_id'],$_POST['stock_class']);

    echo json_encode($update);
}
// 在庫数更新
if(isset($_POST['stock_count'])){
    require_once(ROOT_PATH .'Controllers/StockController.php');
    $stocks = new StockController();
    $up = $stocks->stock_up($_POST['stock_count'],$_POST['s_id']);

    echo json_encode($up);
}
