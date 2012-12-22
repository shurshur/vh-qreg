<?
include("reglib.php");

dbconn();
$nick = rawurldecode($_GET['nick']);
$md5 = $_GET['secret'];

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)<1) err("Нет такого ника среди запросов на регистрацию");

$row = mysql_fetch_assoc($res);
$pwd = $row['pwd'];
$secret = $row['secret'];
$email = $row['email'];

header("Content-Type: text/html; charset=Windows-1251");
if($secret != "") {
  if($md5 !== md5($secret.$pwd.$secret)) err("Неправильный secret");
  mysql_query("UPDATE regs SET secret='' WHERE nick=".sqlesc($nick));
  print("Подтверждение по e-mail прошло успешно<br>");
} else {
  print("Этот ник уже прошёл подтверждение по e-mail.<br>");
}

$regdate=time();
mysql_query("INSERT INTO `reglist` (`nick`,`class`,`reg_op`,`reg_date`,login_pwd,pwd_crypt,pwd_change) values(".sqlesc($nick).",1,'$name','$regdate',".sqlesc($pwd).",1,0)") or print "Ошибка регистрации ника на хабе!";

?>
