<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Shop extends Db {
    // テーブル定義
    private $table = 'shops';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    // ログイン処理
    public function logincheck($email,$password){
        
        if(isset($_POST['email'])){

            try{
                $sql = "SELECT * FROM shops WHERE email=:email";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':email', $email, PDO::PARAM_STR);
                $sth->execute();

                // ログイン可否
                if($rows = $sth->fetch()){ //$rowsにデータが挿入出来たら

                    if(password_verify($password,$rows['password'])){
                        $_SESSION['id'] = $rows['id'];
                        if($_SESSION['id'] == 1){

                            header('Location:owner.php');
                        } else {
                            header('Location:index.php');
                            exit();
                        }
                        
                    } else {
                        $error = 1;
                        return $error;
                    }

                } else {
                    $error = 1;
                    return $error;
                }

            } catch (PDOException $e){
                echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
                exit;
            }
        }
    }

    // 新規登録
    public function Insert(){
        try{
            global $shop_name,$postal_code,$address,$tel,$email,$password;
            // パスワードハッシュ化
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO ".$this->table."(`shop_name`, `postal_code`, `address`, `tel`, `email`, `password`, `created_at`) VALUES (:shop_name,:postal_code,:address,:tel,:email,:password,now())";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':shop_name', $shop_name, PDO::PARAM_STR);
            $sth->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
            $sth->bindParam(':address', $address, PDO::PARAM_STR);
            $sth->bindParam(':tel', $tel, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $hash);
            $sth->execute();

        } catch (PDOException $e){
            echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // ユーザ用マイページ情報
    public function findById($id){
        try{
            $sql = "SELECT * FROM shops WHERE id=:id";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        } catch (PDOException $e){
            echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // パスワード変更
    public function passwordchange($email,$password){
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            $sql = "SELECT * FROM shops WHERE email=:email";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->execute();

            if($rows = $sth->fetch()){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE shops SET password=:password WHERE email=:email";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':password', $hash);
                $sth->bindParam(':email', $email, PDO::PARAM_STR);
                $sth->execute();

                // ログイン状況で振り分け
                if (isset($_SESSION['id'])) {
                    header('Location:index.php');

                } else {
                    header('Location:login.php');
                }

            } else {
                echo '<p style="color: red;" class="d-flex justify-content-center mt-5">＊メールアドレスが違います。</p>';
                return false;
            }

            $this->dbh->commit();

        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 管理者用店舗一覧
    public function findAll(){
        
        try{
            $sql = "SELECT * FROM ".$this->table." WHERE id >=2 ORDER BY name";
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    

    // フラグチェンジ
    public function flg($id){
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            // flg確認
            $sql = "SELECT flg FROM ".$this->table." WHERE id = :id";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);

            // 1の場合0にする
            if($result['flg'] == 1){
                $sql = "UPDATE ".$this->table." SET flg = 0 WHERE id = :id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();

            // 0の場合1にする
            } else {
                $sql = "UPDATE ".$this->table." SET flg = 1 WHERE id = :id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();
            }

            // flg確認
            $sql = "SELECT flg FROM ".$this->table." WHERE id = :id";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            $result_d = $sth->fetch(PDO::FETCH_ASSOC);
            

            $this->dbh->commit();

            return $result_d;

        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // フラグリセット
    public function flg_reset()
    {
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            $sql = "UPDATE ".$this->table." SET flg = 0";
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);

            $this->dbh->commit();

            return $result;

        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Shop.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // パスワードリセット
    public function reset($email)
    {
        $sql = "SELECT * FROM ".$this->table." WHERE email = :email";
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);
        $sth->execute();

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)){
            $to = $email;
            $subject = "パスワードの変更";
            $message = "テスト";
            $headers = "From: from@test.com";
            mb_language("Japanese");
            mb_internal_encoding("UTF-8");
            mb_send_mail($to, $subject, $message, $headers); 


        } else {
            $error = 1;
            return $error;
        }
    }

}