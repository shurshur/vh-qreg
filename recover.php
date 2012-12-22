<?php
include("reglib.php");
dbconn();

$email = $_POST['email'];
$nick = $_POST['nick'];

$res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email).($nick?(" AND nick=".sqlesc($nick)):""));
if(mysql_num_rows($res)<1)
  err("Нет пользователя с таким e-mail");

if(mysql_num_rows($res)>1)
  err("Больше одного пользователя с таким e-mail. Для восстановления укажите ник.");

$row = mysql_fetch_assoc($res);
$secret = mksecret();
$nick = $row["nick"];
$pwd = $row["pwd"];
mysql_query("UPDATE regs SET secret=".sqlesc($secret)." WHERE nick=".sqlesc($nick));

$link = mklink("recovery.php",$nick,$pwd,$secret);
$body= <<<EOF
Вы запросили восстановления пароля на $name пользователя $nick.

Если вы этого не делали, просто проигнорируйте это письмо. Запрос был отправлен с адреса {$_SERVER["REMOTE_ADDR"]}. Пожалуйста, не отвечайте на это письмо - оно было послано автоматически.

Чтобы завершить востановление пароля, нажмите на ссылку:

$link

EOF
;

mail($email, "$name password recovery confirmation", $body, "From: $name <$from>\r\nContent-Type: text/plain; charset=Windows-1251", "-f$from");

header("Content-Type: text/html; charset=Windows-1251");
print("Подтверждение восстановления пароля отправлено на адрес $email, проверяйте почту.");
?>
