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
<input type='text' size='45' name='url2' value='<?=@$_POST['url2']?>' /><br/>
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
//$html = str_get_html(my_get_html($url));
//$html = file_get_html($url);

//$html = str_get_html(file_get_contents($url));
$htmlsource = file_get_contents($url);


$new = htmlspecialchars($html);
//echo $new;



//$html = str_get_html($html);
print "<div>htmlcount = ".count($html)."</div>";
$dtitle = $html->find('html',0);
print "<div>dtitle = ".count($dtitle)."</div>";

if(count($dtitle)<1 )//|| empty($cccxsa))
{
	
	exit;
}
else
{
	print "<h2>".$dtitle->innertext."</h2>";  
	print "<br/>";
	$productsarr=$html->find('div.order_title');
	
	
	print "<h3>id_category = $id_category </h3>";
	print "<div>productcount = ".count($productsarr)."</div>";
	
	
	
	
	$iter=1;
	foreach($productsarr as $product_elem)
	{
		$iter++;
		print "<div>$iter</div>";
		
		//if($iter>3){break;}
		//$nameblock=$product_elem->find('a.ci_title',0);
		$name=strip_tags($product_elem->plaintext);
		
	
	    print "<h2>name= $name </h2>";
		
		//preg_match("/\bopenOrderSub\('p\b(\d+)/i", $product_elem->outertext, $matches);
		// print "<br/>";
		
		 $name=trim($name);
		if( ($P==1  && !empty($name)))
		{
			
			if( existsproduct($name,$link) )
			{
				 print "-Уже существует в Базе<br/>";
			}
			else
			{
					
				$params=array();
				$paramsval=array();
		
					
				eregi("p([0-9]{1,4})\'",$product_elem->outertext,$regs);
				$idtable=trim($regs[1]);
				//print "<h2>onClick= ".$idtable." </h2>";
				
				$table=$html->find("table[id=p$idtable]",0);
				$tr=$table->find("tr",0);
				
				//print "<div>".$tr->innertext." </div>";
				
				$tablecells=0;
				$th=$tr->find("th");
				print "<div>count = ".count($th)."</div>";
				foreach($th as $h)
				{
					
					$params[]=$h->plaintext;
					print $h->plaintext."<br/>";	
					$tablecells++;
				}
				
				
				$pricetd=$table->find("tr",1)->find("td",4);
				$numprice=(int)$pricetd->plaintext;
				print  "price=".$numprice."<br/>";	
				
				
				$imgurl="";
				$description="";
				
				$prodid=addproduct($name,$numprice,1,0,$id_category,$oc_prefix,$imgurl,$description,$link);
				
		
				
				$trs=$table->find("tr");
				$i=0;
				//print "<div>count = ".count($th)."</div>";
				foreach($trs as $row)
				{
					
					
					$td=$row->find("td");
					foreach($td as $i=>$cell)
					{
					
						print "params[$i]";
						print $params[$i];
						print " = ";	
						$paramsval[$i] =$cell->plaintext;
						print $paramsval[$i];
						print "<br/>";	
						
						
						
						//
					}
					print "<br/>";	
					print_r($paramsval);
					print "<br/>";
					$rsgs=array();
					$sizeparam="";
					if(!empty($paramsval[1]))
					{
						$model_option=$paramsval[1];
						$sizeparam=$paramsval[2];
						
						//$sizeparam=str_replace($name,"",$sizeparam);
						//$sizeparam=substr($sizeparam,strlen($name));
						
						$regxp_skobka=strpos($sizeparam,'(');
						$sizeparam=substr($sizeparam,0,$regxp_skobka);
						print "sizeparam= $sizeparam<br>";
						eregi("([0-9]{1,4})х([0-9]{1,4})",$sizeparam,$rsgs);
						
						$sizeparam=$rsgs[0]; 
						print "sizeparam= $sizeparam<br>";
						
						$quantity=(int)$paramsval[3];
						$amount_option=$quantity;
						$price=(int)$paramsval[4];
						
						$price=$price-$numprice;
						if($price>0){$prefprice="+";}else{$prefprice="-";}
						print "rprice=$price<br/>";
						$price=abs($price);
						
						
						
						if($price>=0)
						{
							addT3options($sizeparam,14,$prodid,$quantity,$price,$prefprice,$model_option,'упак',$amount_option,$oc_prefix,$link);
						}
					}
				}
				unset($params);
				unset($paramsval);
				
				
			}
		}
		print "---------------------------------------------------";
	}
	  
	$html->clear(); // подчищаем за собой
	unset($html);
	
}

?>