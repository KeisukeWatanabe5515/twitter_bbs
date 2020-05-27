<?php
session_start();
require('dbconnect.php');

if (empty($_REQUEST['id'])) {
    header('Location: index.html');
    exit;
}

$memos = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, memos m WHERE u.id=m.user_id AND m.id=?');
$memos->execute(array($_REQUEST['id']));

$reply_memos = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, memos m WHERE u.id=m.user_id AND m.reply_message_id=?');
$reply_memos->execute(array($_REQUEST['id']));


?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/application.css" />
    <link rel="stylesheet" href="css/join.css" />
    <link rel="stylesheet" href="css/view.css" />
</head>

<body>
    <header>
        <h1>ひとこと掲示板</h1>
    </header>
    <div class="head">
      
    </div>

    <div class="container">
    <button type="button" onclick="location.href='index.php'">Back</button>
    <hr>
        <?php if ($memo = $memos->fetch()) : ?>
            <div class="profile">
                <?php if($memo['picture'] !== ""): ?>
                <div class="img">
                    <img src="member_picture/<?php print(htmlspecialchars($memo['picture'], ENT_QUOTES)); ?>" width="200" height="200">
                </div>
                <?php endif; ?>
                <div class="info">
                    <dl>
                        <dt>名前：<span><?php print(htmlspecialchars($memo['name'], ENT_QUOTES)) ?></span></dt>
                        <dd></dd>

                        <dt>投稿日時：<?php print(htmlspecialchars($memo['created'], ENT_QUOTES)) ?></dt>
                        <dd></dd>
                    </dl>
                </div>

            </div>
            <div class="msg">
                <p><?php print(htmlspecialchars($memo['message'], ENT_QUOTES)); ?></p>
            </div>

            <?php if ($_SESSION['id'] == $memo['user_id']): ?>
            <button type="button" class="btn-default">編集</button>
             <button type="button" class="btn-danger">削除</button>

            <?php else: ?>
                <button type="button" class="btn-default">返信</button>
               
            <?php endif; ?>

            <?php foreach($reply_memos as $reply_memo): ?>
                <div>
                <hr>
                <div class="profile">
                <?php if($reply_memo['picture'] !== ""): ?>
                    <div class="img"> 
                    <img src="member_picture/<?php print(htmlspecialchars($reply_memo['picture'], ENT_QUOTES)); ?>" width="100" height="100">
                    </div>
                    <?php endif ?>
                <div>
               <span><?php print(htmlspecialchars($reply_memo['created'], ENT_QUOTES) . ' ' .htmlspecialchars($reply_memo['name'], ENT_QUOTES)); ?></span> 
                <div class="msg">
                    
                    <?php print(htmlspecialchars($reply_memo['message'], ENT_QUOTES)); ?>
                </div>
            </div>
        </div>
            <?php endforeach; ?>




    </div>
<?php else : ?>
    <p>その投稿は削除されたか、URLが間違えています</p>
<?php endif; ?>

</body>

</html>