<?php
// $count_name = mb_strlen($_POST["name"]);
// $count_kana = mb_strlen($_POST["kana"]);
$errors = [];
$count = 0;


// 店舗名
if (empty($_POST["shop_name"])) {
    $errors[] = "＊店舗名が入力されていません";
    $count++;
}
// 郵便番号
if(empty($_POST["postal_code"])){
    $errors[] = "＊郵便番号が入力されていません";

}elseif(!preg_match("/^[0-9]{3}-[0-9]{4}$/",$_POST["postal_code"])){
    $errors[] = "＊郵便番号を正しく入力してください";
    $count++;         
}
// 住所
if (empty($_POST["address"])) {
    $errors[] = "＊住所が入力されていません";
    $count++;
}
// 電話番号
if(empty($_POST["tel"])){
    $errors[] = "＊電話番号が入力されていません";

}elseif(!preg_match("/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/",$_POST["tel"])){
    $errors[] = "＊電話番号を正しく入力してください";
    $count++;         
}

// メールアドレス
if(empty($_POST["email"])){
    $errors[] = "＊メールアドレスが入力されていません";
    $count++;
                
}elseif(!preg_match("/^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/",$_POST["email"])){
    $errors[] = "＊メールアドレスが正しく入力してください";
    $count++;        
}

// パスワード
if(empty($_POST["password"]) || empty($_POST['re_password'])){
    $errors[] = "＊パスワードかパスワード再入力が入力されていません";
    $count++;                      
} elseif($_POST["password"] !== $_POST['re_password']){
    $errors[] = "＊パスワードとパスワードが一致していません";
    $count++; 
}

// 送信できないメッセージ
if($count > 0){
    $errors[] = "＊正しく入力をできていない場合は登録できません";
}

?>