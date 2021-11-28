<?php
session_start();

if(isset($_POST['email'])){
    $to = $_POST['email'];
    $subject = "パスワードの変更";
    $message = "テスト";
    $headers = "From: from@test.com";
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    mb_send_mail($to, $subject, $message, $headers); 
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード変更</title>
</head>
<body>
    <div class="d-flex justify-content-center mt-5">
        <div class="mt-5">
            <p class="fs-1 mt-5 mb-5">パスワードの再設定が必要となります。</p>
            <p class="d-flex justify-content-center fs-4">恐れ入りますが、登録されたメールアドレスをご入力いただき、<br>受信されたメールの案内にしたがってパスワードの再設定をお願いいたします。</p>
            <form action="" method="post" class="d-flex justify-content-center m-5 fs-5">
                <div class="">
                    <span>登録しているパスワード：</span>
                    <input type="email" name="email">
                    <div class="d-flex justify-content-between mt-5">
                        <a <?php if(isset($_SESSION['id'])){echo 'href="index.php"';}else{echo 'href="login.php"';} ?>> 戻る </a>
                        <button type="submit"> 送信 </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>