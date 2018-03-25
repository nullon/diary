<?php

$mysql = [
    "host" => "localhost",
    "dbname" => "database",
    "user" => "username",
    "pass" => "password"
];

$PDO = new PDO("mysql:host={$mysql["host"]};dbname={$mysql["dbname"]}",$mysql["user"],$mysql["pass"]);

$query = '
    CREATE TABLE IF NOT EXISTS diary (
        id INT NOT NULL UNIQUE PRIMARY KEY AUTO_INCREMENT,
        date DATE NOT NULL UNIQUE,
        text TEXT NOT NULL
    );';

$PDO->query($query);

if($_GET["p"] == "new"){
    if($_POST){echo insert_post($_POST);}
}
else{
    $query = "SELECT * FROM diary ORDER BY date DESC;";
    $arr = $PDO->query($query)->fetchAll();
    foreach($arr as $value){
        echo $value["date"],$value["text"];
    }
}

?>

<form action="?p=new" method="post">
    <input type="text" name="date" />
    <textarea name="text"></textarea>
    <input type="submit" value="送信" />
</form>

<?php

function insert_post($arr){
    global $PDO;
    $prepare = "INSERT INTO diary VALUE (0,:date,:text);";
    $stmt = $PDO->prepare($prepare);
    $stmt->bindParam(":date",$arr["date"]);
    $stmt->bindParam(":text",$arr["text"]);
    if($stmt->execute()){return "日記を書き込みました。";}
    else{return "日記の書き込みに失敗しました。";}
}