<?
require_once("../config.php");
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if ($link) {
    mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');




$sql="
CREATE TABLE IF NOT EXISTS `parscateg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `donorurl` varchar(255) NOT NULL,
  `parse` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$sql1=
"
CREATE TABLE IF NOT EXISTS `parsurl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `donorurl` varchar(255) NOT NULL,
  `statusdonor` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; 
";

$sql2=
"ALTER TABLE `parscateg` CHANGE `donorurl` `donorurl` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";



//mysql_query($sql,$link) or die("Invalid query: " . mysql_error());
//mysql_query($sql2,$link) or die("Invalid query: " . mysql_error());
}
?>