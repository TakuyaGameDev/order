<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Order extends Db{
    private $table = 'orders';

    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    // 当日在庫数挿入
    public function stocks_today($shops_id, $stocks_id, $shop_stock_count){

        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            // stocks_idが存在するか確認
            // $sql = "SELECT id FROM stocks WHERE id=:id";
            // $sth = $this->dbh->prepare($sql);
            // $sth->bindParam(':id', $stocks_id, PDO::PARAM_INT);
            // $sth->execute();
            // $stock_id = $sth->fetch(PDO::FETCH_ASSOC);
            
            // if(isset($stock_id)){
                // 今日の在庫と同数確認
                $date = date("Y-m-d", strtotime("0 day"));
                $sql = "SELECT id FROM ".$this->table." WHERE shops_id = :shops_id AND stocks_id = :stocks_id AND created_at = :date";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->bindParam(':date', $date, PDO::PARAM_STR);
                $sth->execute();
                $id = $sth->fetch(PDO::FETCH_ASSOC);
                
                if(empty($id)) { // データが空なら

                    // 在庫挿入
                    $sql = "INSERT INTO ".$this->table."(`shops_id`, `stocks_id`, `shop_stock_count`, `created_at`) VALUES (:shops_id,:stocks_id,:shop_stock_count,:date)";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                    $sth->bindParam(':shop_stock_count', $shop_stock_count, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();
                    
                } else {
                    // 存在するなら在庫アップデート
                    $sql = "UPDATE ".$this->table." SET shop_stock_count=:shop_stock_count WHERE id=:id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $id['id'], PDO::PARAM_INT);
                    $sth->bindParam(':shop_stock_count', $shop_stock_count, PDO::PARAM_INT);
                    $sth->execute();
                }

                // 計算の為、適正抽出
                $sql = "SELECT Appropriate_count FROM ".$this->table." WHERE created_at = :date And shops_id = :shops_id AND stocks_id = :stocks_id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->bindParam(':date', $date, PDO::PARAM_STR);
                $sth->execute();
                $Appropriates = $sth->fetch(PDO::FETCH_ASSOC);

                // 計算の為、前日発注数抽出
                $dated_1 = date("Y-m-d", strtotime("-1 day"));
                $sql = "SELECT order_count FROM ".$this->table." WHERE created_at = :dated_1 AND shops_id = :shops_id AND stocks_id = :stocks_id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->bindParam(':dated_1', $dated_1, PDO::PARAM_STR);
                $sth->execute();
                $orders = $sth->fetch(PDO::FETCH_ASSOC);
                
                // NULLの場合は0にする
                if($Appropriates == NULL){
                    $Appropriate = 0;
                } else {
                    $Appropriate = $Appropriates['Appropriate_count'];
                }
                if($orders == NULL){
                    $order = 0;
                } else {
                    $order = $orders['order_count'];
                }
                if($shop_stock_count == NULL){
                    $shop_stock_count = 0;
                }
                // 発注数計算
                $Appropriate_order = $Appropriate - $order - $shop_stock_count;
                // マイナスになったとき
                if ($Appropriate_order < 0) {
                    $Appropriate_order = 0;
                }

    // 処理追加
    // ここは違うテーブル
                // 倉庫在庫抽出
                $sql = "SELECT stock_count FROM stocks WHERE id = :stocks_id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->execute();
                $result = $sth->fetch(PDO::FETCH_ASSOC);

                // 元の在庫から発注数を引く
                $stock = $result['stock_count'];
                $stocked = $stock - $Appropriate_order;

                // 0ならreturnでエラー返す0挿入
                if($stocked < 0){
                    for ($x=1; $x <= 10; $x++) { 
                        if($stocks_id == $x){
                            $error[$x] = $x;
                        }
                    }
                    // 発注数アップデート
                    $sql = "UPDATE ".$this->table." SET order_count=0 WHERE created_at = :date AND shops_id=:shops_id AND stocks_id=:stocks_id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();

                    $this->dbh->commit(); 

                    return $error;
                    
                } else {
                    // 在庫更新
                    $sql = "UPDATE stocks SET stock_count=:stock_count WHERE id=:stocks_id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':stock_count', $stocked, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                    $sth->execute();

                    // 発注数アップデート
                    $sql = "UPDATE ".$this->table." SET order_count=:order_count WHERE created_at = :date AND shops_id=:shops_id AND stocks_id=:stocks_id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':order_count', $Appropriate_order, PDO::PARAM_INT);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();
                    
                    $this->dbh->commit(); 

                }
            // }   

        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }


    // 翌日以降の発注
    public function stocks_tomorrow($shops_id, $stocks_id, $order_count,$date){
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            // 日にちが存在するか
            $sql = "SELECT id FROM ".$this->table." WHERE created_at = :date And shops_id = :shops_id AND stocks_id = :stocks_id";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
            $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);
            $sth->execute();
            $id = $sth->fetch(PDO::FETCH_ASSOC);

            if(empty($id)){// データがNULLの場合
                // 発注数挿入
                $sql = "INSERT INTO ".$this->table."(`shops_id`, `stocks_id`, `order_count`, `created_at`) VALUES (:shops_id,:stocks_id,:order_count,:date)";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->bindParam(':order_count', $order_count, PDO::PARAM_INT);
                $sth->bindParam(':date', $date, PDO::PARAM_STR);
                $sth->execute();
                               
            } else {// データが残っている場合アップデート
                
                // 発注数アップデート
                $sql = "UPDATE ".$this->table." SET order_count=:order_count WHERE id=:id";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':order_count', $order_count, PDO::PARAM_INT);
                $sth->bindParam(':id', $id['id'], PDO::PARAM_INT);
                $sth->execute();
                
            }

            $this->dbh->commit(); 

        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：翌日以降発注Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }





    // 1週間＋前日在庫抽出
    public function showstock($shops_id){

        try{            
            for ($x=1; $x <= 10 ; $x++) { 
                for ($i=-1; $i <= 6; $i++) {
                    $date = date("Y-m-d", strtotime("$i day")); 
                    $sql = "SELECT shop_stock_count FROM ".$this->table." WHERE created_at = :date And shops_id = :shops_id AND stocks_id = :stocks_id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $x, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();
                    $result[$x][] = $sth->fetch(PDO::FETCH_ASSOC);
                }
            }
            return $result;

        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 1週間＋前日発注数抽出
    public function showorder($shops_id){

        try{
            for ($x=1; $x <= 10 ; $x++) { 
                for ($i=-1; $i <= 6; $i++) {
                    $date = date("Y-m-d", strtotime("$i day"));
                    $sql = "SELECT order_count FROM ".$this->table." WHERE created_at = :date And shops_id = :shops_id AND stocks_id = :stocks_id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $x, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();
                    $result[$x][] = $sth->fetch(PDO::FETCH_ASSOC);
                }
            }
            return $result;
            
        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 適正挿入
    public function AppropriateIn($shops_id,$stocks_id,$Appropriate_count,$date){
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();
                        
                // 同数かチェック
                $sql = "SELECT id FROM ".$this->table." WHERE shops_id = :shops_id AND stocks_id = :stocks_id AND created_at = :date";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                $sth->bindParam(':date', $date, PDO::PARAM_STR);
                $sth->execute();
                $id = $sth->fetch(PDO::FETCH_ASSOC);
                // データがNULLの場合
                if(empty($id)){
                                        
                    $sql = "INSERT INTO ".$this->table."(shops_id, stocks_id, Appropriate_count, created_at) VALUES (:shops_id,:stocks_id,:Appropriate_count,:date)";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':stocks_id', $stocks_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->bindParam(':Appropriate_count', $Appropriate_count, PDO::PARAM_INT);
                    $sth->execute();

                    $this->dbh->commit();
                                   
                // データが残っている場合アップデート
                } else {
                    
                    $sql = "UPDATE ".$this->table." SET Appropriate_count=:Appropriate_count WHERE id=:id";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $id['id'], PDO::PARAM_INT);
                    $sth->bindParam(':Appropriate_count', $Appropriate_count, PDO::PARAM_INT);
                    $sth->execute();

                    $this->dbh->commit();
                }
            
            
        
        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 適正抽出
    public function showAppropriate($shops_id){

        try{
            // 全ての商品1週間分
            for ($x=1; $x <= 10; $x++) { 
                for ($i=0; $i <= 6; $i++) { 
                    $date = date("Y-m-d", strtotime("$i day"));
                    $sql = "SELECT Appropriate_count FROM ".$this->table." WHERE created_at = :date And shops_id = :shops_id AND stocks_id = :x";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->execute();
                    $result[$x-1][] = $sth->fetch(PDO::FETCH_ASSOC);
                } 
            }
            return $result;

        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 発注履歴
    public function showorder_history($shops_id)
    {
       try{
           for ($x=1; $x <= 100; $x++) {
                for ($i=-7; $i <= -1; $i++) {
                    $date = date("Y-m-d", strtotime("$i day"));
                    $sql = "SELECT order_count FROM ".$this->table;
                    $sql .= " WHERE created_at = :date AND shops_id = :shops_id AND stocks_id = :x AND order_count IS NOT NULL ORDER BY id DESC LIMIT 1";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->execute();
                    $result[$x-1][] = $sth->fetch(PDO::FETCH_ASSOC);
               }
           }
            return $result;

       } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 管理者用発注一覧
    public function order_count($shops_id)
    {
        try{
            
            for ($x=1; $x <= 100; $x++) { 
                for ($i=0; $i <= 6; $i++) {
                    $date = date("Y-m-d", strtotime("$i day"));
                    $sql ="SELECT order_count FROM ".$this->table." WHERE created_at = :date AND shops_id = :shops_id AND stocks_id = :x";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':shops_id', $shops_id, PDO::PARAM_INT);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->bindParam(':date', $date, PDO::PARAM_STR);
                    $sth->execute();
                    $result[$x][] = $sth->fetch(PDO::FETCH_ASSOC);
                    
                }
            }

            return $result;

        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }

    // 使用量計算
    public function used_count($shop_id)
    {
        try{
            for ($x=1; $x <= 10; $x++) { 
                for ($i=-7; $i <= -1; $i++) { 
                    // 先週-1日在庫抽出
                    $i_1 = $i - 1;
                    $dated_1 = date("Y-m-d", strtotime("$i_1 day"));
                    $sql = "SELECT shop_stock_count FROM ".$this->table." WHERE shops_id=:id AND created_at=:date AND stocks_id=:x AND shop_stock_count IS NOT NULL ORDER BY id DESC LIMIT 1";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $shop_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $dated_1, PDO::PARAM_STR);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->execute();

                    $stocks_1 = $sth->fetch(PDO::FETCH_ASSOC);
                    if($stocks_1 == NULL){
                        $stock_1 = 0;
                    }else{
                        $stock_1 = $stocks_1['shop_stock_count'];
                    }
                    

                    // 先週-2発注抽出
                    $i_2 = $i - 2;
                    $dated_2 = date("Y-m-d", strtotime("$i_2 day"));
                    $sql = "SELECT order_count FROM ".$this->table." WHERE shops_id=:id AND created_at=:date AND stocks_id=:x AND order_count IS NOT NULL ORDER BY id DESC LIMIT 1";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $shop_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $dated_2, PDO::PARAM_STR);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->execute();

                    $orders_2 = $sth->fetch(PDO::FETCH_ASSOC);                    
                    if ($orders_2 == NULL) {
                        $order_2 = 0;
                    }else{
                        $order_2 = $orders_2['order_count'];
                    }

                    // 先週の在庫抽出
                    $dated = date("Y-m-d", strtotime("$i day"));
                    $sql = "SELECT shop_stock_count FROM ".$this->table." WHERE shops_id=:id AND created_at=:date AND stocks_id=:x AND shop_stock_count IS NOT NULL ORDER BY id DESC LIMIT 1";
                    $sth = $this->dbh->prepare($sql);
                    $sth->bindParam(':id', $shop_id, PDO::PARAM_INT);
                    $sth->bindParam(':date', $dated, PDO::PARAM_STR);
                    $sth->bindParam(':x', $x, PDO::PARAM_INT);
                    $sth->execute();

                    $stocks = $sth->fetch(PDO::FETCH_ASSOC);                    
                    if($stocks == NULL){
                        $stock = 0;
                    }else{
                        $stock = $stocks['shop_stock_count'];
                    }

                    $result = $stock_1 + $order_2 - $stock;
                    if($result < 0){
                        $result = 0;
                    }
                    $results[$x][] = $result;
                }
            }
            return $results;
                    
        } catch (PDOException $e){
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
    }
         

    public function AppropriateAll($id, $date, $date_1, $date_2, $date_3, $date_4, $date_5, $date_6)
    {
        try{
            // トランザクション
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();
            // 商品名抽出
            $sql = "SELECT id, name FROM stocks ORDER BY class";
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $results[0] = $sth->fetchAll(PDO::FETCH_ASSOC);

            // 適正量抽出
            $sql = "SELECT o.Appropriate_count FROM ".$this->table." o LEFT JOIN stocks s ON s.id = o.stocks_id ";
            $sql .= "WHERE shops_id=:id AND o.Appropriate_count IS NOT NULL ";
            $sql .= "AND o.created_at=:date OR o.created_at=:date_1 OR o.created_at=:date_2 OR o.created_at=:date_3 ";
            $sql .= "OR o.created_at=:date_4 OR o.created_at=:date_5 OR o.created_at=:date_6 ";
            $sql .= "ORDER BY o.created_at,s.class";
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);
            $sth->bindParam(':date_1', $date_1, PDO::PARAM_STR);
            $sth->bindParam(':date_2', $date_2, PDO::PARAM_STR);
            $sth->bindParam(':date_3', $date_3, PDO::PARAM_STR);
            $sth->bindParam(':date_4', $date_4, PDO::PARAM_STR);
            $sth->bindParam(':date_5', $date_5, PDO::PARAM_STR);
            $sth->bindParam(':date_6', $date_6, PDO::PARAM_STR);
            $sth->execute();
            $results[1] = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            // var_dump($results);
            
        //　使用数計算
            // 先週-1日在庫抽出
            // $sql = "SELECT o.shop_stock_count FROM ".$this->table." o LEFT JOIN stocks s ON s.id = o.stocks_id ";
            // $sql .= "WHERE shops_id=:id AND o.shop_stock_count IS NOT NULL AND o.created_at=:date ";
            // $sql .= "GROUP BY o.stocks_id ORDER BY s.class,o.stocks_id";
            // $sth = $this->dbh->prepare($sql);
            // $sth->bindParam(':id', $id, PDO::PARAM_INT);
            // $sth->bindParam(':date', $dated_1, PDO::PARAM_STR);
            // $sth->execute();
            // $stocks = $sth->fetchAll(PDO::FETCH_ASSOC);
            // foreach($stocks as $stock){
            //     $stock = $stock['shop_stock_count'];
            // }
            // var_dump($stock);

            // $stocks_1 = $sth->fetch(PDO::FETCH_ASSOC);
            // if($stocks_1 == NULL){
            //     $stock_1 = 0;
            // }else{
            //     $stock_1 = $stocks_1['shop_stock_count'];
            // }
            
            $this->dbh->commit();

            return $results;
        
        } catch (PDOException $e){
            $this->dbh->rollBack();
            echo "接続失敗：Order.php :". $e->getMessage() . "\n";
            exit;
        }
        
    }

}