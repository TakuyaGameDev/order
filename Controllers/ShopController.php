<?php
require_once(ROOT_PATH .'/Models/Shop.php');

class ShopController {
    private $request; //リクエストパラメータ（POST)
    private $Shop; //Shopモデル

    public function __construct()
    {
        // リクエスト
        $this->request['post'] = $_POST;
        //モデルオブジェクトの生成
        $this->Shop = new Shop();
    }

    //ログイン
    public function login(){
        if(!empty($this->request['post']['email'])){
            $error = $this->Shop->logincheck($this->request['post']['email'],$this->request['post']['password']);
            $params = [
                'error' => $error
            ];
            return $params;
        }
    }

    // 新規登録
    public function add(){
        $this->Shop->Insert();
    }

    //ユーザマイページ
    public function index(){
        $shop = $this->Shop->findById($_SESSION['id']);
        $params = [
            'shop' =>$shop
        ];
        return $params;
    }

    // パスワード変更
    public function passcha(){
        if($this->request['post']['password'] === $this->request['post']['re_password']){

            if(!empty($this->request['post']['email'])){
                $this->Shop->passwordchange($this->request['post']['email'],$this->request['post']['password']);
            }

        } else {
            echo '<p style="color: red;" class="d-flex justify-content-center mt-5">＊パスワードが再入力と違います。</p>';
        }
    }

    // 管理者用店舗一覧
    public function findAll(){
        $shops = $this->Shop->findAll();
        $params = [
            'shops' => $shops
        ];
        return $params;
    }
    // 管理者用顧客詳細
    public function shop(){
        $shop = $this->Shop->findById($_POST['id']);
        $params = [
            'shop' =>$shop
        ];
        return $params;
    }

    // flgチェック
    public function flg($id)
    {
        $result_d = $this->Shop->flg($id);
        $params = [
            'result_d' => $result_d
        ];
        return $params;
    }

    // フラグリセット
    public function flg_reset()
    {
        $result = $this->Shop->flg_reset();
        $params = [
            'result' => $result
        ];
        return $params;
    }

    // パスワードリセット
    public function reset($email)
    {
        
    }
}