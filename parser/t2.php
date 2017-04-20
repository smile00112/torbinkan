<?php

header('Content-type: text/html; charset="utf8"');  
$kprice=1;


require_once("../../vippechi.ru/parser/simplehtmldom/simple_html_dom.php");

require_once("function.php");
require_once("../config.php");

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore
?>
<form method='post'>
<?
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if ($link) {
    mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
	print selectcateg($link);
}
?><br/>
<input type='text' size='45' name='url' value='<?=@$_POST['url']?>' />Url описания<br/>

<input type='submit' value='Go' /><br/>
</form>

<?

$site='http://verfit.ru/';
if(!empty($_POST['gooo']))
{
	
	
	foreach($_POST as $k=>$v)
	{
		print "$k<br/>\r\n";
		
		if(substr($k,0,5)=='prod_')
		{
			$productid=substr($k,5);
			
			print "$productid\r\n";
			print "=\r\n";
			if(!empty($v))			
			{
				$urlmy=$site.$v;
				print "$urlmy\r\n<br/>";
				
				$html2 = new simple_html_dom();
				$html2 = str_get_html(my_get_html($urlmy));
				
				$paramsblock=$html2->find('div.prod');
				print "Описание = ";
				
				foreach($paramsblock as $paramelem)
				{
					if( strpos($paramelem->plaintext,"Особенности")>-1 )
					{
						$description=$paramelem->parent()->parent()->find('td',2)->innertext;
						$description=strip_tags($description);
						print $description;
					}
					print "<br/>"; 	
				}
				
				$imageblock=$html2->find('table[id=table1]',0)->find('img',0);
				print "Изображение = ";
				$imgurl=$site.$imageblock->src;
				print "img=".$imgurl;
				print "<br/>";
				
				$description=mysql_real_escape_string($description);
				$description=str_replace("'","",$description);
				
				$query42="UPDATE `".$oc_prefix."product_description`
				SET `description`='$description'
				WHERE `product_id`='$productid'";
				print $query42."<br/>";
				mysql_query($query42,$link) or die("Invalid query: " . mysql_error());
				
				
				add_image_product($imgurl,$productid,$oc_prefix,$link);
				$html2->clear();
				unset($html2);
			}
		}
		
	}
	
	
}	



if(!empty($_POST['url']))
{
	$url=$_POST['url'];
	$id_category=$_POST['category_id'];
}
else{
	
	exit;
}


$html = new simple_html_dom();
$html = str_get_html(my_get_html($url));

$dtitle = $html->find('title',0);

if(count($dtitle)<1)
{
	
	exit;
}
else
{
	print "<h2>".$dtitle->innertext."</h2>";  
	print "<br/>";
	$productsarr=$html->find('div.cat_item');
	
	
	print "<form method='post'>\r\n";
	
	print "<h3>id_category = $id_category </h3>";
	print "<div>count = ".count($productsarr)."</div>";
	
	
	$iter=1;
	
	$query0="SELECT *,p.model as pmodel FROM ".$oc_prefix."product as p
	LEFT JOIN ".$oc_prefix."product_to_category  as c ON c.`product_id`=p.`product_id`
	WHERE c.`category_id`='".$id_category."'";
	//print $query0."<br/>";
	$res0=mysql_query($query0,$link) or die("Invalid query: " . mysql_error());
	if(mysql_num_rows($res0)>0)
	{
		while($row=mysql_fetch_array($res0))
		{
			
			
			print "<select name='prod_".$row['product_id']."'>\r\n";
			foreach($productsarr as $product_elem)
			{
				$nameblock=$product_elem->find('a.ci_title',0);
				$name=strip_tags($nameblock->innertext);
				
				
				$url2block=$product_elem->find('a.ci_title',0);
				print "url товара = <br/>";
				$url2= $url2block->href;
				
				print "<option value='$url2'>".$name." </option>\r\n";
				
			}
			print "</select>\r\n";
			print $row['pmodel']."\r\n";
			print "<br/>\r\n";
		}
		print "<br/>\r\n";
	}
	print "<input type='submit' name='gooo' value='Goo'>\r\n";
	print "</form>\r\n";
}








?>