<?
header("Content-Type: text/html; charset=Windows-1251");
include("reglib.php");
?>
<title>Регистрация</title>
<h1>Регистрация</h1>
<form action=register.php method=post>
Ник: <input type=text name=nick value="Ваш ник"><br>
Пароль: <input type=password name=password value="<?=time()%1000;?>"><br>
Пароль ещё раз: <input type=password name=confirm value="<?=time()%999;?>"><br>
E-mail: <input type=text name=email value="Ваша@почта"><br>
<input type=submit value=OK>
<input type=reset value=Сброс>
</form>
<h1>Восстановление пароля</h1>
<form action=recover.php method=post>
E-mail (обязательно): <input type=text name=email><br/>
Ник (необязательно): <input type=text name=nick><br/>
<input type=submit value=OK>
<input type=reset value=Сброс>
</form>
