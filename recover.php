<?php
include("reglib.php");
dbconn();

$email = $_POST['email'];
$nick = $_POST['nick'];

$res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email).($nick?(" AND nick=".sqlesc($nick)):""));
if(mysql_num_rows($res)<1)
  err("��� ������������ � ����� e-mail");

if(mysql_num_rows($res)>1)
  err("������ ������ ������������ � ����� e-mail. ��� �������������� ������� ���.");

$row = mysql_fetch_assoc($res);
$secret = mksecret();
$nick = $row["nick"];
$pwd = $row["pwd"];
mysql_query("UPDATE regs SET secret=".sqlesc($secret)." WHERE nick=".sqlesc($nick));

$link = mklink("recovery.php",$nick,$pwd,$secret);
$body= <<<EOF
�� ��������� �������������� ������ �� $name ������������ $nick.

���� �� ����� �� ������, ������ �������������� ��� ������. ������ ��� ��������� � ������ {$_SERVER["REMOTE_ADDR"]}. ����������, �� ��������� �� ��� ������ - ��� ���� ������� �������������.

����� ��������� ������������� ������, ������� �� ������:

$link

EOF
;

mail($email, "$name password recovery confirmation", $body, "From: $name <$from>\r\nContent-Type: text/plain; charset=Windows-1251", "-f$from");

header("Content-Type: text/html; charset=Windows-1251");
print("������������� �������������� ������ ���������� �� ����� $email, ���������� �����.");
?>
