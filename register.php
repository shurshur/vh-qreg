<?
include("reglib.php");
dbconn();

$nick=$_POST['nick'];
$password=$_POST['password'];
$confirm=$_POST['confirm'];
$email=$_POST['email'];

if($password!=$confirm)
  err("������� ������ �� ���������!");

#if(!preg_match("/^[\w\.\d-]+@(qwerty\.ru|qwertyru\.ru|peredelkino\.ru)$/i",$email))
if(!checkemail($email))
  err("������������ e-mail $email");

for ($i = 0; $i < strlen($nick); ++$i)
  if (strpos($allowedchars_nick, $nick[$i]) === false)
    err("��� �������� ����������� �������");

if(strlen($nick)<$min_nick) err("����������� ����� ���� - $min_nick �������");

if(strlen($nick)>$max_nick) err("������������ ����� ���� - $max_nick �������");

for ($i = 0; $i < strlen($password); ++$i)
  if (strpos($allowedchars_pass, $password[$i]) === false)
    err("������ �������� ����������� �������, ����������� ������ ���������� ����� � �����");

if(strlen($password)<5) err("����������� ����� ������ - 5 ��������");

$res = mysql_query("SELECT * FROM reglist WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("����� ��� ��� �����");

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("����� ��� ��� �����, �� ��� �� ������ �����������");

if(!$allow_multi) {
  $res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email));
  if(mysql_num_rows($res)>0)
    err("�� ���� e-mail ��� ��������������� ���� ������������.");
}

$pwd = crypt($password,$password[0].$password[1]);
$secret = mksecret();
mysql_query("INSERT INTO regs (nick,email,secret,pwd) VALUES (".sqlesc($nick).",".sqlesc($email).",".sqlesc($secret).",".sqlesc($pwd).")");
#err("��� ����� �������� �� $email");

$link = mklink("confirm.php",$nick,$pwd,$secret);
$body= <<<EOF
�� ��������� ����������� �� $name ������������ $nick.

���� �� ����� �� ������, ������ �������������� ��� ������. ������ ��� ��������� � ������ {$_SERVER["REMOTE_ADDR"]}. ����������, �� ��������� �� ��� ������ - ��� ���� ������� �������������.

����� ��������� �����������, ������� �� ������:

$link

EOF
;

mail($email, "$name registration confirmation", $body, "From: $name <$from>\r\nContent-Type: text/plain; charset=Windows-1251", "-f$from");

header("Content-Type: text/html; charset=Windows-1251");
print("������������� ����������� ���������� �� ����� $email, ���������� �����.");
?>
