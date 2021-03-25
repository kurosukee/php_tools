# このツールについて

AWS S3のPOSTsフォームを発行します。

# 動作確認環境

* PHP version 7.3
* Composer version 2.0
* Postman

# パッケージインストール

`composer install`

# 環境依存設定値の変更

`.env` ファイルを変更する

# CLIで実行

`php main.php`

# フォームをPOSTする

1. Postmanを起動する
1. コマンド実行する
1. [URL]に実行結果のactionの値を貼り付ける
1. [メソッド]をPOST
1. [Body]タブを開き「form-data」を選択する
1. [Bulk Edit]を押し、実行結果の`acl` 〜 `X-Amz-Signature`行を貼り付ける
1. パラメータ `file` を追加し、型でファイルを選択し、ファイルを添付する
1. [Send]ボタンを実行する
