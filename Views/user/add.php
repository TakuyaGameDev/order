<?php
    // セッションスタート

use function PHPSTORM_META\type;

session_start();
    // クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    // フラグで表示条件変更
     $pageFlag = 0;
     if(!empty($_POST['btn_confirm']))
    {
        $pageFlag = 1;

    }
    if(!empty($_POST['btn_submit']))
    {
        $pageFlag = 2;

    }
    // xss対策
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- BootstrapのCSS読み込み -->
    <link href="bootstrapcss/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
</head>
<body>
    <h1 class="d-flex justify-content-center border-bottom p-3">新規登録</h1>
    <div class="d-flex justify-content-center bg-">
        <div class="d-flex flex-column w-50">
            <div class="m-5">

                <!-- 入力画面 -->
                <?php if($pageFlag === 0 ): ?>
                <!-- csrf対策　合言葉作成 -->
                <?php 
                if(!isset($_SESSION['csrfToken'])){
                    $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
                }
                $token = $_SESSION['csrfToken'];
                ?>
                    <form action="" method="post">
                        <table class="table table-bordered table-striped table-danger border border-4 rounded-3">
                            <tr>
                                <td class="text-center">店舗名：</td>
                                <td><input type="text" name="shop_name" value="<?php if(!empty($_POST['shop_name'])){echo h($_POST['shop_name']);} ?>" placeholder="東京駅前店" class="w-100 border-1 rounded-3"></td>
                            </tr>
                            <tr>
                                <td class="text-center">郵便番号：</td>
                                <td><input type="text" name="postal_code" value="<?php if(!empty($_POST['postal_code'])){echo h($_POST['postal_code']);} ?>" placeholder="111-1234" class="w-100 border-1 rounded-3"></td>
                            </tr>
                            <tr style="height: 200px;">
                                <td class="text-center">住所：</td>
                                <td><textarea type="text" name="address" value="<?php if(!empty($_POST['address'])){echo h($_POST['address']);} ?>" placeholder="東京都凸凹区〇〇-〇" style="height: 150px;" class="w-100 border-1 rounded-3"></textarea></td>
                            </tr>
                            <tr>
                                <td class="text-center">電話番号：</td>
                                <td><input type="text" name="tel" value="<?php if(!empty($_POST['tel'])){echo h($_POST['tel']);} ?>" placeholder="00-1234-5678" class="w-100 border-1 rounded-3"></td>
                            </tr>
                            <tr>
                                <td class="text-center">メールアドレス：</td>
                                <td><input type="email" name="email" value="<?php if(!empty($_POST['email'])){echo h($_POST['email']);} ?>" placeholder="tokyo@test.com" class="w-100 border-1 rounded-3"></td>
                            </tr>
                            <tr>
                                <td class="text-center">パスワード：</td>
                                <td><input type="password" name="password" value="<?php if(!empty($_POST['password'])){echo h($_POST['password']);} ?>" class="w-100 border-1 rounded-3"></td>
                            </tr>
                            <tr>
                                <td class="text-center">パスワード再入力：</td>
                                <td><input type="password" name="re_password" value="<?php if(!empty($_POST['re_password'])){echo h($_POST['re_password']);} ?>" class="w-100 border-1 rounded-3"></td>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-center">
                            <input type="hidden" name="csrf" value="<?php echo $token?>">
                            <a href="login.php" class="btn btn-secondary w-25 m-5">戻る</a>
                            <button type="submit" name="btn_confirm" value="登録" class="btn btn-primary w-25 m-5">登録</button>
                        </div>
                    </form>
                <?php endif;?>

                <!-- 確認画面 -->
                <?php if($pageFlag === 1 ): ?>
                <!-- 合言葉受信 -->
                <?php 
                    if($_POST['csrf'] === $_SESSION['csrfToken']):
                    require_once(ROOT_PATH .'Views/other/validation.php');
                ?>
                    <form action="" method="post">
                        <?php foreach($errors as $error){
                            echo '<p style="color: red;">'.$error.'<p>';
                        }
                        ?>
                        <table class="table table-bordered table-striped table-danger border border-4 rounded-3">
                            <tr>
                                <td class="text-center w-25">企業名：</td>
                                <td><?php echo h($_POST['name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">店舗名：</td>
                                <td><?php echo h($_POST['shop_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">郵便番号：</td>
                                <td><?php echo h($_POST['postal_code']) ?></td>
                            </tr>
                            <tr style="height: 200px;">
                                <td class="text-center">住所：</td>
                                <td><?php echo h($_POST['address']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">電話番号：</td>
                                <td><?php echo h($_POST['tel']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">メールアドレス：</td>
                                <td><?php echo h($_POST['email']) ?></td>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-center">
                            <button type="submit" name="back" value="戻る" class="btn btn-secondary w-25 m-5">戻る</button>
                            <button <?php if($count === 0){echo 'type="submit"';}else{echo 'type="button"';} ?> name="btn_submit" value="登録" class="btn btn-primary w-25 m-5">登録</button>
                            <input type="hidden" name="name" value="<?php echo h($_POST['name']) ?>">
                            <input type="hidden" name="shop_name" value="<?php echo h($_POST['shop_name']) ?>">
                            <input type="hidden" name="postal_code" value="<?php echo h($_POST['postal_code']) ?>">
                            <input type="hidden" name="address" value="<?php echo h($_POST['address']) ?>">
                            <input type="hidden" name="tel" value="<?php echo h($_POST['tel']) ?>">
                            <input type="hidden" name="email" value="<?php echo h($_POST['email']) ?>">
                            <input type="hidden" name="password" value="<?php echo h($_POST['password']) ?>">
                            <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']) ?>">
                        </div>
                    </form>
                <?php endif;?>
                <?php endif;?>

                <!-- 完了画面 -->
                <?php if($pageFlag === 2 ){
                        if($_POST['csrf'] === $_SESSION['csrfToken']){
                    
                            $name = h($_POST['name']);
                            $shop_name = h($_POST['shop_name']);
                            $postal_code = h($_POST['postal_code']);
                            $address = h($_POST['address']);
                            $tel = h($_POST['tel']);
                            $email = h($_POST['email']);
                            $password = h($_POST['password']);
                            // INSERT処理
                            require_once(ROOT_PATH .'/Controllers/ShopController.php');
                            $add = new ShopController();
                            $add->add();
                            // ログイン画面へ遷移
                            header('Location:login.php');
                            // 合言葉削除
                            unset($_SESSION['csrfToken']);
                        }
                } ?>
            </div>
        </div>
    </div>
</body>
</html>