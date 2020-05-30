<?php
session_start();
require('dbconnect.php');

// 不正アクセス or 1時間経過後のリダイレクト処理
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    // session有効時間更新
    $_SESSION['time'] = time();

    // ログインユーザ情報をDBから取得
    $users = $db->prepare('SELECT * FROM memos WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $user = $users->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    if ($_POST['message'] !== '') {
        // POST値をmemosテーブルに格納
        $message = $db->prepare('INSERT INTO memos SET user_id=?,
        message=?, created=NOW()');
        $message->execute(array(
            $user['id'],
            $_POST['message']
        ));

        // POSTの値を初期化
        header(('Location: index.php'));
        exit();
    }
}

// memosテーブルとusersテーブルの情報をリレーションして取得
$memos = $db->query('SELECT u.name, u.picture, m.* FROM users u, memos m WHERE u.id=m.user_id ORDER BY m.created DESC');
?>
<!DOCTYPE html>
<html lang="js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
      integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="css/application.css" />
    <link rel="stylesheet" href="index.css">
    <title>Twitter BBS</title>
</head>

<body>
    <div id="container">
        <form action="" method="post">
            <label for="message">
                <?php print(htmlspecialchars($user['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ
            </label>
            <textarea name="message" id="memo" cols="30" rows="10"></textarea>
            <input type="hidden" name="reply_message_id" value="" />
            <div>
                <p>
                    <input type="submit" value="投稿する" />
                </p>
            </div>
        </form>
    <!-- 取得したDB情報からforeachで一覧を表示 -->
    <?php foreach ($memos as $memo): ?>
        <div class="msg">
            <img src="img/<?php print(htmlspecialchars($memo['picuture'], ENT_QUOTES)); ?>" width="48" height="48"
            alt="<?php print(htmlspecialchars($memo['name'], ENT_QUOTES)); ?>" />
            <p>
                <?php print(htmlspecialchars($memo['message'], ENT_QUOTES)); ?>
                <span class="name">
                    【<?php print(htmlspecialchars($memo['name'], ENT_QUOTES)); ?>】
                </span>
                [<a href="index.php?res=">Re</a>]
            </p>
            <p class="day">
                <a href="view.php?id=">
                    <?php print(htmlspecialchars($memo['created'], ENT_QUOTES)); ?>
                </a>
                <a href="view.php?id=">返信元のメッセージ</a>
                [<a class="delete" href="delete.php">
                    削除
                </a>]
            </p>
        </div>
    <?php endforeach; ?>
    </div>
</body>

</html>
