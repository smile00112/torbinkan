<?php
header('Content-type: text/html; charset="utf8"'); 

//print_r($_POST);
require_once("../config.php");
require_once("parsclass.php");
require_once("function.php");
require_once("./simplehtmldom/simple_html_dom.php");

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
   
   $url2=$_POST['url'];
	$id_category=$_POST['categid'];
$kprice=1;
 if ($link && !empty($url2)) {
   mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
	
}else {die();}  
   
   print $url2."<br>";
   print "id_category=$id_category<br>";
   
   $html2 = new simple_html_dom();
   $html2 = str_get_html(my_get_html($url2));

   $name=$html2->find('span.heading',0)->innertext;
   print "<h2>name= ".$name."</h2>"; 
  
   $priceblock=$html2->find('div.product__bar_price',0)->find('span',0);
   print "Цена = "; 
   
   $price=strip_tags($priceblock->innertext);
               
   $price=str_replace(" ","",$price);
   $numprice=0;
   $numprice+=$price;
   $numprice=$kprice*$numprice;
   
   print $numprice;
   print "<br/>";
   



   if(existsproduct($name,$link))
   {
      $prodid=existsproduct($name,$link);
      addtocateg($prodid,$id_category,$link);
      reqursive_update_categ($id_category,$prodid,$oc_prefix,$link);
      $arrparam=array("price"=>$numprice);
      updateproduct($prodid,$oc_prefix,$link,$arrparam);
      print "-Уже существует в Базе =$prodid<br/>";
   }
   else
   {
      
     
      
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

	
	  
	
?>