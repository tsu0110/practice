<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <h1>この掲示板のテーマ：大好きなもの！</h1>
    
    <form action = "" method = "post">
        <p>【投稿フォーム】</p>
        <input type = "text" name = "name" placeholder = "名前">
        <input type = "text" name = "comment" placeholder = "コメント">
        <input type = "text" name = "password1" placeholder = "パスワード">
        <input type = "submit" name = "submit" value = "送信">
    </form>
    <br>
        
    <form action = "" method = "post">
        <p>【削除フォーム】</p>
        <input type = "number" name = "denumber" placeholder = "削除番号">
        <input type = "text" name = "password2" placeholder = "パスワード">
        <input type = "submit" name = "delete" value = "削除">
    </form>
    <br>
        
    <form action = "" method = "post">
        <p>【編集フォーム】</p>
        <input type = "number" name = "ednumber" placeholder = "編集番号">
        <input type = "text" name = "password3" placeholder = "パスワード">
        <input type = "submit" name = "edit" value = "編集">
    </form>
    <br>
    
    
    <?php
        //データベース名・ユーザー名・パスワードを設定
        $dsn = `データベース名`;
        $user = `ユーザー名`;
        $password = `パスワード`;
        
        //データベースに接続・PDOのエラーレポートを表示
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    
        //データベースを作成
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date char(32),"
        . "pass TEXT"
        . ");";
        $stmt = $pdo -> query($sql);
    

        //送信ボタンが押された場合
        if (isset($_POST["name"], $_POST["comment"], $_POST["password1"])) {
            if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"])) {
                //データベースに登録
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
                $sql -> bindParam(":name", $name, PDO::PARAM_STR);
                $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
                $sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
                $sql -> bindParam(":date", $date, PDO::PARAM_STR);
                
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $pass = $_POST["password1"];
                $date = date("Y/m/d H:i:s");
                $sql -> execute();
            }
        ;}
            
           
        //編集ボタンが押された場合   
        if (isset($_POST["ednumber"])) {
            if (isset($_POST["edname"], $_POST["edcomment"], $_POST["password4"])) {
                $id = $_POST["ednumber"];
                $name = $_POST["edname"];
                $comment = $_POST["edcomment"];
                $pass = $_POST["password4"];
                
                $sql = "UPDATE tbtest SET name=:name, comment=:comment, pass=:pass WHERE id=:id";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(":name", $name, PDO::PARAM_STR);
                $stmt -> bindParam(":comment", $comment, PDO::PARAM_STR);
                $stmt -> bindParam(":pass", $pass, PDO::PARAM_INT);
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
            }
        ;}    
            
        
        //削除ボタンが押された場合
        if (isset($_POST["denumber"], $_POST["password2"])) {
            if (!empty($_POST["denumber"]) && !empty($_POST["password2"])) {
                //データレコードを削除
                $id = $_POST["denumber"];
                
                $sql = "delete from tbtest where id=:id";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
            }
        ;}
        
        
        //編集ボタンが押された場合
        if (isset($_POST["ednumber"], $_POST["password3"])) {
            if ($_POST["ednumber"] != "" && $_POST["password3"] != "") {
                $id = $_POST["ednumber"];
                $pass = $_POST["password3"];
                
                $sql = "SELECT * FROM tbtest";
                $stmt = $pdo -> query($sql);
                $results = $stmt -> fetchAll();
                foreach ($results as $row) {
                    if ($row["id"] == $id && $row["pass"] == $pass) {
    ?>
                
                        <form action = "" method = "post">
                            <p>【投稿編集フォーム】</p>
                            <input type = "hidden" name = "ednumber" placeholder = "編集予定番号" value = "<?= $row['id'] ?>">
                            <input type = "text" name = "edname" placeholder = "名前" value = "<?= $row['name'] ?>">
                            <input type = "text" name = "edcomment" placeholder = "コメント" value = "<?= $row['comment'] ?>">
                            <input type = "text" name = "password4" placeholder = "パスワード" value = "<?= $row['pass'] ?>">
                            <input type = "submit" name = "submit" value = "送信">
                        </form>
                        <br>
                        
    <?php
                    ;}
                ;}
            ;}
        ;}
        
        
        //表示
        $sql = "SELECT * FROM tbtest";
        $stmt = $pdo -> query($sql);
        $results = $stmt -> fetchAll();
        foreach ($results as $row) {
            echo htmlspecialchars($row["id"]. ", ");
            echo htmlspecialchars($row["name"]. ", ");
            echo htmlspecialchars($row["comment"]. ", ");
            //【必要な場合のみ】　echo htmlspecialchars($row["pass"]). "", "";
            echo htmlspecialchars($row["date"]). "<br>";
            echo "<hr>";
        }
        
        
        //【必要な場合のみ】　作成したテーブルの構成詳細を確認
        //$sql = "SHOW CREATE TABLE tbtest";
        //$result = $pdo -> query($sql);
        //foreach ($result as $row) {
            //echo $row[1];
        //}
        //echo "<hr>";
        
        
        //【必要な場合のみ】　tbtest テーブルを削除
        //$sql = "DROP TABLE tbtest";
        //$stmt = $pdo -> query($sql);
    ?>
</body>
</html>