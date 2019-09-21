<html>
<head>
<meta charset="utf-8">
<title>mission_5</title>
</head>
<body>


<form method="POST" action="">

<h1>入力フォーム</h1>
<?php
    //データベース読み込み
    $dsn="mysql:dbname='(データベース名)';host=localhost";
    $user='(ユーザー名)';
    $password='(パスワード)';
    $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=> PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS boarddb"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "time char(50),"
    . "password char(20)"
    .");";
    $stmt = $pdo->query($sql);
    
    $editname='';
    $editcomment='';
    $editnum='';
    
    if(!empty($_POST["editnumber"])&&!empty($_POST["editpass"])){
        $pass=$_POST["editpass"];
        $id=$_POST["editnumber"];
        $editnum=$_POST["editnumber"];
        $sql = 'SELECT * FROM boarddb where id='.$id;
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        if($row['password']==$pass){
            $editname=$row['name'];
            $editcomment=$row['comment'];
        };
        };
    }
    

    echo"名前:<br><input type='text' name='name' value=$editname><br>";
    echo"コメント:<br><input name='comment' value=$editcomment><br><br>";
    echo"<input type='hidden' name='hiddennum' value=$editnum><br>";
    echo"パスワード設定:(編集実行中にここは入力しないでください。)<br>";
    echo"<input type='text' name='password'><br>";
    echo"<input type='submit' value='送信'><br>";
    
?>

</form>
<form method="POST" action="">
<h1>削除フォーム</h1>
<input type="text" name="deletenumber"><br>
パスワード:<br>
<input type="text" name="deletepass"><br>
<input type="submit" value="削除">
</form>

<form method="POST" action="">
<h1>編集フォーム</h1>
<input type="text" name="editnumber"><br>
パスワード:<br>
<input type="text" name="editpass"><br>
<input type="submit" value="編集">
</form>


<?php
    if(!empty($_POST["name"]) && !empty($_POST["comment"])&&empty($_POST["hiddennum"])
                                        &&!empty($_POST["password"])){
        $sql = $pdo -> prepare("INSERT INTO boarddb (name, comment, time , password) VALUES (:name, :comment, :time, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $timestamp = time();
        $time = date("Y/m/d G:i:s",$timestamp);
        $password = $_POST["password"];
        $sql -> execute();

        
    };
    if(!empty($_POST["name"]) && !empty($_POST["comment"]&&!empty($_POST["hiddennum"]))){
        $id = $_POST["hiddennum"];;
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $sql = 'update boarddb set name=:name,comment=:comment where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    };
    
    if(!empty($_POST["deletenumber"])&&!empty($_POST["deletepass"])){
        $pass=$_POST["deletepass"];
        $id = $_POST["deletenumber"];
        $sql = 'SELECT * FROM boarddb where id='.$id;
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['password']==$pass){
                $sql = 'delete from boarddb where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            };
        };
    };
    
$sql = 'SELECT * FROM boarddb';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['time'].'<br>';
echo "<hr>";
};
?>
</body>
</html>
