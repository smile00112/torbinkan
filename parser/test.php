<?php

header('Content-type: text/html; charset="utf8"');  
$kprice=1;

//require_once("../../vippechi.ru/parser/simplehtmldom/simple_html_dom.php");

require_once("function.php");
require_once("../config.php");

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore


$id=42;
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if ($link) {
    mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
	

	$query0="SELECT * FROM `parsurl` WHERE 1";
	//$query0="SELECT * FROM `".$oc_prefix."product_attribute` WHERE `attribute_id`='25'";
	//$query0="SELECT * FROM `".$oc_prefix."option_value_description` WHERE `option_id`='14'";
	//$query0="SELECT * FROM ".$oc_prefix."product_option_value  WHERE `product_id`='$id'";  
	//$query0="SELECT *,p.model as pmodel FROM oc_product as p LEFT JOIN oc_product_to_category as c ON c.`product_id`=p.`product_id` WHERE c.`category_id`='172'";
	print $query0."<br>";
	$res0=mysql_query($query0,$link);
	print "count=".mysql_num_rows($res0)."<br>";
   print "<table border='1'>";	
	if(mysql_num_rows($res0)>0)
	{
		$i=0;
      
      while($row=mysql_fetch_array($res0,MYSQL_ASSOC))
		{
			if($i==0)
         {
            foreach($row as $k=>$v)	
            {   
               print "<td>row[$k]</td>";
                  
            }
      
         }
         
         print "<tr>";
         foreach($row as $k=>$v)	
			{
				
				print "<td>$v</td>";
				
			}
         $i++;
			//print "___________<br>";
		}
		print "</tr>";
	}
   print "</table>";
}



?>
