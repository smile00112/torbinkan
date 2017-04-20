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
<input type='text' size='45' name='url' value='<?=@$_POST['url']?>' /><br/>
<input type='text' size='45' name='p' value='<?=@$_POST['p']?>' />Индекс<br/>
<input type='text' size='45' name='manufactur' value='' />Производитель<br/>
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
$site='http://verfit.ru/';
//$url="http://russkiypar.ru/Pyechi-b-nnyye/Importnyye-c20c22.html";

if(!empty($_POST['p']))
{
	$P=$_POST['p'];
	//if($P==1){$url="http://russkiypar.ru/Pyechi-b-nnyye/Importnyye-c20c22.html";}
	//if($P==2){$url="http://russkiypar.ru/Vsye-dlya-b-ni-c33.html";}
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
	
	
	print "<h3>id_category = $id_category </h3>";
	print "<div>count = ".count($productsarr)."</div>";
	
	
	$iter=1;
	foreach($productsarr as $product_elem)
	{
		$iter++;
		print "<div>$iter</div>";
		
		if($iter>5){break;}
		$nameblock=$product_elem->find('a.ci_title',0);
		$name=strip_tags($nameblock->innertext);
		
	
	    print "<h2>name= $name </h2>";
		// print "<br/>";
		
		// $name=trim($name);
		if( ($P==1  && !empty($name)))
		{
			
			// print "Название = ";
			// print $name;
			// print "<br/>";
			if( existsproduct($name,$link) )
			{
				 print "-Уже существует в Базе<br/>";
			}
			else
			{
				$url2block=$product_elem->find('a.ci_title',0);
				print "url товара = <br/>";
				$url2= $url2block->href;
				
				$url2=$site.$url2;
				print $url2;
				print "<br/>";
				
				if(empty($url2))
				{
					continue;
				}
						
				$html2 = new simple_html_dom();
				$html2 = str_get_html(my_get_html($url2));

			
			
				$paramsblock=$html2->find('div.prod');
				print "Описание = ";
				
				foreach($paramsblock as $paramelem)
				{
					//print $paramelem->plaintext;
					if( strpos($paramelem->plaintext,"Особенности")>-1 )
					{
						print "+"; 	
						$description=$paramelem->parent()->parent()->find('td',2)->innertext;
						$description=strip_tags($description);
						print $description;
					}
					print "<br/>"; 	
				}

				
				print $paramsblock->outertext;
				
				// $descblock=$paramsblock->find('strong',0);
				// $description=strip_tags($descblock->innertext);
				// print $description;
				 print "<br/>"; 
			
				$numprice=0;
			/*
				$priceblock=$html2->find('div.price',0)->find('meta[itemprop=price]',0);
				print "Цена = ";
				
				$price=strip_tags($priceblock->content);
				$price=str_replace(" ","",$price);
				$numprice=0;
				$numprice+=$price;
				$numprice=$kprice*$numprice;
				print $numprice;
				print "<br/>";
			*/
				$imageblock=$html2->find('table[id=table1]',0)->find('img',0);
				print "Изображение = ";
				$imgurl=$site.$imageblock->src;
				print $imgurl;
				print "<br/>";
				
				

				
				
				 $prodid=addproduct($name,$numprice,1,0,$id_category,$oc_prefix,$imgurl,$description,$link);
				 add_image_product($imgurl,$prodid,$oc_prefix,$link);
				// //Бренд
				
				// if($P==1 && $pos1)	{			add_product_brend('Harvia',$prodid,$oc_prefix,$link);			}
				// if($P==1 && $pos2)	{			add_product_brend('Kastor',$prodid,$oc_prefix,$link);			}
				
				
				if(!empty($_POST['manufactur']))
				{
					add_product_brend($_POST['manufactur'],$prodid,$oc_prefix,$link);
				}
				
				
				// $razm="";
				
				$params=array();
				$paramsval=array();
				
				$specbsection=$html2->find('div[id=smth]',0);
				$specbtable=array();
				if(count($specbsection)>0)
				{	
					$specbtable=$specbsection->find('table',0);
				}
				if(count($specbtable)>0)
				{
					//print "Table +<br/>";
					$colstable=$specbtable->find('tr',0)->find('td');
					
					$countTableColumn=count($colstable);
					print "countTableColumn =".$countTableColumn."<br/>";
					
					
					$spechead=$specbtable->find('tr');
					//print $spechead->innertext;
					$i=0;
					foreach($spechead as $h)
					{
						for($i=0;$i<=$countTableColumn;$i++)
						{
							$td=$h->find('td.tablica',$i);
							if(count($td)>0)
							{
								//print $td->plaintext." | ";
								if(!empty($params[$i]))
								{
									$params[$i].=" ".$td->plaintext;
								}
								else{
									$params[$i]=$td->plaintext;
								}
								
							}
							
						}
						//print "<br/>";
	
						
						$i++;
					}
					
					
					
					
					foreach($params as $param)
					{
						//print $param;
						//print "<br/>";
					}
					//Читаем значение таблицы
					$specrow=$specbtable->find('tr');
					foreach($specrow as $row)
					{
						
						foreach($params as $i=>$param)
						{
							
							$spectd	="";$spectd1=null;$spectd2=null;
							$spectd1=$row->find('td.tablica1',$i);
							$spectd2=$row->find('td.tablica2',$i);
							if(count($spectd1)>0){$spectd=$spectd1;	}
							if(count($spectd2)>0){$spectd=$spectd2;	}
							
							if(count($spectd)>0 && !empty($spectd->plaintext))
							{
								
								print $param;
								print " = ";
								$value=$spectd->plaintext;
								print $value;
								print "<br/>"; 
								
								$type='select';
								addnewoptions($value,$param,$prodid,$type,$oc_prefix,1,$link);
							}
						}
					}

	
				}	
				
				
				// $html2->clear();
				// unset($html2);
			}
		}
		print "---------------------------------------------------";
	}
	  
	$html->clear(); // подчищаем за собой
	unset($html);
	
}

?>