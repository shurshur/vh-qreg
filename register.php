<?
include("reglib.php");
dbconn();

$nick=$_POST['nick'];
$password=$_POST['password'];
$confirm=$_POST['confirm'];
$email=$_POST['email'];

if($password!=$confirm)
  err("������� ������ �� ���������!");

if(!preg_match("/^[\w\.\d-]+@(qwerty\.ru|qwertyru\.ru|peredelkino\.ru)$/i",$email))
  err("������������ e-mail $email");


$allowedchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789()[]{}_-+=.�����Ũ����������������������������������������������������������,@=#^~/'."\\'".'�*"';

for ($i = 0; $i < strlen($nick); ++$i)
  if (strpos($allowedchars, $nick[$i]) === false)
    err("��� �������� ����������� �������");

if(strlen($nick)<3) err("����������� ����� ���� - 3 �������");

$allowedchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
for ($i = 0; $i < strlen($password); ++$i)
  if (strpos($allowedchars, $password[$i]) === false)
    err("������ �������� ����������� �������, ����������� ������ ���������� ����� � �����");

if(strlen($password)<5) err("����������� ����� ������ - 5 ��������");

$res = mysql_query("SELECT * FROM reglist WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("����� ��� ��� �����");

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("����� ��� ��� ��������, �� ��� �� ������ �����������");

$second = false;
$res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email));
if(mysql_num_rows($res)>0) $second = true;

$pwd = crypt($password,$password[0].$password[1]);
$secret = mksecret();
mysql_query("INSERT INTO regs (nick,email,secret,pwd) VALUES (".sqlesc($nick).",".sqlesc($email).",".sqlesc($secret).",".sqlesc($pwd).")");
#err("��� ����� �������� �� $email");

$link = mklink($nick,$pwd,$secret);
$body= <<<EOF
�� ��������� ����������� �� QWERTY hub ������������ $nick.

���� �� ����� �� ������, ������ �������������� ��� ������. ������ ��� ��������� � ������ {$_SERVER["REMOTE_ADDR"]}. ����������, �� ��������� �� ��� ������ - ��� ���� ������� �������������.

����� ��������� �����������, ������� �� ������:

$link

���� �� ��� e-mail ����� ��� ��������������� ���� �� ���� ���, �� ����������� ������ ���� ����� ��������� ������ ����� ��������� ��������������� ����.

EOF
;

mail($email, "QWERTY Hub registration confirmation", $body, "From: QWERTYbot <qwerty@qwertyclub.net>\r\nContent-Type: text/plain; charset=Windows-1251", "-fqwerty@qwertyclub.net");

header("Content-Type: text/html; charset=Windows-1251");
print("������������� ����������� ���������� �� ����� $email, ���������� �����.");
?>
