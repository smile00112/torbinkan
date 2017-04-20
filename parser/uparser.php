<?
require_once("../config.php");

$LIMITLOG=1000; //количество записей журнала посещений
global $ocpath;
$ocpath="/www/krep-stroy.ru/"; //Путь к директории Ocstore

global $oc_prefix;
$oc_prefix=DB_PREFIX;//Префикс в БД Ocstore
$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if ($link) {
    mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');
}

?>
<!DOCTYPE HTML>
<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
<script>
function strip(html)
{
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent||tmp.innerText;
}
//var arr = ["http://www.gssport.ru/shop/category/165"];
//,"http://www.gssport.ru/shop/category/winter/skiclothing/menscollection/jackets"];
var arr = new Array();
var arrcateg = new Array();
var arrpages = new Array();

var arrproducturl = new Array();
var arrproductid = new Array();
var arrproduct_categid = new Array();

var zzz=0;

 var numhnd=0;
 var numtovars=0;
$(document).ready(function(){
	
	
	$.ajax({
      type: "POST",
	  data: "test=1",
	  url: "sparser.php",
	  success: function(data){
		//alert( "Прибыли данные: " + data );
			resdata1= strip(data);
			$("#results").append(""+data+"\r\n");
		//$("#results").append("<div>"+data+"</div>");
		$( "#runbutn" ).show();	
		$( "#clrbutn" ).show();	
		$( "#runparseproduct" ).show();	
		$( "#repeatlink" ).hide();	
	  }
	});
	//alert($("#url").val());
	//arr[0]=$("#url").val();
	<?php
      $query="SELECT *,cd.category_id as categid FROM `parscateg` as p 
		LEFT JOIN `".DB_PREFIX."category_description` as cd ON p.category_id=cd.category_id
		WHERE cd.language_id='1' AND p.parse=1 ORDER by cd.`name`";
		//print "<div>".$query."</div>";
		$result = mysql_query($query,$link);

		$nm=mysql_num_rows($result);
		$ii=0;
		while ($row = mysql_fetch_array($result)) {
			$q1="SELECT `cd` . * , `c`.`parent_id`
			FROM `".DB_PREFIX."category` AS `c`
			LEFT JOIN `".DB_PREFIX."category_description` AS `cd` ON `c`.`parent_id` = `cd`.`category_id`
			WHERE `c`.`category_id` = '".$row['categid']."'";
			
			$res1 = mysql_query($q1,$link);
			$rw1 = mysql_fetch_array($res1);
			
			//print "<tr><td>".$row['categid']." - ".$rw1['name']." - ".$row['name']."</td><td>".$row['donorurl']."</td></tr>";
			$arrelem[]=$row['donorurl'];
			$arrnumcateg[]=$row['categid'];
			
			print "arr[$ii]='".$row['donorurl']."';";
			print "arrcateg[$ii]=".$row['categid'].";";
			//print '"http://www.gssport.ru/shop/category/165"]';
			$ii++;
		}
		$comma_separated = implode('","', $arrelem);
		$comma_num_sep = implode(',', $arrnumcateg);
		print "//array categ\r\n";
		//print "arr = [\"$comma_separated\"];\r\n";
		//print "arrcateg = [$comma_num_sep];\r\n";
	?>
	
	//Список страниц конкретно товаров
	<?
		$query="SELECT * FROM `parsurl`  WHERE statusdonor=1";
		//print "<div>".$query."</div>";
		$result = mysql_query($query,$link);

		$nm=mysql_num_rows($result);
		$ii=0;
		while ($row = mysql_fetch_array($result)) {

			print "arrproducturl[$ii]='".$row['donorurl']."';\r\n";
			print "arrproduct_categid[$ii]=".$row['category_id'].";";
			print "arrproductid[$ii]=".$row['productid'].";";
			//print '"http://www.gssport.ru/shop/category/165"]';
			$ii++;
		}
	?>

});

function parsbreak()
{
	zzz=1;
}	
function parsproduct()
{
	var allcount=arrproducturl.length;
	var iteration=0;
	$("#results").empty();
	$("#mainres").empty();
	var kprice=$("#koefprice").val();
	var kprice2=$("#koefprice2").val();
	zzz=0;
	
	// $.ajax({
		  // type: "POST",
		  // data: "delord=1",
		  // url: "sparser.php",
		  // success: function(resdata1){
			// //resdata1= strip(resdata1);
			  // //$("#results").append(":"+resdata1+"\r\n");
			  // if(resdata1==1)
			  // {
				// $("#mainres").html("<div>товары отсутствующие у доноров деактивированы<br/></div>");
			  // }
			  // else
			  // {
				// $("#mainres").html("<div>Произошла ошибка во время очистки товаров...<br/></div>");  
			  // }
		  // }
	// });
	
	
	
	arrproducturl.forEach(function(item, i, arr) {
		
			$.ajax({
				  type: "POST",

				  data: "productparsing="+encodeURIComponent(item)+"&categid="+arrproduct_categid[i]+"&kprice="+kprice+"&kprice2="+kprice2,
				  url: "sparser.php",
				  success: function(resdata1){
					 resdata1= strip(resdata1);
					  $("#results").append(":"+resdata1+"\r\n");
					  iteration++;
					  $("#mainres").html("<div>Идет парсинг...<br/>Обработано "+iteration+" товаров из "+allcount+"<br/></div>");
				  }
			}); 
			if(zzz==1){ return false;}
	});
}

function clrdata()
{
	$.ajax({
		  type: "POST",
		  data: "cleardata=1",
		  url: "sparser.php",
		  success: function(resdata1){
			//resdata1= strip(resdata1);
			  //$("#results").append(":"+resdata1+"\r\n");
			  if(resdata1==1)
			  {
				$("#mainres").html("<div>Список товаров для парсера очищен...<br/></div>");
			  }
			  else
			  {
				$("#mainres").html("<div>Произошла ошибка во время очистки списка...<br/></div>");  
			  }
		  }
	});
	
	
}

function handledata(arr)
{
	//for (var i = 0; i < arr.length; i++) 
	//{
	//arr[0]=$("#url").val();	
	
	var allnum=arr.length;
	$( "#runbutn" ).hide();	
	$( "#clrbutn" ).hide();	
	$( "#runparseproduct" ).hide();	
		$( "#repeatlink" ).show();	
	arr.forEach(function(item, i, arr) {
		//alert( i + ": " + item + " (массив:" + arr + ")" );
		//item=arr[i];
		//alert( i+" ="+ item); 
		//$( "#repeatlink" ).html('Прервать');
		
		//$("#results").append(""+ i + ": " + item + " (массив:" + arr + ")" +"\r\n");
		$("#results").append(""+ i +" :" +arrcateg[i]+": " + item + " " +"\r\n");

		$.ajax({
		  url: "sparser.php",
		  type: "POST",
		  data: "parsing=1&item="+ item,
		  success: function(resdata){
			
			
			$("#results").append("<div>"+ i + ": " + item  +"</div>");
			$("#results").append("<div>"+resdata+"</div>");
			
			arrpages=resdata.split('<br/>');
			
			
			
			
			
			//Обработка первой (текущей) страницы 
			$.ajax({
				  type: "POST",
				  //data: "pageparsing="+encodeURIComponent(item),
				  data: "pageparsing="+encodeURIComponent(item)+"&categid="+arrcateg[i],
				  url: "sparser.php",
				  success: function(resdata1){
					 resdata1= strip(resdata1);
					  $("#results").append("res2:"+resdata1+"\r\n");
					  numtovars++;
				  }
			});
			
			for (var k = 0; k < arrpages.length; k++) 
			{	
				//alert(arrpages[k]);
				$("#results").append("<div>"+ k + ": " + arrpages[k] + " (массив:" + arrpages + ")" +"</div>");
				$.ajax({
					type: "POST",
					data: "pageparsing="+encodeURIComponent(arrpages[k])+"&categid="+arrcateg[i],
					url: "sparser.php",
					success: function(resdata2){
					resdata2= strip(resdata2);
					$("#results").append(""+resdata2+"\r\n");
					//$("#results").append("<div>"+resdata2+"</div>");
					numtovars++;
					}
				});
			}
			 
			numhnd++;
			//$("#mainres").html("<div>Идет парсинг...<br/>Обработано "+numhnd+" категорий из "+allnum+"<br/>Обработано товаров "+numtovars+" </div>");
			$("#mainres").html("<div>Формируем список страниц товаров. Обработано "+numhnd+" категорий из "+allnum+" </div>");	
			
		  }
		});

	});
		
			//$( "#repeatlink" ).html('Повторить');	
			
		
	//}
}

</script>
<?php


?>
<div id='settings'>
<a id='settinglink' href='./settings.php'>Настройка парсера </a>
</div>
<?//<div><input type='text' size='90' id='url' value='http://www.gssport.ru/shop/category/165' /></div>?>
<div align='center'><a id='repeatlink' href='./uparser.php'>Вернуться </a></div>
<!--<div>коэффициент цены<input type='text' id='koefprice' value='1.0' /></div>-->
<div>Коэффициент наценки :<input id='koefprice' type='text' name='kprice' value='1.0'/></div>


<input type='button' style='display:none;' id='clrbutn' onClick='clrdata()' 	  		value='Очистить список товаров доноров' />
<input type='button' style='display:none;' id='runbutn' onClick='handledata(arr)' 		value='Сформировать список товаров доноров' />
<input type='button' style='display:none;' id='runparseproduct' onClick='parsproduct()' value='Парсинг' />


<div>
<a href='./provimage.php'>Удалить пустые изображения</a>
</div>
<div id='mainres' align='center'>
</div>

<textarea id='results' cols='80' rows='10' align='center'>

</textarea>



<div id='results2'>
</div>
</body>
</html>