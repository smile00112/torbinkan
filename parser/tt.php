<?php

require_once("../config.php");
require_once("parsclass.php");
require_once("function.php");
require_once("./simplehtmldom/simple_html_dom.php");

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

function pager($url,$links_block,$template)
{
	$urlarr=array();
   
   foreach($links_block as $elem)
	{
	}
   $max=0;
   $templ=str_replace('{num}','([0-9]{1,2})',$template);
   //print $templ."<br/>";
   
   $count=count($links_block);
   //print $count;
   $elem=$links_block[$count-1];
   //print "<div>".$elem->innertext."</div>";		
   //print "<div>".$elem->href."</div>";		
   if(ereg($templ, $elem->href,$regs))
   {
      //print_r($regs);
      $max=$regs[1];
   }
	if($max>0)	
	{
		for($i=2;$i<=$max;$i++)	
		{
			$templ2=str_replace("{num}",$i,$template);
			print "<div>$url/?".$templ2."</div>";		
         $urlarr[]=$url."/?".$templ2;
		}
	}
   return $urlarr;
}

if ($link) {
   mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
	
   
   
   if(!empty($_GET['iter'])){  $iter=$_GET['iter']-1;}else{$iter=0;}
   
   
   
   
   $where="`id`>$iter";
	$q="SELECT * FROM `parscateg` WHERE $where ORDER BY `id`";
	$res=mysql_query($q);

	while ($row = mysql_fetch_array($res)) 
	{
		//if (х % у == 0) { echo ('Число'.$x. 'Кратно'.$y); }
      //каждые 3 редирект
		// if ($iter % 3 == 0) { 
         // //echo ('Число'.$x. 'Кратно'.$y); 
         
         // print '
         // <script language="JavaScript"> 
           // window.location.href = "http://krep-stroy.ru/parser/tt.php?iter='+$iter+'"
         // </script>
         // ';
         
      // }
      print '
      <script language="JavaScript"> 
        window.location.href = "http://krep-stroy.ru/parser/tt.php?iter='+$iter+'"
      </script>
      ';
   
      $iter++;
      print "<br/>iter=$iter<br/>";
      print "<div>".$row['donorurl']."</div>";
		
		$url=$row['donorurl'];
		
		$html = new simple_html_dom();
		$html = str_get_html(my_get_html($url));
      
      //читаем товары------------
      $productsarr=$html->find('div.product-card');
      $categid=$row['category_id'];
      
      print "<div>count = ".count($productsarr)."</div>";
      
      foreach($productsarr as $product_elem)
      {

         $nameblock=$product_elem->find('div.product-info',0)->find('a',0);
         $urlfeat= $nameblock->href;
         addtolist_product_item($categid,$urlfeat,$link);
      }
      
      //------------------------
		
      $pagerblock=$html->find('div.pagination',0)->find('div.links',0);
		
      //addtolist_product_item($categid,$url,$link);
      if(count($pagerblock)>0)
      {
         $links_block=$pagerblock->find('a');
         $urlarr=pager($url,$links_block,"page={num}");
         
         //Листаем страницы
         foreach($urlarr as $urlelm)
         {
            
            $html2 = new simple_html_dom();
            $html2 = str_get_html(my_get_html($url));
            
            //читаем товары------------
            $productsarr=$html2->find('div.product-card');
            $categid=$row['category_id'];
            
            print "<div>count = ".count($productsarr)."</div>";
            foreach($productsarr as $product_elem)
            {
              
               $nameblock=$product_elem->find('div.product-info',0)->find('a',0);
               $urlfeat= $nameblock->href;
               addtolist_product_item($categid,$urlfeat,$link);
            }
            $html2->clear();
				unset($html2);
            //addtolist_product_item($categid,$urlelm,$link);
         }
      }
	}
}
?>