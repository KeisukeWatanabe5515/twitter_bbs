<?php
session_start();

if(!empty($_POST)) {
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
    else if (strpos($email, '@') === false) {
            $error['email'] = 'format';
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
    
    // ここにアカウントの重複チェックを記入

    // 確認画面への以降処理
    if (empty($error)) {
        if (!empty($_FILES)) {        
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
    }

}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
</head>
<body>
<div class="container">
<h1>会員登録</h1>

<p>次のフォームに必要事項を入力して下さい</p>

<p class="error">
    <ul>
            <?php
            if ($error['name'] === 'blank') : ?>
                <li>ニックネームを入力してください</li>
            <?php endif; ?>
            <?php
            if ($error['email'] === 'blank') : ?>
                <li>メールアドレスを入力して下さい</li>
            <?php elseif ($error['name'] === 'format'): ?>
                <li>メールアドレスの形式が間違っています</li>
            <?php endif; ?>
            <?php
            if ($error['password'] === 'blank') : ?>
                <li>パスワードを入力して下さい</li>
            <?php elseif ($error['password'] === 'length'): ?>
                <li>パスワードは4文字以上20文字以内で入力して下さい</li>
            <?php elseif ($error['password'] === 'mismatch'): ?>
                <li>パスワードが一致していません</li>
            <?php endif; ?>
            <?php
            if ($error['image'] === 'type') : ?>
                <li>画像ファイルは拡張子が「jpg」「gif」「png」のものを指定して下さい</li>
            <?php endif; ?>
    </ul>
</p>

<form action="" method="POST" enctype="multipart/form-data">
    <dl>
        <dt>ニックネーム <span class="required">必須</span></dt>
        <dd>
            <input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($name)); ?>">
        </dd>

        <dt>メールアドレス <span class="required">必須</span></dt>
        <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email)); ?>">
        </dd>

        <dt>パスワード <span class="required">必須</span></dt>
        <dd>
            <input type="password" name="password" size="15" maxlength="20" value="">
        </dd>

        
        <dt>パスワード（確認）</dt>
        <dd>
            <input type="password" name="re_password" size="15" maxlength="20" value="">
        </dd>

        <dt>写真など</dt>
        <dd>
            <input type="file" name="image" size="35" value="test">
			<?php if(!empty($error) && empty($error['image'])) : ?>
			<p class="re_image">* 恐れ入りますが、画像をもう一度指定してください</p>
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