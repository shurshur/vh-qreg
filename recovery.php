<?
include("reglib.php");

dbconn();

$final = false;

if(isset($_POST['nick']) && isset($_POST['secret']) && isset($_POST['password'])) {
  $final = true;
  $nick = $_POST['nick'];
  $md5 = $_POST['secret'];
  $password = $_POST['password'];
} else {
  $nick = $_GET['nick'];
  $md5 = $_GET['secret'];
}

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)<1) err("��� ������ ���� ����� �������� �� �����������");

$row = mysql_fetch_assoc($res);
$pwd = $row['pwd'];
$secret = $row['secret'];
$email = $row['email'];

if(($secret != "") && ($md5 == md5($secret.$pwd.$secret)))
  if($final) {
    $pwd = crypt($password,$password[0].$password[1]);
    mysql_query("UPDATE regs SET secret='',pwd=".sqlesc($pwd)." WHERE nick=".sqlesc($nick)) or err("������ ���������� ��������");
    mysql_query("UPDATE reglist SET login_pwd=".sqlesc($pwd)." WHERE nick=".sqlesc($nick)) or err("������ ��������� ������");
    header("Content-Type: text/html; charset=Windows-1251");
    print("������ ������.<br>");
  } else {
    header("Content-Type: text/html; charset=Windows-1251");
    ?>
<form action=recovery.php method=post>
<input type=hidden name=nick value="<? print htmlentities($nick); ?>">
<input type=hidden name=secret value="<? print htmlentities($md5); ?>">
����� ������ ������������ <? print $nick; ?>:
<input type=password name=password><br>
<input type=submit value=OK>
<input type=reset value=�����>
</form>
    <?
  }
else
  err("������������ secret");

?>
