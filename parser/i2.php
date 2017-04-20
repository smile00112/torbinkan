<!DOCTYPE html>
<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
var arr =[];
var categ =[];
</script>


<?php
require_once("../config.php");
require_once("parsclass.php");
require_once("function.php");


global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

if ($link) {
	mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
	
	$where="`id`>0";
	$q="SELECT * FROM `parscateg` WHERE $where ORDER BY `id`";
	$res=mysql_query($q);
	print "<script>";
	while ($row = mysql_fetch_array($res)) 
	{
		$url=$row['donorurl'];
		$categid=$row['category_id'];
		
	     print " arr.push('$url');\r\n";
	     print " categ.push('$categid');\r\n";
	}
	print "</script>";
}

?>

<script>
$( document ).ready(function() {
	
	for(var i = 0; i < arr.length; i++)
	{
      
      $.post("ajax.php", { url: arr[i],categid:categ[i]})
         .done(function(data) {
            //alert("Data Loaded: " + data);
            $( "#result" ).append( data );
      });
   }
});

</script>


<div id="result"></div>