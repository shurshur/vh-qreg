<?
include("reglib.php");
dbconn();

$nick=$_POST['nick'];
$password=$_POST['password'];
$confirm=$_POST['confirm'];
$email=$_POST['email'];

if($password!=$confirm)
  err("Введёные пароли не совпадают!");

if(!preg_match("/^[\w\.\d-]+@(qwerty\.ru|qwertyru\.ru|peredelkino\.ru)$/i",$email))
  err("Неправильный e-mail $email");


$allowedchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789()[]{}_-+=.АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя,@=#^~/'."\\'".'©*"';

for ($i = 0; $i < strlen($nick); ++$i)
  if (strpos($allowedchars, $nick[$i]) === false)
    err("Ник содержит запрещённые символы");

if(strlen($nick)<3) err("Минимальная длина ника - 3 символа");

$allowedchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
for ($i = 0; $i < strlen($password); ++$i)
  if (strpos($allowedchars, $password[$i]) === false)
    err("Пароль содержит запрещённые символы, допускаются только английские буквы и цифры");

if(strlen($password)<5) err("Минимальная длина пароля - 5 символов");

$res = mysql_query("SELECT * FROM reglist WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("Такой ник уже занят");

$res = mysql_query("SELECT * FROM regs WHERE nick=".sqlesc($nick));
if(mysql_num_rows($res)>0) err("Такой ник уже запрошен, но ещё не прошёл регистрацию");

$second = false;
$res = mysql_query("SELECT * FROM regs WHERE email=".sqlesc($email));
if(mysql_num_rows($res)>0) $second = true;

$pwd = crypt($password,$password[0].$password[1]);
$secret = mksecret();
mysql_query("INSERT INTO regs (nick,email,secret,pwd) VALUES (".sqlesc($nick).",".sqlesc($email).",".sqlesc($secret).",".sqlesc($pwd).")");
#err("Тут будет отправка на $email");

$link = mklink($nick,$pwd,$secret);
$body= <<<EOF
Вы запросили регистрацию на QWERTY hub пользователя $nick.

Если вы этого не делали, просто проигнорируйте это письмо. Запрос был отправлен с адреса {$_SERVER["REMOTE_ADDR"]}. Пожалуйста, не отвечайте на это письмо - оно было послано автоматически.

Чтобы завершить регистрацию, нажмите на ссылку:

$link

Если на Ваш e-mail ранее уже регистрировался хотя бы один ник, то регистрация нового ника будет завершена только после одобрения администратором хаба.

EOF
;

mail($email, "QWERTY Hub registration confirmation", $body, "From: QWERTYbot <qwerty@qwertyclub.net>\r\nContent-Type: text/plain; charset=Windows-1251", "-fqwerty@qwertyclub.net");

header("Content-Type: text/html; charset=Windows-1251");
print("Подтверждение регистрации отправлено на адрес $email, проверяйте почту.");
?>
