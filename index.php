<?
header("Content-Type: text/html; charset=Windows-1251");
include("reglib.php");
?>
<title>�����������</title>
<h1>�����������</h1>
<form action=register.php method=post>
���: <input type=text name=nick value="��� ���"><br>
������: <input type=password name=password value="<?=time()%1000;?>"><br>
������ ��� ���: <input type=password name=confirm value="<?=time()%999;?>"><br>
E-mail: <input type=text name=email value="����@�����"><br>
<input type=submit value=OK>
<input type=reset value=�����>
</form>
<h1>�������������� ������</h1>
<form action=recover.php method=post>
E-mail (�����������): <input type=text name=email><br/>
��� (�������������): <input type=text name=nick><br/>
<input type=submit value=OK>
<input type=reset value=�����>
</form>
