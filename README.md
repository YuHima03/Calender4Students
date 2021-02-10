# Calender for Students (名称未定)
**学生の為の機能が揃うカレンダー**を作ってます（現在開発中）<br />
Webアプリ（的なやつ）なのでアプリのインストールが不要です<br />
また、それぞれの端末から同じカレンダーを管理することも可能になる予定です
![theme_img](https://pbs.twimg.com/media/EsqoFtpWMAQUwEk?format=jpg&name=large)

## 進捗状況
###### 完成時期はまだ未定です
* 2021/02/07
  * ログイン、ログアウトの機構がほぼ完成
  * 仮登録アカウントたるものを作った
* 2021/02/11
  * アカウント作成の機構が半分ほど完成(メアド認証がまだ)

## 具体的な機能
###### 変更になる可能性があります
* 時間割の管理
  * これがこのカレンダーの一番の機能
* 時間割に合わせた課題の管理
  * 進捗状況も設定＆確認できるように
* 考査の管理
  * テスト期間や範囲の設定＆確認できるように
* 長期休暇の予定や課題の管理
* 学校行事の管理

## 備考
###### これも変更になる可能性があります
* 仮登録アカウントについて
  * ワンタップで登録不要で利用可能
  * ただし同一端末からのみ
  * 一部機能が使用不可
    * アカウント名の設定
    * 複数端末からの利用(仮登録アカウントでログアウトすると再ログインは出来ない)
* メアド登録について
  * パスワード忘却時にメアドに再設定リンクを送信できる
  
## 実は...
###### 裏話的な(?)
* 仮登録アカウントの情報もサーバーで保存される
  * 端末に保存することも考えたが、WebStorageで保存可能な容量を超えたら終わるので、という理由
  * アカウント情報もある(参照するために置いてある)
