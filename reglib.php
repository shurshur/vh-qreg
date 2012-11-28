<?

include_once("config.php");

if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) || isset($_SERVER['HTTP_X_REAL_IP'])) {
  foreach(array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP') as $key => $value) {
    if(
      isset($_SERVER[$value])
      && strlen($_SERVER[$value]) > 0
      && strpos($_SERVER[$value], "127.") !== 0
    ) {
      if($p = strrpos($_SERVER[$value], ",")) 
      { 
        $_SERVER["REMOTE_ADDR"] = $REMOTE_ADDR = trim(substr($_SERVER[$value], $p+1)); 
        $_SERVER["HTTP_X_FORWARDED_FOR"] = substr($_SERVER[$value], 0, $p); 
      } 
      else 
        $_SERVER["REMOTE_ADDR"]= $REMOTE_ADDR = $_SERVER[$value]; 
      
      break;
    }
  }
}

function mksecret($len = 20) {
  $ret = "";
  for ($i = 0; $i < $len; $i++)
    $ret .= chr(mt_rand(0, 255));
  return $ret;
}

function mklink($loc,$nick,$pass,$secret) {
  global $base;
  $s = md5($secret.$pass.$secret);
  # escape!
  $nick=urlencode($nick);
  return $base."/$loc?nick=".urlencode($nick)."&secret=$s";
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
  global $db_host, $db_user, $db_pass, $db_data, $db_charset;
  mysql_connect($db_host, $db_user, $db_pass) or err("Error in MySQL connect");
  mysql_select_db($db_data) or err("Error in MySQL select db");
  if(isset($db_charset) && $db_charset)
    mysql_query("set names ".$db_charset) or err("Error in MySQL set charset");
  mysql_query("delete from regs where length(secret)=20 and added < now()-interval 3 day") or err("Error in cleanup");
}

function checkemail($email) {
  if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email))
    return true;
  return false;
}

?>
