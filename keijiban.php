<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>掲示板</title>
    <?php
    //mysqliクラスのオブジェクトを作成
    $mysqli = new mysqli('localhost', 'root', '', 'testdatabase');
    //エラーが発生したら
    if ($mysqli->connect_error){
      print("接続失敗：" . $mysqli->connect_error);
      exit();
    }
    ?>

  </head>
  <body>
    <center>
    <h1>
      掲示板
    </h1>

    <?php
      //commentがPOSTされているなら
      if(isset($_POST["comment"])){
        //エスケープしてから表示
        $hundlename = htmlspecialchars($_POST["handlename"]);
        $comment = htmlspecialchars($_POST["comment"]);
        print(" ${hundlename} さん。");
        print("あなたのコメントは「 ${comment} 」です。");
        //データベースに記入
        $stmt = $mysqli->prepare("INSERT INTO datas (HundleName,Comment) VALUES (?,?)");
        //データベースにに窮するデータ
        $stmt->bind_param('ss', $_POST["handlename"], $_POST["comment"]);
        //実行
        $stmt->execute();
        $mysqli->close();
        ?>
        <br>

                <form method="POST" action="Keijiban.php">
                  <p>名前</p><input name="handlename"/><br>
                    <p>コメント</p><textarea name="comment" rows="5" cols="50"/></textarea><br>
                  <input type="submit" value="送信" />
                      <p>投稿しました。</p><br>
                  <?php
                  //datasテーブルからCreatedDateの降順でデータを取得
                  $result = $mysqli->query("SELECT * FROM datas ORDER BY CreatedDate DESC");
                  if($result){
                    //1行ずつ取り出し
                    while($row = $result->fetch_object()){
                      //エスケープして表示
                      $name = htmlspecialchars($row->HundleName);
                      $message = htmlspecialchars($row->Comment);
                      //改行処理
                      if ( get_magic_quotes_gpc() ) {
                      $message = stripslashes( $message );
                    }
                    $message = nl2br($message);


                      $created = htmlspecialchars($row->CreatedDate);
                        print("$name : $message ($created)<br>");
                    }
                          $mysqli->close();
                  }
                        
                  ?>
                        </center>


<?php
      }else{
?>
        <form method="POST" action="Keijiban.php">
          <p>名前</p><input name="handlename"/><br>
            <p>コメント</p><textarea name="comment" rows="5" cols="50"/></textarea><br>
          <input type="submit" value="送信" />


      </form>
<?php
//datasテーブルからCreatedDateの降順でデータを取得
$result = $mysqli->query("SELECT * FROM datas ORDER BY CreatedDate DESC");
if($result){
  //1行ずつ取り出し
  while($row = $result->fetch_object()){
    //エスケープして表示
    $name = htmlspecialchars($row->HundleName);
    $message = htmlspecialchars($row->Comment);
    //改行処理
    if ( get_magic_quotes_gpc() ) {
    $message = stripslashes( $message );
  }
  $message = nl2br($message);


    $created = htmlspecialchars($row->CreatedDate);
      print("$name : $message ($created)<br>");
  }
        $mysqli->close();
}
      }
?>
      </center>

      </body>
</html>
