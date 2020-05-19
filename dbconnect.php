<?php
try {
    $db = new PDO('mysql:dbname=hirokei_db01;host=localhost;charset=utf8', 'user01', 'shusaku726');

} catch(PDOException $e) {
    print('DB接続エラー:' . $e->getMessage());
}