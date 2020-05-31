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
        message=?, reply_message_id=?, created=NOW()');
        $message->execute(array(
            $user['id'],
            $_POST['message'],
            $_POST['reply_memo_id']
        ));

        // POSTの値を初期化
        header(('Location: index.php'));
        exit();
    }
}

$page = $_REQUEST['page'];
// 不正パラメータ処理
if ($page == '') {
    $page = 1;
}
$page = max($page, 1);

// 現在のmemosテーブルの件数を取得
$counts = $db->query('SELECT COUNT(*) AS cnt FROM memos');
$cnt = $counts->fetch();
// 取得クエリから一覧のmasPageを計算
$masPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

// ページ数の計算(5の倍数)
$start = ($page - 1) * 5;


// memosテーブルとusersテーブルの情報をリレーションして取得
$memos = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, memos m WHERE u.id=m.user_id ORDER BY m.created DESC LIMIT ?,5');
$memos->bindParam(1, $start, PDO::PARAM_INT);
$memos->execute();

// 返信処理
if (isset($_REQUEST['res'])) {
    // 返信ユーザー情報をDB問い合わせ
    $response = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, memos m WHERE u.id=m.user_id AND m.id=?');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@' . $table['name'] . '' . $table['message'];
}
?>
<!DOCTYPE html>
<html lang="js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/application.css" />
    <link rel="stylesheet" href="index.css">
    <title>Twitter BBS</title>
</head>

<body>
    <div id="container">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="logout.php">ログアウト</a>
            </li>
        </ul>
        <div class="paging">
            <?php if ($page > 1) : ?>
                <div class="col-3"><a href="index.php?page=<?php print($page - 1); ?>"></a>前のページへ</div>
            <?php else : ?>
                <div class="col-3"><a href="index.php?page=<?php print($page - 1); ?>"></a>前のページへ</div>
            <?php endif; ?>

            <?php if ($page < $maxPage) : ?>
                <div class="col-3"><a href="index.php?page=<?php print($page + 1); ?>"></a>次のページへ</div>
            <?php else : ?>
                <div class="col-3"><a href="index.php?page=<?php print($page + 1); ?>"></a>次のページへ</div>
            <?php endif; ?>
        </div>
        <form action="" method="post">
            <label for="memo">
                <?php print(htmlspecialchars($user['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ
            </label>
            <textarea name="memo" id="memo" cols="30" rows="10">
            <?php print(htmlspecialchars($message, ENT_QUOTES)); ?>
            </textarea>
            <input type="hidden" name="reply_memo_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
            <div>
                <p>
                    <input type="submit" value="投稿する" />
                </p>
            </div>
        </form>
        <!-- 取得したDB情報からforeachで一覧を表示 -->
        <?php foreach ($memos as $memo) : ?>
            <div class="msg">
                <img src="img/<?php print(htmlspecialchars($memo['picuture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($memo['name'], ENT_QUOTES)); ?>" />
                <p>
                    <?php print(htmlspecialchars($memo['message'], ENT_QUOTES)); ?>
                    <span class="name">
                        【<?php print(htmlspecialchars($memo['name'], ENT_QUOTES)); ?>】
                    </span>
                    [<a href="index.php?res=<?php print(htmlspecialchars($memo['id'], ENT_QUOTES)); ?>">Re</a>]
                </p>
                <p class="day">
                    <a href="view.php?id=<?php print(htmlspecialchars($memo['id'], ENT_QUOTES)); ?>">
                        <?php print(htmlspecialchars($memo['created'], ENT_QUOTES)); ?>
                    </a>
                    <?php if ($memo['reply_message_id'] > 0) : ?>
                        <a href="view.php?id=<?php print(htmlspecialchars($memo['reply_message_id'], ENT_QUOTES)); ?>">返信元のメッセージ</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['id'] === $memo['user_id']) : ?>
                        [<a class="delete" href="delete.php?id=<?php print(htmlspecialchars($memo['id'], ENT_QUOTES)); ?>">
                            削除
                        </a>]
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
