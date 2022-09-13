     <?php
     // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password char(32),"
    . "date DATETIME"
    .");";
    $stmt = $pdo->query($sql);
    
    ?>
    
    <?php
            $date = date("Y/m/d/ H:i:s");
            
            if(!empty($_POST["name"]) && !empty($_POST["comment"])) {//空かどうかをチェックする
                $str1 = $_POST["name"];
                $str2 = $_POST["comment"];
                $str3 = $_POST["password"];
                //新規投稿機能
                if(empty($_POST["number_edit_plan"])){
                    $sql = $pdo -> prepare("INSERT INTO tbtest_1 (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $name = $str1;
                    $comment = $str2;
                    $password = $str3;
                    //$date = date("Y/m/d/ H:i:s");
                    $sql -> execute();
                }
                
                if(!empty($_POST["number_edit_plan"])){
                    $id = $_POST["number_edit_plan"]; //変更する投稿番号
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $password = $_POST["password"];
                    $sql = 'UPDATE tbtest_1 SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> execute();
                }
            }
                
            
            
            
                //消除機能
            if(!empty($_POST["number_delete"]) && !empty($_POST["password_delete"])){
                $id = $_POST["number_delete"];
                $sql = 'SELECT * FROM tbtest_1 WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    $del_pw = $row['password'];
                }
                if($del_pw == $_POST["password_delete"]){    
                $str5 = $_POST["number_delete"];
                $id = $str5;
                $sql = 'delete from tbtest_1 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                }
                if($del_pw != $_POST["password_delete"]){
                    echo"パスワードが違います";
                }
            }
                
                //編集したい部分を表示する機能
                
                if(!empty($_POST["number_edit"]) && !empty($_POST["password_edit"])){
                    $str4 = $_POST["number_edit"];
                    $id = $str4;
                    $sql = 'SELECT * FROM tbtest_1 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql); 
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute(); 
                    $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        $edit_pw = $row['password'];   
                    }if($edit_pw == $_POST["password_edit"]){
                    foreach ($results as $row){
                        $edit_num = $row['id'];
                        $edit_name =  $row['name'];
                        $edit_com =  $row['comment'];
                    }
                    }else{
                        echo"パスワードが違います";
                    }
                }else{
                    $edit_num = "";
                    $edit_name = "";
                    $edit_com = "";
                }
                
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="m5-1.php" method="post">
        <input type="text" name="name" id="name" placeholder="名前" value="<?php echo $edit_name ;?>"><br>
        <input type="text" name="comment" id="comment" placeholder="コメント" value="<?php echo $edit_com ;?>"><br>
        <input type="text" name="password" id="password" placeholder="パスワード">
        <input type="hidden" name="number_edit_plan" id="number_edit_plan" value="<?php echo $edit_num ;?>">
        <input type="submit" name="submit" value="送信"><br><br>
    </form>
    
    <form action="m5-1.php" method="post">    
        <input type="number" name="number_delete" id="number_delete" placeholder="削除対象番号"><br>
        <input type="text" name="password_delete" id="password_delete" placeholder="パスワード">
        <input type="submit" name="delete" value="削除"><br><br>
    </form>
    
    <form action="m5-1.php" method="post">
        <input type="number" name="number_edit" id="number_edit" placeholder="編集対象番号"><br>
        <input type="text" name="password_edit" id="password_edit" placeholder="パスワード">
        <input type="submit" name="edit" value="編集"><br><br>
    </form>
</body>
</html>

    <?php
            //print機能
            $sql = 'SELECT * FROM tbtest_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                //echo $row['password'].',';
                echo $row['date'].'<br>';
            echo "<hr>";
            }
    ?>