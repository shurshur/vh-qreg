<?
header("Content-Type: text/html; charset=Windows-1251");
include("reglib.php");
?>
<form action=register.php method=post>
���: <input type=text name=nick value="��� ���"><br>
������: <input type=password name=password value="<?=time()%1000;?>"><br>
E-mail: <input type=text name=email value="���� ����@qwerty.ru"><br>
<input type=submit value=OK>
<input type=reset value=�����>
</form>
