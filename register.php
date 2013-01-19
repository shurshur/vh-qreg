<?
include("reglib.php");
dbconn();

$nick=$_POST['nick'];
$password=$_POST['password'];
$confirm=$_POST['confirm'];
$email=$_POST['email'];

if($password!=$confirm)
  err("Введёные пароли не совпадают!");

#if(!preg_match("/^[\w\.\d-]+@(qwerty\.ru|qwertyru\.ru|peredelkino\.ru)$/i",$email))
if(!checkemail($email))
  err("Неправильный e-mail $email");

for ($i = 0; $i < strlen($nick); ++$i)
  if (strpos($allowedchars_nick, $nick[$i]) === false)
    err("Ник содержит запрещённые символы");

if(strlen($nick)<$min_nick) err("Минимальная длина ника - $min_nick символа");

if(strlen($nick)>$max_nick) err("Максимальная длина ника - $max_nick символа");

for ($i = 0; $i < strlen($password); ++$i)
  if (strpos($allowedchars_pass, $password[$i]) === false)
    err("Пароль содержит запрещённые символы, допускаются только английские буквы и цифры");

if(strlen($password)<5) err("Минимальная длина пароля - 5 символов");

$res = mysql_query("SELECT * FROM reglist WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("Такой ник уже занят");

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("Такой ник уже занят, но ещё не прошёл регистрацию");

if(!$allow_multi) {
  $res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email));
  if(mysql_num_rows($res)>0)
    err("На этот e-mail уже зарегистрирован один пользователь.");
}

$pwd = crypt($password,$password[0].$password[1]);
$secret = mksecret();
mysql_query("INSERT INTO regs (nick,email,secret,pwd) VALUES (".sqlesc($nick).",".sqlesc($email).",".sqlesc($secret).",".sqlesc($pwd).")");
#err("Тут будет отправка на $email");

$link = mklink("confirm.php",$nick,$pwd,$secret);
$body= <<<EOF
Вы запросили регистрацию на $name пользователя $nick.

Если вы этого не делали, просто проигнорируйте это письмо. Запрос был отправлен с адреса {$_SERVER["REMOTE_ADDR"]}. Пожалуйста, не отвечайте на это письмо - оно было послано автоматически.

Чтобы завершить регистрацию, нажмите на ссылку:

$link

EOF
;

mail($email, "$name registration confirmation", $body, "From: $name <$from>\r\nContent-Type: text/plain; charset=Windows-1251", "-f$from");

header("Content-Type: text/html; charset=Windows-1251");
print("Подтверждение регистрации отправлено на адрес $email, проверяйте почту.");
?>
