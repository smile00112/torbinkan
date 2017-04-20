<?php
function str_random($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
}
function clearlist_product($link)
{
	$query="DELETE FROM `parsurl` WHERE `id`>0";
	//print $query."\r\n";
	mysql_query($query,$link) or die("Invalid query: " . mysql_error());
	return 1;
	//}else {return false;}
	
}
function addtolist_product_item($categid,$donorurl,$link)
{
	$query0="SELECT * FROM `parsurl`  WHERE `donorurl`='".$donorurl."' AND `category_id`='".$categid."'";
	//print $query0."\r\n";
	$res0=mysql_query($query0,$link);
	if(mysql_num_rows($res0)<1)
	{
		$query="INSERT INTO `parsurl` ( `category_id`, `donorurl`) VALUES ( '".$categid."', '".$donorurl."')";
		mysql_query($query,$link);
	}
}
function addtocateg($productid,$categid,$link)
{
		
	$query="SELECT * FROM `".DB_PREFIX."product_to_category` WHERE `category_id`='".$categid."' AND `product_id`='".$productid."'";
	//print "<div>".$query."</div>";
	$result = mysql_query($query,$link);	
	if(mysql_num_rows($result)<1)
	{
		//Категория
		$query3="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
		VALUES ('".$categid."','".$productid."','1')";
		//print $query3."<br/>";
		mysql_query($query3,$link);
	}

}
function selectcateg($link)
{
	$toprint ="<select name='category_id'>";
	$query="SELECT * FROM `".DB_PREFIX."category_description` WHERE language_id='1' ORDER by `name`";
	//print "<div>".$query."</div>";
	$result = mysql_query($query,$link);
	
	$nm=mysql_num_rows($result);
	
	while ($row = mysql_fetch_array($result)) {
		$q1="SELECT `cd` . * , `c`.`parent_id`
		FROM `".DB_PREFIX."category` AS `c`
		LEFT JOIN `".DB_PREFIX."category_description` AS `cd` ON `c`.`parent_id` = `cd`.`category_id`
		WHERE `c`.`category_id` = '".$row['category_id']."'";

		//$q1="SELECT * FROM `".DB_PREFIX."category` WHERE category_id='".$row['category_id']."'";
		$res1 = mysql_query($q1);
		$rw1 = mysql_fetch_array($res1);
		$sel="";
		if(!empty($_POST['category_id'])){
			if($_POST['category_id']==$row['category_id']){$sel='selected';}
		}
		$toprint .="\r\n<option $sel value='".$row['category_id']."'>".$rw1['name']." - ".$row['name']."</option>\r\n";
	}
	$toprint .="</select>";

	return $toprint;
}


function remove_title($title,$option)
{
	$arrstr=explode(" ", $title);
	foreach($arrstr as $k=>$v)
	{
		if($v!=' ' && !empty($v))
		{	
			////print "elem=$v<br>";
			
			$option=str_replace($v,"",$option);
		}
	}
	$option=str_replace("-","",$option);
	//$option=str_replace("»","",$option);
	$option=trim(strip_tags($option));
	$option = preg_replace("/[0-9]{1}/", "", $option); 
	////print "resoption= $option<br>";
	return $option;
}
function my_get_html($url)
{
	
	if($fp = gzopen($url,'r'))
	{	
		$contents = '';
		while($html = gzread($fp , 256000))
		{
			$contents .= $html;
		}
		gzclose($fp);
		return $contents;
	}
	//print "Невозможно открыть url -$url\r\n";
	return false;
}

function copyimages_ocstore($imageurl,$ocproduct,$link2,$oc_prefix)
{
   $ocpath=DIR_IMAGE."/data/products/"; //Путь к директории Ocstore
 
   $img = str_random(10).'.jpg';
   
   file_put_contents($ocpath.$img, file_get_contents($imageurl));
   $imgurldb="data/products/".$img;
   
   $query1="UPDATE ".$oc_prefix."product SET `image`='$imgurldb' WHERE product_id='$ocproduct'";
   //print $query1."<br/>";
   mysql_query($query1,$link2);
   
   $query0="SELECT * FROM ".$oc_prefix."product_image  WHERE `image`='$imgurldb' ";
   $res0=mysql_query($query0,$link2);
   if(mysql_num_rows($res0)>0)
   {
      $query1="UPDATE ".$oc_prefix."product_image SET `image`='$imgurldb' WHERE product_id='".$ocproduct."'";
      ////print $query1."<br/>";
      //mysql_query($query1,$link2);
   }
   else
   {
      $query2="INSERT INTO ".$oc_prefix."product_image(`product_id`,`image`,`sort_order`) VALUES ('".$ocproduct."','$imgurldb',1)";
      //print $query2."<br/>";
      mysql_query($query2,$link2);
   }
   

}

function set_product_image_option($option_value_id,$prodid,$sortid,$oc_prefix,$link2)
{
	
	$query0="SELECT * FROM `".$oc_prefix."product_image`  WHERE `product_id`='$prodid' AND `sort_order`='$sortid'";
	$res0=mysql_query($query0,$link2);
	$row0=mysql_fetch_array($res0);

	if(!empty($row0['product_image_id']))
	{
		$query3="INSERT INTO ".$oc_prefix."product_image_option(`product_id`,`option_value_id`,`image`) 
		VALUES ('".$prodid."','".$option_value_id."','".$row0['image']."')";
		//print $query3."<br/>";
		mysql_query($query3,$link2);		
	}
}
function add_product_brend($brandname,$productid,$oc_prefix,$link2)
{
	$query0="SELECT * FROM `".$oc_prefix."manufacturer`  WHERE `name`='$brandname'";
	$res0=mysql_query($query0,$link2);
	//
	if(mysql_num_rows($res0)<1)
	{
		$query1="INSERT INTO ".$oc_prefix."manufacturer(`name`) 
		VALUES ('".$brandname."')";
		//print $query1."<br/>";
		mysql_query($query1,$link2);
		$manufacturer_id=mysql_insert_id();
		
		$query1="INSERT INTO ".$oc_prefix."manufacturer_description(`manufacturer_id`,`description`,`language_id`) 
		VALUES ('".$manufacturer_id."','".$brandname."',1)";
		//print $query1."<br/>";
		mysql_query($query1,$link2);
		
		$query1="INSERT INTO ".$oc_prefix."manufacturer_description(`manufacturer_id`,`description`,`language_id`) 
		VALUES ('".$manufacturer_id."','".$brandname."',2)";
		//print $query1."<br/>";
		mysql_query($query1,$link2);

		$query2="INSERT INTO ".$oc_prefix."manufacturer_to_store(`manufacturer_id`,`store_id`) 
		VALUES ('".$manufacturer_id."',0)";
		//print $query2."<br/>";
		mysql_query($query2,$link2);

	}
	else
	{
		$row0=mysql_fetch_array($res0);
		
		$manufacturer_id=$row0['manufacturer_id'];
		
	}
	$query3="UPDATE ".$oc_prefix."product SET `manufacturer_id`='".$manufacturer_id."' WHERE  `product_id`='".$productid."'";
	//print $query3."<br/>";
	mysql_query($query3,$link2);
		
	
}

function add_relation_options($relatedoptions_id,$option_value_id,$option_id,$prodid,$oc_prefix,$link2)
{
	if(empty($relatedoptions_id))
	{
		$quantity=100;
		$query1="INSERT INTO ".$oc_prefix."relatedoptions(`product_id`,`quantity`) 
		VALUES ('".$prodid."','".$quantity."')";
		//print $query1."<br/>";
		mysql_query($query1,$link2);
		$relatedoptions_id=mysql_insert_id();
		
		$query2="INSERT INTO ".$oc_prefix."relatedoptions_variant_product(`relatedoptions_variant_id`,`product_id`,`relatedoptions_use`) 
		VALUES (1,'".$prodid."',1)";
		//print $query2."<br/>";
		mysql_query($query2,$link2);
		
	}
	$query3="INSERT INTO ".$oc_prefix."relatedoptions_option(`relatedoptions_id`,`product_id`,`option_id`,`option_value_id`) 
	VALUES ('".$relatedoptions_id."','".$prodid."','".$option_id."','".$option_value_id."')";
	//print $query3."<br/>";
	mysql_query($query3,$link2);
	
	
	return $relatedoptions_id;

}
function add_image_product($imageurl,$prodid,$oc_prefix,$link2,$baseimg=false)
{
	$ocpath=DIR_IMAGE."/data/products/"; //Путь к директории Ocstore
	
	if(strstr($imageurl, '.png'))
	{
		$img = str_random(10).'.png';	
	}
	else
	{
		$img = str_random(10).'.jpg';	
	}
	file_put_contents($ocpath.$img, file_get_contents($imageurl));
	$imgurldb="data/products/".$img;
	
		
	$query0="SELECT * FROM `".$oc_prefix."product_image` WHERE `product_id`='".$prodid."' ORDER BY sort_order";
	$res0=mysql_query($query0,$link2);
	
	$row0=mysql_fetch_array($res0);
	$numimg=mysql_num_rows($res0);
	
	
	
	$numimg=$numimg+1;
	
	$query1="INSERT INTO ".$oc_prefix."product_image(`product_id`,`image`,`sort_order`) 
		VALUES ('".$prodid."','".$imgurldb."','".$numimg."')";
		////print $query1."<br/>";
	
	
	if($numimg>1)
	{  $addimg=$row0['image']; 	}
	else { 	$addimg=$imgurldb; }
	
	if($baseimg)
	{
		$query5="UPDATE ".$oc_prefix."product SET `image`='".$addimg."' WHERE product_id='".$prodid."'";
		//	//print $query5."<br/>";
		mysql_query($query5,$link2);
	}
	else{
		mysql_query($query1,$link2);	
		
	}
	$res0=null;
	
}	
function add_image_options($option_value_id,$imageurl,$prodid,$oc_prefix,$link2)
{
	
	$ocpath=DIR_IMAGE."/data/products/"; //Путь к директории Ocstore
	
	
	if(strstr($imageurl, '.png'))
	{
		$img = str_random(10).'.png';	
	}
	else
	{
		$img = str_random(10).'.jpg';	
	}
	$contentfile=file_get_contents($imageurl);
	if($contentfile)
	{
		file_put_contents($ocpath.$img,$contentfile );
		$imgurldb="data/products/".$img;
		
		
		
		$query0="SELECT * FROM `".$oc_prefix."product_image` WHERE `product_id`='".$prodid."'";
		$res0=mysql_query($query0,$link2);
		$numimg=mysql_num_rows($res0);
		
		$numimg=$numimg+1;
		
		$query1="INSERT INTO ".$oc_prefix."product_image(`product_id`,`image`,`sort_order`) 
			VALUES ('".$prodid."','".$imgurldb."','".$numimg."')";
			////print $query1."<br/>";
		mysql_query($query1,$link2);	
		
		$query5="UPDATE ".$oc_prefix."product SET `image`='$imgurldb' WHERE product_id='".$prodid."'";
		//	//print $query5."<br/>";
			mysql_query($query5,$link2);
		
		$query0="SELECT * FROM `".$oc_prefix."product_image_option` WHERE `product_id`='".$prodid."' AND `option_value_id`='".$option_value_id."'";
		$res0=mysql_query($query0,$link2);
		if(mysql_num_rows($res0)<1)
		{
			$query3="INSERT INTO ".$oc_prefix."product_image_option(`product_id`,`option_value_id`,`image`) 
			VALUES ('".$prodid."','".$option_value_id."','".$imgurldb."')";
			////print $query3."<br/>";
			mysql_query($query3,$link2);		
		
		}
		else
		{
			$query3="UPDATE ".$oc_prefix."product_image_option
			SET `image`='".$imgurldb."' WHERE
			`product_id`='".$prodid."' AND `option_value_id`='".$option_value_id."'";
			////print $query3."<br/>";
			mysql_query($query3,$link2);	
			
		}
	}
}
 
function addnewoptions($value,$optname,$prodid,$type,$oc_prefix,$language_id,$link2)
{
	$query0="SELECT * FROM `".$oc_prefix."option_description` WHERE `name`='".$optname."' AND language_id='$language_id'";
	$res0=mysql_query($query0,$link2);
	//Если нет названия такой опции то добавляем.
	if(mysql_num_rows($res0)<1)
	{
		$query3="INSERT INTO ".$oc_prefix."option(`type`,`sort_order`) 
			VALUES ('".$type."',1)";
		mysql_query($query3,$link2);
		$option_id=mysql_insert_id();
		
		$query3="INSERT INTO ".$oc_prefix."option_description(`option_id`,`language_id`,`name`) 
			VALUES ('".$option_id."','".$language_id."','".$optname."')";
		mysql_query($query3,$link2);
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$option_id=$row0['option_id'];
	}
	
	$query0="SELECT * FROM `".$oc_prefix."option_value_description` WHERE `name`='".$value."' AND `option_id`='".$option_id."'";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		$query2="INSERT INTO ".$oc_prefix."option_value(`option_id`,`sort_order`) 
		VALUES ('".$option_id."','1')";
		////print $query2."<br/>";
		mysql_query($query2,$link2);
		
		$option_value_id=mysql_insert_id();
		
		$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
		VALUES ('".$option_value_id."',1,'".$option_id."','".$value."')";
		////print $query3."<br/>";
		mysql_query($query3,$link2);

		$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
		VALUES ('".$option_value_id."',2,'".$option_id."','".$value."')";
		////print $query3."<br/>";
		mysql_query($query3,$link2);
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$option_value_id=$row0['option_value_id'];
	}
	
	$query0="SELECT * FROM `".$oc_prefix."product_option` WHERE `product_id`='".$prodid."' AND `option_id`='".$option_id."'";
	////print $query0."<br/>";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		$query3="INSERT INTO ".$oc_prefix."product_option(`product_id`,`option_id`,`required`) 
		VALUES ('".$prodid."','".$option_id."',1)";
		////print $query3."<br/>";
		mysql_query($query3,$link2);		
		
		$product_option_id=mysql_insert_id();
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$product_option_id=$row0['product_option_id'];
	}

	
	$query0="SELECT * FROM `".$oc_prefix."product_option_value` WHERE `product_option_id`='".$product_option_id."' AND `product_id`='".$prodid."'
	AND `option_id`='".$option_id."' AND  `option_value_id`='".$option_value_id."' ";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		$query3="INSERT INTO ".$oc_prefix."product_option_value(`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`) 
		VALUES ('".$product_option_id."','".$prodid."','".$option_id."','".$option_value_id."','1')";
		print $query3."<br/>";
		mysql_query($query3,$link2);
	}

	return $option_value_id;
	
	
	
}

function addT3options($value,$option_id,$prodid,$quantity,$price,$prefprice,$model_option,$unit_option,$amount_option,$oc_prefix,$link2)
{
	
	
	$query0="SELECT * FROM `".$oc_prefix."option_value_description` WHERE `name`='".$value."' AND `option_id`='".$option_id."'";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		$query2="INSERT INTO ".$oc_prefix."option_value(`option_id`,`sort_order`) 
		VALUES ('".$option_id."','1')";
		////print $query2."<br/>";
		mysql_query($query2,$link2);
		
		$option_value_id=mysql_insert_id();
		
		$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
		VALUES ('".$option_value_id."',1,'".$option_id."','".$value."')";
		////print $query3."<br/>";
		mysql_query($query3,$link2);

		$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
		VALUES ('".$option_value_id."',2,'".$option_id."','".$value."')";
		////print $query3."<br/>";
		mysql_query($query3,$link2);
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$option_value_id=$row0['option_value_id'];
	}
	
	
	$query0="SELECT * FROM `".$oc_prefix."product_option` WHERE `product_id`='".$prodid."' AND `option_id`='".$option_id."'";
	////print $query0."<br/>";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		$query3="INSERT INTO ".$oc_prefix."product_option(`product_id`,`option_id`,`required`) 
		VALUES ('".$prodid."','".$option_id."',1)";
		////print $query3."<br/>";
		mysql_query($query3,$link2);		
		
		$product_option_id=mysql_insert_id();
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$product_option_id=$row0['product_option_id'];
	}
	
	
	
	

	
	$query0="SELECT * FROM `".$oc_prefix."product_option_value` WHERE `product_option_id`='".$product_option_id."' AND `product_id`='".$prodid."'
	AND `option_id`='".$option_id."' AND  `option_value_id`='".$option_value_id."' ";




	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
	}
		$query3="INSERT INTO ".$oc_prefix."product_option_value(`product_option_id`,`product_id`,`option_id`,`option_value_id`,
		`quantity`,`price`,`price_prefix`,`model_option`,`unit_option`,`amount_option`) 
		VALUES ('".$product_option_id."','".$prodid."','".$option_id."','".$option_value_id."','$quantity','".$price."'
		,'".$prefprice."','".$model_option."','".$unit_option."','".$amount_option."')";
		print $query3."<br/>";
		mysql_query($query3,$link2);
	
	
	return $option_value_id;
		
}

function addoptions($value,$optid,$prodid,$oc_prefix,$link2)
{
	

	$selopt=$optid;
	
	if(!empty($selopt) && !empty($prodid) )
	{
		$query0="SELECT * FROM `".$oc_prefix."product_option` WHERE `product_id`='".$prodid."' AND `option_id`='".$selopt."'";
		////print $query0."<br/>";
		$res0=mysql_query($query0,$link2);
		if(mysql_num_rows($res0)<1)
		{
			$query3="INSERT INTO ".$oc_prefix."product_option(`product_id`,`option_id`,`required`) 
			VALUES ('".$prodid."','".$selopt."',1)";
			////print $query3."<br/>";
			mysql_query($query3,$link2);		
			
			$product_option_id=mysql_insert_id();
		}
		else
		{
			$row0=mysql_fetch_array($res0);
			$product_option_id=$row0['product_option_id'];
		}
		
		////print "<div>product_option_id=$product_option_id</div>\r\n";
		
		$query0="SELECT * FROM `".$oc_prefix."option_value_description` WHERE `name`='".$value."' AND `option_id`='".$selopt."'";
		$res0=mysql_query($query0,$link2);
		if(mysql_num_rows($res0)<1)
		{
			$query2="INSERT INTO ".$oc_prefix."option_value(`option_id`,`sort_order`) 
			VALUES ('".$selopt."','1')";
			////print $query2."<br/>";
			mysql_query($query2,$link2);
			
			$option_value_id=mysql_insert_id();
			
			$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
			VALUES ('".$option_value_id."',1,'".$selopt."','".$value."')";
			////print $query3."<br/>";
			mysql_query($query3,$link2);

			$query3="INSERT INTO ".$oc_prefix."option_value_description(`option_value_id`,`language_id`,`option_id`,`name`) 
			VALUES ('".$option_value_id."',2,'".$selopt."','".$value."')";
			////print $query3."<br/>";
			mysql_query($query3,$link2);
		}
		else
		{
			$row0=mysql_fetch_array($res0);
			$option_value_id=$row0['option_value_id'];
		}
		
		$query0="SELECT * FROM `".$oc_prefix."product_option_value` WHERE `product_option_id`='".$product_option_id."' AND `product_id`='".$prodid."' AND `option_id`='".$selopt."' AND  `option_value_id`='".$option_value_id."' ";
		$res0=mysql_query($query0,$link2);
		if(mysql_num_rows($res0)<1)
		{
			$query3="INSERT INTO ".$oc_prefix."product_option_value(`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`) 
			VALUES ('".$product_option_id."','".$prodid."','".$selopt."','".$option_value_id."','1')";
			print $query3."<br/>";
			mysql_query($query3,$link2);
		}
		else
		{
			/*
			$query3="UPDATE ".$oc_prefix."product_option_value 
			SET `option_value_id`='".$option_value_id."'
			WHERE `product_option_id`='".$product_option_id."' AND `product_id`='".$prodid."' AND `option_id`='".$selopt."'";
			//print $query3."<br/>";
			mysql_query($query3,$link2);
			*/
		}

		return $option_value_id;
	}
}

function addatribute($value,$kname,$prodid,$oc_prefix,$link2)
{
	
	$query0="SELECT * FROM `".$oc_prefix."attribute_description` WHERE `name`='".$kname."'";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{	
		$query3="INSERT INTO `".$oc_prefix."attribute`(`attribute_group_id`,`sort_order`) 
		VALUES (8,0)";
		 
		mysql_query($query3,$link2);		
		$attribute_id=mysql_insert_id();
		
		$query4="INSERT INTO `".$oc_prefix."attribute_description`(`attribute_id`,`language_id`,`name`) VALUES ('".$attribute_id."',1,'".$kname."')";
		mysql_query($query4,$link2);	
		$query5="INSERT INTO `".$oc_prefix."attribute_description`(`attribute_id`,`language_id`,`name`) VALUES ('".$attribute_id."',2,'".$kname."')";
		mysql_query($query5,$link2);
	}
	else
	{
		$row0=mysql_fetch_array($res0);
		$attribute_id=$row0['attribute_id'];
	}
	
	$query5="INSERT INTO `".$oc_prefix."product_attribute`(`product_id`,`attribute_id`,`language_id`,`text`)
												   VALUES ('".$prodid."','".$attribute_id."',1,'".$value."')";
	mysql_query($query5,$link2);
	
	
	
}

function get_manufacturer_from_text($text,$oc_prefix,$link2)
{
	$arrstr=explode(" ", $text);
	
	foreach($arrstr as $v)
	{
		
		
	}
	//$query0="SELECT * FROM `".$oc_prefix."option_value_description` WHERE `name`='".$value."' AND `option_id`='".$selopt."'";
	//$res0=mysql_query($query0,$link2);
}
function existsproduct($name,$link2)
{
	global $oc_prefix;
	$name=mysql_real_escape_string($name);
	
	$query0="SELECT * FROM ".$oc_prefix."product  WHERE `model`='".$name."'";
	//print $query0."<br/>";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)>0)
	{
		$row=mysql_fetch_array($res0);
		return $row['product_id'];
		
		
		
	}
	else{
		return false;
	}
}
function addupdatetocateg($id_category,$prodid,$oc_prefix,$link2)
{
	//Проверка есть ли такая категория у товара
		$q1="SELECT * FROM ".$oc_prefix."product_to_category  WHERE `category_id`='".$id_category."'";
		$rs1=mysql_query($q1,$link2);
		reqursive_update_categ($id_category,$prodid,$oc_prefix,$link2);
		if(mysql_num_rows($rs1)<1)
		{
			
			//родительская категория
			$q2="SELECT * FROM ".$oc_prefix."category  WHERE `category_id`='".$id_category."'";
			$rs2=mysql_query($q2,$link2);
			if(mysql_num_rows($rs2)>0)
			{
				$rw1=mysql_fetch_array($rs1);
    			$newcateg=$rw1['parent_id'];
				//Категория
				if($newcateg>0)
				{
					$query33="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
					VALUES ('".$newcateg."','".$prodid."','1')";
					////print $query3."<br/>\r\n";
					mysql_query("\r\n addcateg=$newcateg stage1".$query33,$link2);
					addtolog($query33);
				}
			}
			
			
			if($id_category>0)
			{
				//Категория
				$query3="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
				VALUES ('".$id_category."','".$prodid."','0')";
				print "\r\n addupptocateg -";
				print $query3."<br/>\r\n";
				mysql_query($query3,$link2);
				addtolog('addupptocateg - '.$query3);
			}
		}
		
}
function reqursive_update_categ($id_category,$prodid,$oc_prefix,$link2)
{
	//родительская категория
	$q2="SELECT * FROM ".$oc_prefix."category  WHERE `category_id`='".$id_category."'";
	//addtolog("\r\n reqursive-$id_category ".$q2);
	$rs2=mysql_query($q2,$link2);
	if(mysql_num_rows($rs2)>0)
	{
		$rw1=mysql_fetch_array($rs2);
		$newcateg=$rw1['parent_id'];
		
		
		$q1="SELECT * FROM ".$oc_prefix."product_to_category  WHERE `category_id`='".$newcateg."' AND `product_id`='".$prodid."'";
		//addtolog(" rer-$newcateg ".$q1);
		$rs1=mysql_query($q1,$link2);
		if(mysql_num_rows($rs1)<1)
		{	
			if($newcateg>0)
			{
				//Категория
				$query33="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
				VALUES ('".$newcateg."','".$prodid."','1')";
				//addtolog("\r\n  ".$query33);
				////print $query3."<br/>\r\n";
				mysql_query($query33,$link2);
			}
		}

		if(!empty($rw1['parent_id']))
		{
			reqursive_update_categ($newcateg,$prodid,$oc_prefix,$link2);
		}
		return true;
	}
	else
	{
		return false;
	}
	
}

function updateproduct($prodid,$oc_prefix,$link2,$arrparam=array())
{
	$set=array();
	foreach($arrparam as $k=>$val)
	{
		$set[]="`$k`='$val'";
		
	}
		
	$query2="UPDATE ".$oc_prefix."product
	SET ".implode(",",$set). " WHERE `product_id`='$prodid'";
	//print $query2."<br/>";
	mysql_query($query2,$link2);
}


function addproduct($name,$price,$quantity,$manuf,$id_category,$oc_prefix,$urlimage,$description,$link2)
{
	$name=mysql_real_escape_string($name);
	$description=mysql_real_escape_string($description);
	 
	$name=str_replace("'","",$name);
	$description=str_replace("'","",$description);
	
	$query0="SELECT * FROM ".$oc_prefix."product  WHERE `model`='".$name."'";
	////print $query0."<br/>";
	$res0=mysql_query($query0,$link2);
	if(mysql_num_rows($res0)<1)
	{
		//$name $price $active $quantity 	$manuf
		$main=0;
		$active=1;

		$query2="INSERT INTO ".$oc_prefix."product(`model`,`price`,`status`,`stock_status_id`,`quantity`,`manufacturer_id`)
		VALUES ('".$name."','".$price."','".$active."',5,'".$quantity."','".$manuf."')";
		print $query2."<br/>";
		mysql_query($query2,$link2);
		$prodid=mysql_insert_id();
		
		
		//родительская категория
		$q1="SELECT * FROM ".$oc_prefix."category  WHERE `category_id`='".$id_category."'";
		$rs1=mysql_query($q1,$link2);
		if(mysql_num_rows($rs1)>0)
		{
			$rw1=mysql_fetch_array($rs1);
			 
			//Категория
			$query3="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
			VALUES ('".$rw1['parent_id']."','".$prodid."','1')";
			////print $query3."<br/>";
			mysql_query($query3,$link2);
			
		}
		
		//Категория
		$query3="INSERT INTO ".$oc_prefix."product_to_category(`category_id`,`product_id`,`main_category`) 
		VALUES ('".$id_category."','".$prodid."','".$main."')";
		////print $query3."<br/>";
		mysql_query($query3,$link2);
		
		$query41="INSERT INTO ".$oc_prefix."product_to_store(`store_id`,`product_id`) VALUES ('0','".$prodid."')";
		////print $query41."<br/>";
		mysql_query($query41,$link2);
		
		
					
		$query42="INSERT INTO ".$oc_prefix."product_description VALUES($prodid, 1, '".$name."','".$description."','', '".$name."','".$name."', '".$name."', '".$name."', '".$name."', '')";
		//print $query42."<br/>";
		mysql_query($query42,$link2) or die("Invalid query: " . mysql_error());
		
		
		$query43="INSERT INTO ".$oc_prefix."product_description VALUES($prodid, 2, '".$name."','".$description."','', '".$name."', '".$name."', '".$name."', '".$name."', '".$name."', '')";
		//print $query43."<br/>";
		mysql_query($query43,$link2) or die("Invalid query: " . mysql_error());
		//copyimages_ocstore($urlimage,$prodid,$link2,$oc_prefix);
		return $prodid;
	}
	return null;
}
?>