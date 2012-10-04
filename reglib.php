<?

function mksecret($len = 20) {
  $ret = "";
  for ($i = 0; $i < $len; $i++)
    $ret .= chr(mt_rand(0, 255));
  return $ret;
}

function mklink($nick,$pass,$secret) {
  $s = md5($secret.$pass.$secret);
  # escape!
  $nick=urlencode($nick);
  return "http://dc.qwerty.ru/reg/confirm.php?nick=$nick&secret=$s";
}

function mkpass($pass) {
  return crypt($pass);
}

function sqlesc($x) {
  return "'".mysql_real_escape_string($x)."'";
}

function err($s) {
  header("Content-Type: text/html; charset=Windows-1251");
  print("FATAL Error: $s");
  die();
}

function dbconn() {
  mysql_connect("localhost","verlihubuser","verlihubpass") or err("Error in MySQL connect");
  mysql_select_db("verlihubdata") or err("Error in MySQL select db");
  mysql_query("set names cp1251") or err("Error in MySQL set charset");
  mysql_query("delete from regs where length(secret)=20 and added < now()-interval 3 day") or err("Error in cleanup");
}

?>
