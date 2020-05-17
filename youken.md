# Twitter BBS 要件

- 成果物： Twitterクローンを作ってみよう
これまで学習してきた知識を総動員してTwitterクローンを作ってみましょう。
→WebのBBSの形で模倣する

## できること
1. 会員登録ができる
2. ログインができる
3. 投稿できる
3. 一覧表示できる
3. ユーザーをプロフィールで検索できる。
3. ツイートを投稿できる
3. ツイートを表示できる
3. フォローとフォロー解除ができる<br>
→<font color=red>まずは1～4を再現する</font>

### インターフェイス切り分け
1. <font color=blue>会員登録ができる</font>
- 会員登録フォームを作成
    - 詳細: DB(名称未定)のテーブル(usersテーブル(仮))を作成
    - 会員登録フォーム(phpファイル)を作成
    - usersテーブルにフォーム入力値を登録する処理の追加
    - 登録フォームのバリデーションチェックを追加(null,DB存在しているemailaddressを入力していないかのチェック)
- ログイン画面に会員登録フォームへのリンクを追加
    - 詳細: 作成されているログイン画面のファイルをにリンクを追加する処理を行う
2. <font color=blue>ログインができる</font>
- ログイン画面の作成
    - ログイン画面(php)の作成
    - バリデーションチェック(DBにある値があるか、nullか、片方しか入力していないか)追加
    - ログイン処理に成功した場合、入力情報をsession_idに保持し、メイン画面に遷移させる処理を追加
    - ログインに失敗した場合、エラーメッセージを表示する処理を追加
3. <font color=blue>投稿できる</font>
- メイン画面に投稿ボタンを追加
- 投稿ページを作成
- DBに投稿用のテーブル(memosテーブル(仮))を作成
- 投稿ページで投稿ボタンを押した際、memosテーブルに投稿情報を登録する処理を追加
- バリデーションチェック(nullの場合エラー)
4. <font color=blue>一覧表示ができる</font>
- メインページで一覧を確認できる
- DBのmemosテーブルを取得し、一覧表示する処理を追加
- 一覧表示が1ページ5件で表示される処理を追加