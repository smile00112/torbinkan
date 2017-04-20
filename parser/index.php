<?php

header('Content-type: text/html; charset="utf8"');  
$kprice=1;

require_once("./simplehtmldom/simple_html_dom.php");

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
<input type='text' size='45' name='url' value='<?=@$_POST['url']?>' /><br/>
<input type='text' size='45' name='p' value='1' /><br/>
<input type='submit' value='Go' /><br/>
</form>
<?

if(!empty($_POST['url']))
{
	$url=$_POST['url'];
	$id_category=$_POST['category_id'];
}
else{
	
	exit;
}



if(!empty($_POST['p']))
{
	$P=$_POST['p'];
	
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
	$productsarr=$html->find('div.product-card');
	
	print "<h3>id_category = $id_category </h3>";
	print "<div>count = ".count($productsarr)."</div>";
	$iter=0;
	foreach($productsarr as $product_elem)
	{
		$iter++;
		print "$iter<br/>";
		if($iter>20){break;}
		
		$nameblock=$product_elem->find('div.product-info',0)->find('a',0);
		$url2= $nameblock->href;
		$name=strip_tags($nameblock->innertext);
		//$pos1 = stripos($name, 'Harvia');
		//$pos2 = stripos($name, 'Kastor');
			
		print "<h2>$name </h2>";
		print "<p>$url2</p>";
		//print "<h2>$name | $P $pos1 || $pos2</h2>";
		print "<br/>";
		
		
		if( ($P==1) )
		{
			
			print "Название = ";
			print $name;
			print "<br/>";
			
			print $url2;

			
			if(existsproduct($name,$link))
			{
				$prodid=existsproduct($name,$link);
				print "-Уже существует в Базе =$prodid<br/>";
			}
			else
			{
				
				$html2 = new simple_html_dom();
				$html2 = str_get_html(my_get_html($url2));
				
				$imageblock=$html2->find('div.image-additional',0)->find('a');
				$imgarr=array();
				foreach($imageblock as $imgs)
				{
					$imgarr[]=$imgs->href;
					print "<div>".$imgs->href."</div>";
				}
				$paramsblock=$html2->find('div.product__bar_content',0)->find('table',0)->find('tr');
				
				$manuf="";
				$model="";
				foreach($paramsblock as $tr)
				{
					$trtitl=$tr->find('td',0);
					if(strpos($trtitl,'Модель'))
					{
						print "Модель = ";
						$model=strip_tags($tr->find('td',1));
						print $model;
						print "<br/>";
					}
				
					$trval=$tr->find('td',0);
					if(strpos($trtitl,'Производитель'))
					{
						$manuf=strip_tags($tr->find('td',1));
						print "Производитель = ";
						print $manuf;
						print "<br/>";
					}
				}

				
				//print "Описание = ";
				$description=$html2->find('div.descriprion-info',0)->innertext;
				//print $description;
				print "<br/>";

				$priceblock=$html2->find('div.product__bar_price',0)->find('span',0);
				print "Цена = "; 
				
				$price=strip_tags($priceblock->innertext);
								
				$price=str_replace(" ","",$price);
				$numprice=0;
				$numprice+=$price;
				$numprice=$kprice*$numprice;
				
				print $numprice;
				print "<br/>";
			
			

				
				
				$prodid=addproduct($name,$numprice,100,0,$id_category,$oc_prefix,$imgurl,$description,$link);
				print "<div>prodid=".$prodid."</div>";
				add_product_brend($manuf,$prodid,$oc_prefix,$link);	
				$iter=0;
				foreach($imgarr as $imgelm)
				{
					if($iter==0){
						add_image_product($imgelm,$prodid,$oc_prefix,$link,true);
					}
					else
					{
						add_image_product($imgelm,$prodid,$oc_prefix,$link);
					}
					$iter++;
				}
				
				
				$specblock=$html2->find('table.table_characteristics',0)->find('tr');
				
				foreach($specblock as $elem)
				{	
					$eltit=$elem->find('td',0)->innertext;
					$elval=$elem->find('td',1)->innertext;
					print "<div><b>$eltit</b> = $elval</div>";
					addatribute($elval,$eltit,$prodid,$oc_prefix,$link);
				}
				
				//print $specblock->innertext;
				print "<br/>";
				
				//$razm="";
				
            reqursive_update_categ($id_category,$prodid,$oc_prefix,$link);
            //addseotag($prodid,$metatitle,$metakeywords,$metadescription,$oc_prefix,$link);
            
				
				//addoptions($value,$optid,$prodid,$oc_prefix,$link);
				print "---------------------------------------------------";
				print "<br/>";
				$html2->clear();
				unset($html2);
			}
		}
	}
	  
	$html->clear(); // подчищаем за собой
	unset($html);
	
}

?>