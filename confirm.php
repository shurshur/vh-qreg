<?
include("reglib.php");

dbconn();
$nick = $_GET['nick'];
$md5 = $_GET['secret'];

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)<1) err("��� ������ ���� ����� �������� �� �����������");

$row = mysql_fetch_assoc($res);
$pwd = $row['pwd'];
$secret = $row['secret'];
$email = $row['email'];

header("Content-Type: text/html; charset=Windows-1251");
if($secret != "") {
  if($md5 !== md5($secret.$pwd.$secret)) err("������������ secret");
  mysql_query("UPDATE regs SET secret='' WHERE nick=".sqlesc($nick));
  print("������������� �� e-mail ������ �������<br>");
} else {
  print("���� ��� ��� ������ ������������� �� e-mail.<br>");
}

$res = mysql_query("SELECT * FROM reglist,regs WHERE reglist.nick=regs.nick AND regs.email=".sqlesc($email)." AND regs.nick!=".sqlesc($nick));
if(mysql_num_rows($res)>0) {
  print("�� ���� email ��� ��������������� �� ������� ���� ���� ���. ��� ����, ����� �������, ������� ������� ����������� ��������������� ����.");
} else {
  $regdate=mktime();
  mysql_query("INSERT INTO `reglist` (`nick`,`class`,`reg_op`,`reg_date`,login_pwd,pwd_crypt,pwd_change) values(".sqlesc($nick).",1,'QWERTYbot','$regdate',".sqlesc($pwd).",1,0)") or print "������ ����������� ���� �� ����!";
}

?>
