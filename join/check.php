<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, picture=?, created=NOW()');


   $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
   ));
    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit;
}

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>

    <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/application.css" />
    <link rel="stylesheet" href="../css/join.css" />
</head>
<body>
    <header>
            <h1>会員登録</h1>
        </header>
    <div class="container">
        
        
        <form action="" method="post">
            <input type=hidden name="action" value="submit">
            
            <dl>
                <dt>ニックネーム</dt>
                <dd>
                    <?php htmlspecialchars(print($_SESSION['join']['name']));?>
                </dd>
                <dt>メールアドレス</dt>
                <dd>
                    <?php htmlspecialchars(print($_SESSION['join']['email']));?>
                </dd>
                <dt>パスワード</dt>
                <dd>【表示されません】</dd>
                <dt>写真</dt>
                <dd>
                <?php if ($_SESSION['join']['image'] !== ''): ?>
                    <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>"　width="100" height="100"　alt="<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>">
                <?php endif; ?>
                </dd>
            </dl>
            <div class ="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-3"><button type="submit" >登録する</button></div>
            <div class="margin"></div>
		<div class="col-sm-3"><button type="button" onclick="location.href='index.php?action=rewrite'">確認画面に戻る</button></div>
		
	</div>
        </form>
    </div>
    
</body>
</html>

