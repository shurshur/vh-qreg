<?
header("Content-Type: text/html; charset=Windows-1251");
include("reglib.php");
?>
<form action=register.php method=post>
Ник: <input type=text name=nick value="Ваш ник"><br>
Пароль: <input type=password name=password value="<?=time()%1000;?>"><br>
E-mail: <input type=text name=email value="Ваше мыло@qwerty.ru"><br>
<input type=submit value=OK>
<input type=reset value=Сброс>
</form>
