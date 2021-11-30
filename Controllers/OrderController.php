<?php
require_once(ROOT_PATH .'/Models/Order.php');

class OrderController {
    private $request; //リクエストパラメータ（POST)
    private $Order; //Orderモデル

    public function __construct()
    {
        // リクエスト
        $this->request['post'] = $_POST;
        //モデルオブジェクトの生成
        $this->Order = new Order();
    }
    
    // 在庫挿入→発注表示まで
    public function order(){
        
        $stock = 'stock';
        $order = 'order';
        $under = '_';
        
        for ($x=1; $x <= 100; $x++) { 
            for ($i=0; $i <= 0; $i++) {
                if(isset($_POST["$stock$x$under$i"])){
                    if($_POST["$stock$x$under$i"] < 0){
                        $_POST["$stock$x$under$i"] = 0;
                    }
                    $errors[$x] = $this->Order->stocks_today($_SESSION['id'],$x,$_POST["$stock$x$under$i"]);
                    $params = [
                        'errors' => $errors
                    ];
                }
            }
        }
        for ($x=1; $x <= 100; $x++) { 
            for ($i=1; $i <= 6; $i++) {
                if(isset($_POST["$order$x$under$i"])){
                    if($_POST["$order$x$under$i"] < 0){
                        $_POST["$order$x$under$i"] = 0;
                    }
                    $date = date("Y-m-d", strtotime("$i day"));
                    $this->Order->stocks_tomorrow($_SESSION['id'],$x,$_POST["$order$x$under$i"],$date);
                }
            }
        }
        if(isset($params['errors'])){
            return $params;
        }
        
    }

    // 1週間＋前日の在庫抽出
    public function showstock(){
        
        $stock = $this->Order->showstock($_SESSION['id']);
        $params = [
            'stock' => $stock
        ];
        return $params;
    }

    // 1週間＋前日の発注抽出
    public function showorder(){
        $order = $this->Order->showorder($_SESSION['id']);
        $params = [
            'order' => $order
        ];
        return $params;
    }

    // 適正挿入
    public function AppropriateIn(){

        $Appropriate = 'Appropriate';
        $under = '_';

        for ($x=1; $x <= 10; $x++) { 
            for ($i=0; $i <= 6; $i++) {
                if(isset($_POST["$Appropriate$x$under$i"])){
                    if($_POST["$Appropriate$x$under$i"] < 0){
                        $_POST["$Appropriate$x$under$i"] = 0;
                    }
                    
                    $date = date("Y-m-d", strtotime("$i day"));
                    $this->Order->AppropriateIn($_SESSION['id'],$x,$_POST["$Appropriate$x$under$i"],$date);            
                }
            }
        }
    }

    // 適正表示
    public function showAppropriate(){

        $Appropriate = $this->Order->showAppropriate($_SESSION['id']);
        $params = [
            'Appropriate' => $Appropriate
        ];
        return $params;
    }

    // 発注履歴
    public function showorder_history()
    {
        $history = $this->Order->showorder_history($_SESSION['id']);
        $params = [
            'history' => $history
        ];
        return $params;
    }

    // 管理者用発注一覧
    public function order_count()
    {
        
        $order_count = $this->Order->order_count($_POST['id']);
        $params = [
            'order_count' => $order_count
        ];
        return $params;
            
    }

    // 使用量抽出
    public function used_count()
    {        
        $used = $this->Order->used_count($_SESSION['id']);
        $params = [
            'used' => $used
        ];
        return $params;    
    }

    // 適正
    public function AppropriateAll()
    {
        $date = date("Y-m-d", strtotime("0 day"));
        $date_1 = date("Y-m-d", strtotime("1 day"));
        $date_2 = date("Y-m-d", strtotime("2 day"));
        $date_3 = date("Y-m-d", strtotime("3 day"));
        $date_4 = date("Y-m-d", strtotime("4 day"));
        $date_5 = date("Y-m-d", strtotime("5 day"));
        $date_6 = date("Y-m-d", strtotime("6 day"));
        $requests = $this->Order->AppropriateAll($_SESSION['id'], $date, $date_1, $date_2, $date_3, $date_4, $date_5, $date_6);
        $params=[
            'result' => $requests
        ];
        return $params;
    }
    
}