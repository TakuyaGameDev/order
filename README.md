# order:family_man_girl_boy:
PHP自作（大手飲食店向け発注サイト）


## 使い方説明:fountain_pen:
#### XAMPPのhtdocsにorderファイルをいれて、ローカルホストで接続してください。
ログイン画面は統一されていますがユーザを店舗とオーナー（倉庫側）にわけており、それぞれ機能が変わっています。

### 1.オーナー側でログインする場合
#### 商品登録・削除・編集、各店舗の発注数を確認ができます。
- メールアドレス：owner@test.com
- パスワード：password

### 2.店舗でログインする場合
#### 商品発注・適正、パスワードの変更ができます。
- メールアドレス：
- パスワード：password

## 環境:computer:
XAMPP/MySQL/PHP

## データベース:globe_with_meridians:
- データベース名：orderapp
- お使いのphpMyAdminに上記ファイル（orderappDB.sql）をインポートしていただければお使いになれると思います。
