<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
    // 名前の判定
    $name = str_replace(array(' ', '　'), '', $_POST['name']);

    if($name === "") {
        $error['name'] = 'blank';
    }

    // メールアドレスの判定
    $email = str_replace(array(' ', '　'), '', $_POST['email']);

    if ($email === "") {
        $error['email'] = 'blank';
    } 
 

    // パスワードの判定
    $password = str_replace(array(' ', '　'), '', $_POST['password']);

    $re_password = str_replace(array(' ', '　'), '', $_POST['re_password']);
   
    if ($password === "") {
            $error['password'] = 'blank';
    }
    else if (strlen($password) < 4) {
        $error['password'] = 'length';
    }
    else if ($password !== $re_password) {
        $error['password'] = 'mismatch';
    }

    // 画像ファイルの判定
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }
    
    // アカウントの重複チェック
    if (empty($error)) {
       $member = $db->prepare('SELECT COUNT(*) AS cnt FROM users where email=?');

       $member->execute(array($email));
       $record = $member->fetch();
       if ($record['cnt'] > 0) {
           $error['email'] = 'duplicate';
       }

    }

    // 確認画面への以降処理
    if (empty($error)) {
        if (!empty($_FILES['image']['name'])) {        
            $ext = substr($fileName, -3);
            $image = date('YmdHis') . '.' . $ext;

            move_uploaded_file($_FILES['image']['tmp_name'],
		'../member_picture/' . $image);
        } else {
            $image = "";
        }
        
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;

        header('Location: check.php');
        exit();
    }

    


}
if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
    $name = str_replace(array(' ', '　'), '', $_POST['name']);
    $email = str_replace(array(' ', '　'), '', $_POST['email']);
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
  

<p>次のフォームに必要事項を入力して下さい</p>
            <?php
            if ($error['name'] === 'blank') : ?>
                <div class="error"> * ニックネームを入力して下さい</div>
            <?php endif; ?>
            <?php
            if ($error['email'] === 'blank') : ?>
                <div class="error">* メールアドレスを入力して下さい</div>
            <?php elseif ($error['email'] === 'duplicate'): ?>
                <div class="error">* 指定したメールアドレスは、既に登録されています</div>
            <?php endif; ?>
            <?php
            if ($error['password'] === 'blank') : ?>
                <div class="error">* パスワードを入力して下さい</div>
            <?php elseif ($error['password'] === 'length'): ?>
                <div class="error">* パスワードは4文字以上20文字以内で入力して下さい</div>
            <?php elseif ($error['password'] === 'mismatch'): ?>
                <div class="error">* パスワードが一致していません</div>
            <?php endif; ?>
            <?php
            if ($error['image'] === 'type') : ?>
                <div class="error">* 画像ファイルは拡張子が「jpg」「gif」「png」のものを指定して下さい</div>
            <?php endif; ?>

<form action="" method="POST" enctype="multipart/form-data">
        

        <dl>
        <dt><label for="name">ニックネーム <span class="badge badge-danger">必須</span></label> </dt>
        <dd><input id="name" type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($name)); ?>"></dd>

        <dt><label for="email">メールアドレス <span class="badge badge-danger">必須</span></label></dt>
        <dd><input id="email" type="email" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email)); ?>">
        </dd>

        <dt><label for="password">パスワード <span class="badge badge-danger">必須</span></label></dt>
        <dd><input id="password" type="password" name="password" size="15" maxlength="20" value=""></dt></dd>

        <dt><label for="re_passsword">パスワード(確認)</label>
            </dt>
        <dd><input id=re_password type="password" name="re_password" size="15" maxlength="20" value=""></dd>

        <dt><label for="image">写真など</label></dt>
            <dd><input id="image" type="file" name="image" size="35" value="test">
            <?php if(!empty($error) && !empty($_FILES['image']['name']) &&$error['image'] != "type") : ?>
                <p class="error">* 恐れ入りますが、画像をもう一度指定してください</p>
            <?php endif; ?>
            </dd>
        </dl>
    <div>
        <input type="submit" value="入力内容を確認する">
    </div>

</form>

</div>
    
</body>
</html>