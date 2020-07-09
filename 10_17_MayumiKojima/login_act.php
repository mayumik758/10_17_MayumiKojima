<?php
session_start();

$lid = $_POST["lid"];
$lpwd = $_POST["lpwd"];

// film_dbに接続
include("funcs.php");
$pdo = db_cct();

// データ取得sql作成
$stmt = $pdo->prepare("SELECT * FROM member_db WHERE lid=:lid AND lpwd=:lpwd");
$stmt->bindValue(':lid',$lid, PDO::PARAM_STR);
$stmt->bindValue(':lpwd',$lpwd, PDO::PARAM_STR);
$status = $stmt->execute();

// SQL実行時にエラーがある場合ストップ
if($status==false){
  sql_error($stmt);
}

//抽出データ数を取得
$val = $stmt->fetch();

// SESSIONに値を代入
if( $val["id"] !=""){
  //Login成功時
  $_SESSION["chk_ssid"]  = session_id();
  // $_SESSION["admin"] = $val['admin'];
  $_SESSION["name"] = $val['name'];
  redirect("select.php");
}else{
  //Login失敗時(Logout経由)
  redirect("login.php");
}

exit();


?>