<?php
session_start();
    // DB接続
    require('dbconnect.php');

    if (!empty($_POST)) {
      if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        // DB問い合わせ処理
        $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
        $login->execute(array(
          $_POST['email'],
          $_POST['password']
        ));
        $user = $login->fetch();

        if ($user) {
          $_SESSION['id'] = $user['id'];
          $_SESSION['time'] = time();

          header('Location: login.php');
          exit();
        } else {
          $error['login'] = 'failed';
        }
      } else {
        $error['login'] = 'blank';
      }
    }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
      integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="css/application.css" />

    <title>Twitter BBS</title>
  </head>
  <body>
    <h1>Twitter BBS Login</h1>
    <h2>ログイン情報を入力してください。
        <!-- 会員登録ページへ遷移 -->
        <a href="#">会員登録はこちら</a></h2>
    <div class="container">
      <form action="" method="post">
        <div class="">
          <label for="email">
            メールアドレス
          </label>
          <input type="mailaddress" name="email" value="<?php
          print(htmlspecialchars($_POST['email'], ENT_QUOTES));
          ?>" />
          <?php
          if ($error['login'] === 'blank'): ?>
          <p class="error">*　メールアドレスとパスワードを正しく入力してください。</p>
          <?php endif; ?>
          <?php
          if ($error['login'] === 'failed'): ?>
          <p class="error">*　ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>
        </div>
        <div class="">
          <label for="password">
            パスワード
          </label>
          <input type="password" name="password" value="<?php
          print(htmlspecialchars($_POST['password'], ENT_QUOTES));
          ?>"/>
        </div>
            <input type="submit" value="ログイン"/>
      </form>
    </div>
  </body>
</html>
