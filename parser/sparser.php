
<?php
require_once("../config.php");
require_once("function.php");
require_once("./simplehtmldom/simple_html_dom.php");


$host = "localhost";
$user = "u0112699_sport"; //Параметры БД Prestashop
$db = "SagalKomr7472";
$password = "X5qTXFXZhnyGLQXy";
$LIMITLOG=1000; //количество записей журнала посещений
global $ocpath;
$ocpath="/www/sport-fire.ru/"; //Путь к директории Ocstore

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore

//print_r($_POST);

if(!empty($_POST['test']))
{
	print "тестовый запрос - ok";		
	exit();	
}

if(!empty($_POST['cleardata']))
{
	//print "Была произведена очистка - ok";		
	$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if ($link) {
		mysql_query("set names utf8"); 
		mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
		$result=clearlist_product($link);
		file_put_contents('./logs/log.txt', "");
		print $result;
	}
	exit();	
}
if(!empty($_POST['delord']))
{
	//print "Было произведена очистка - ok";		
	$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if ($link) {
		mysql_query("set names utf8"); 
		mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
		//$result=clearlist_product($link);
		$result=delete_old_product($oc_prefix,$link);
		//file_put_contents('./logs/log.txt', "");
		print $result;
	}
	exit();	
}

if(!empty($_POST['productparsing']))
{
	$url2=$_POST['productparsing'];
	$id_category=$_POST['categid'];
	
	$url2=htmlspecialchars_decode($url2);
	$url2=trim($url2);
	if(!empty($url2) && $url2!='undefined')	{
		$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
		if ($link) {
			mysql_query("set names utf8"); 
			mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
			
			//Первый парсер
			if(strpos($url2,'gssport.ru'))
			{
				$kprice=$_POST['kprice'];
				require_once("product_donor1.php");
				print "id_product=".$prodid."<br/>";
			}
			//Второй парсер
			if(strpos($url2,'bggest.ru'))
			{
				$kprice=$_POST['kprice2'];
				require_once("product_donor2.php");
			}
			mysql_close($link);
		}
	}
}
if(!empty($_POST['pageparsing']))
{
	$url=$_POST['pageparsing'];
	$categid=$_POST['categid'];
	$url=htmlspecialchars_decode($url);
	$url=trim($url);
	
	$brandArr=array('8848 ALTITUDE','HYRA','ALMRAUSCH','MEATFLY','MORMAII','AHD','POINT-7','NAUTIX','TAKOON','MEATFLY','TRIGGERNAUT');
	
	
	
	if(!empty($url) && $url!='undefined')
	{
		print "Категория - ".$categid."\r\n";
		print "Обработка страницы - ".$url."\r\n";	
	
		$fromUrl='http://www.gssport.ru';
		

		
		$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
		if ($link) 
		{
			mysql_query("set names utf8"); 
			mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');

				
			//Первый парсер
			if(strpos($url,'gssport.ru'))
			{	
				$html = new simple_html_dom();
		
				$html = str_get_html(my_get_html($url));
		
				$dtitle = $html->find('title',0);
				print "<div>".$dtitle->innertext."</div>";
			
				//require_once("parsrequire.php");
				$contentproducts = $html->find('div.view-beeshop-products',0);
				$basecontent=count($contentproducts);
				$i=0;
				if($basecontent>0)
				{
					$ret = $contentproducts->find('div.column-fields-wrapper');
					print "\r\n  Кол-во товаров на странице=".count($ret)."\r\n";
					foreach($ret as $element)
					{       
						$i++;
						
						//print "<h2>$i</h2>";
						//if($i!=2 && $i!=1){ continue;}
						$a=$element->find('a',0);
						$a1=$element->find('a',1);
						//print "fc count=".count($a)."<br/>\r\n";	
						
						if(count($a)>0)
						{
							$url2=$fromUrl.$a->href;
							
							$name=$a1->innertext;
							print "<div>name=$name</div>\r\n";
							print "<div>url=$url2</div>\r\n";
							//addtolist_product_item($categid,$url2,$link);
						}
					}	
				}
			}
			mysql_close($link);
		}
		exit();
	}
}
if(!empty($_POST['parsing']))
{
	$item=$_POST['item'];
	//print "parsing - $item \r\n";	
	$item=trim($item);
	
	if(!empty($item))
	{
			$fromUrl='http://lit-kom.ru';
			

			$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
			if ($link) {
				mysql_query("set names utf8"); 
				mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');

				$html = new simple_html_dom();
				$html = str_get_html(my_get_html($item));
				$dtitle = $html->find('title',0);
				//print "result=".count($dtitle);
				//print $dtitle->innertext;
				if(count($dtitle)<1)
				{
					exit;
				}
				
				//Количество страниц
				$ulpagerblock=$html->find('div.pagination',0)->find('div.links',0);
				if(count($ulpagerblock)>0)
				{	
					//$ulpager = $ulpagerblock->find('a');
					//$numpages=count($ulpager)+1;
					print "<div>Количество страниц ".$numpages."</div>";
					foreach($ulpager as $pagelem)
					{
						//print $fromUrl.$pagelem->find('a',0)->href."<br/>";	
					}
				}
				else
				{
					//print "only 1 page";
				}
			}
		

	}
	exit();	
}


//print "вот такие данные sparsss";	
?>