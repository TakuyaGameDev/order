<?php
    require_once(ROOT_PATH .'/Models/Db.php');

    class Stock extends Db {
        // テーブル定義
        private $table = 'stocks';

        public function __construct($dbh = null)
        {
            parent::__construct($dbh);
        }
        // 在庫一覧
        public function stock()
        {
            try{
                $sql = "SELECT * FROM ".$this->table." ORDER BY class";
                $sth = $this->dbh->prepare($sql);
                $sth->execute();
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);

                return $result;


            } catch (PDOException $e){
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }


        // 在庫数追加
        public function stock_up($stock_add,$id)
        {
            try{
                // トランザクション
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbh->beginTransaction();

                // 元の在庫を抽出
                $sql = "SELECT stock_count FROM ".$this->table." WHERE id = :id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();
                $old_counts = $sth->fetch(PDO::FETCH_ASSOC);

                $old_count = $old_counts['stock_count'];

                // 元の在庫に追加
                $new_count = $stock_add + $old_count;

                // 在庫数更新
                $sql = "UPDATE ".$this->table." SET stock_count = :new_count WHERE id = :id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':new_count', $new_count, PDO::PARAM_INT);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();

                
                $this->dbh->commit(); 
                
                return $new_count;

            } catch (PDOException $e){
                $this->dbh->rollBack();
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }

        // 商品追加
        public function stock_add($name,$count,$class)
        {
            try{
                // トランザクション
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbh->beginTransaction();

                // 重複確認
                $sql = "SELECT name FROM ".$this->table;
                $sth = $this->dbh->prepare($sql);
                $sth->execute();
                $results = $sth->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $result){
                    $names[] = $result['name'];
                } 

                if(in_array($name, $names)){

                    $error = "＊{$name}は存在します";
                    return $error;
                }else{
                    // 欠番探す
                    $sql = "SELECT MIN( id + 1 ) AS id FROM ".$this->table." WHERE ( id + 1 ) NOT IN ( SELECT id FROM ".$this->table.") ";
                    $sth = $this->dbh->prepare($sql);
                    $sth->execute();
                    $id = $sth->fetch(PDO::FETCH_ASSOC);

                    // 欠番へ挿入
                    $sql = "INSERT INTO ".$this->table."(id,name,stock_count,class,created_at) VALUES (:id,:name,:count,:class,now()) ";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $id['id'], PDO::PARAM_INT);
                    $sth->bindParam(':name', $name, PDO::PARAM_STR);
                    $sth->bindParam(':count', $count, PDO::PARAM_INT);
                    $sth->bindParam(':class', $class,PDO::PARAM_INT);
                    $sth->execute();
    
                    $this->dbh->commit();
                }

            } catch (PDOException $e){
                $this->dbh->rollBack();
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }

        // 商品名抽出
        public function findById($id)
        {
            try{
                $sql = "SELECT * FROM ".$this->table." WHERE id=:id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();
                $result = $sth->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e){
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }

        // 商品名変更
        public function up_name($name,$id,$class)
        {
            try{
                // トランザクション
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbh->beginTransaction();

                $sql = "UPDATE ".$this->table." SET name=:name,class=:class,updated_at=now() WHERE id=:id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':name', $name, PDO::PARAM_STR);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->bindParam(':class', $class, PDO::PARAM_INT);
                $sth->execute();

                // 変更後の名前抽出
                $sql = "SELECT * FROM ".$this->table." WHERE id=:id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();
                $result = $sth->fetch(PDO::FETCH_ASSOC);

                $this->dbh->commit();
                return $result;

            } catch (PDOException $e){
                $this->dbh->rollBack();
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }

        // 商品削除
        public function delete($id)
        {
            try{
                // トランザクション
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbh->beginTransaction();

                $sql = "DELETE FROM ".$this->table." WHERE id=:id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->execute();
            
                $this->dbh->commit();
                return $id;
            } catch (PDOException $e){
                $this->dbh->rollBack();
                echo "接続失敗：Stock.php :". $e->getMessage() . "\n";
                exit;
            }
        }


    }
    // if($stocks_id = 1){
                    //     $error[1] = 1;
                    // }
                    // if($stocks_id = 2){
                    //     $error[2] = 2;
                    // }
                    // if($stocks_id = 3){
                    //     $error[3] = 3;
                    // }
                    // if($stocks_id = 4){
                    //     $error[4] = 4;
                    // }
                    // if($stocks_id = 5){
                    //     $error[5] = 5;
                    // }
                    // if($stocks_id = 6){
                    //     $error[6] = 6;
                    // }
                    // if($stocks_id = 7){
                    //     $error[3] = 3;
                    // }
                    // if($stocks_id = 4){
                    //     $error[4] = 4;
                    // }