<?php
require_once(ROOT_PATH .'/Models/Stock.php');

class StockController {
    private $request; //リクエストパラメータ（POST)
    private $Stock; //Shopモデル

    public function __construct()
    {
        // リクエスト
        $this->request['post'] = $_POST;
        //モデルオブジェクトの生成
        $this->Stock = new Stock();
    }

    // 在庫抽出
    public function stock(){
        $stock = $this->Stock->stock();
        $params = [
            'stock' => $stock
        ];
        return $params;
    }

    // 在庫更新
    public function stock_up($count,$id)
    {
        $result = $this->Stock->stock_up($count,$id);
        return $result;
    }

    // 商品追加
    public function stock_add()
    {
        $result = $this->Stock->stock_add($_POST['name'],$_POST['stock'],$_POST['class']);
        return $result;
    }
    // 商品1つ抽出
    public function findById(){
        $result = $this->Stock->findById($_POST['id']);
        return $result;
    }
    // 商品名変更
    public function up_name($name,$id,$class){
        $result = $this->Stock->up_name($name,$id,$class);
        return $result;
    }
    // 商品削除
    public function delete($id)
    {
        $result = $this->Stock->delete($id);
        return $result;
    }
}