<?php  
header('Content-type: text/html; charset="utf8"');  
 

require_once("../config.php");
require_once("function.php");

$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if ($link) {
    mysql_query("set names utf8"); 
	mysql_select_db(DB_DATABASE, $link) or die('Could not select database.');

	
	
	if(isset($_GET['disableall']))
	{
		$id=$_GET['disableall'];
		$query="UPDATE  `parscateg` SET `parse`=0 WHERE 1";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}	
	if(isset($_GET['enableall']))
	{
		//$id=$_GET['disableall'];
		$query="UPDATE  `parscateg` SET `parse`=1 WHERE 1";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}	
	
	
	
	if(isset($_GET['disable']))
	{
		$id=$_GET['disable'];
		$query="UPDATE  `parscateg` SET `parse`=0 WHERE `id`='".$id."'";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}
	if(isset($_GET['enable']))
	{
		$id=$_GET['enable'];
		$query="UPDATE  `parscateg` SET `parse`=1 WHERE `id`='".$id."'";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}
	if(isset($_GET['del']))
	{
		$id=$_GET['del'];
		$query="DELETE FROM `parscateg` WHERE `id`='".$id."'";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}
	if(isset($_GET['swpars']))
	{
		$id=$_GET['id'];
		$swpars=$_GET['swpars'];
		if($swpars==0){$parse=1;}else{$parse=0;}
		
		$query="UPDATE  `parscateg` SET `parse`=$parse WHERE `id`='".$id."'";
		//print $query;
		mysql_query($query,$link);
		?><script>location.replace("./settings.php");</script><?
	}	
	if(!empty($_POST['addcategsoot']))
	{
		$category_id=$_POST['category_id'];
		$donorurl=$_POST['donorurl'];

		$query="INSERT INTO `parscateg` (`id`, `category_id`, `donorurl`) VALUES (NULL, '".$category_id."', '".$donorurl."')";
		mysql_query($query,$link);
		
		?><script>location.replace("./settings.php");</script><?
	}
	
	
	// function selectcateg($link)
	// {
		// $toprint ="<select name='category_id'>";
		// $query="SELECT * FROM `".DB_PREFIX."category_description` WHERE language_id='1' ORDER by `name`";
		// //print "<div>".$query."</div>";
		// $result = mysql_query($query,$link);
		
		// $nm=mysql_num_rows($result);
		
		// while ($row = mysql_fetch_array($result)) {
			// $q1="SELECT `cd` . * , `c`.`parent_id`
			// FROM `".DB_PREFIX."category` AS `c`
			// LEFT JOIN `".DB_PREFIX."category_description` AS `cd` ON `c`.`parent_id` = `cd`.`category_id`
			// WHERE `c`.`category_id` = '".$row['category_id']."'";

			// //$q1="SELECT * FROM `".DB_PREFIX."category` WHERE category_id='".$row['category_id']."'";
			// $res1 = mysql_query($q1);
			// $rw1 = mysql_fetch_array($res1);
			
			// $toprint .="\r\n<option value='".$row['category_id']."'>".$rw1['name']." - ".$row['name']."</option>\r\n";
		// }
		// $toprint .="</select>";
	
		// return $toprint;
	// }
	
	
}

?>
<h2>Настройка соответствия категорий у доноров</h2>
<a href='./uparser.php'>Назад </a>
<?/*
<table width='60%'>
<tr>
<td><input type='radio' name='seldonor'/>Все</td>
<td><input type='radio' name='seldonor'/>gssport</td>
<td><input type='radio' name='seldonor'/>bggest</td>
</tr>
</table>
*/
?>
<table width='60%'>
<tr>
<td><a href='?p=showall'>Все</a></td>



</tr>
</table>


<table border='1' style='table-layout: fixed;  width:100%'>
<tr>
<td>Категория </td><td width='60%'>Url Донора </td>
<td><a href='?disableall'>Отключить все</a></td>
<td><a href='?enableall'>Включить все</a></td>
 <!--<td> Парсить категорию</td>-->

</tr>
<?php
$andquer="";
if($_GET['p']=='show1')
{
	$andquer="AND p.donorurl LIKE '%mbutik.net%' ";
}
if($_GET['p']=='show2')
{
	$andquer="AND p.donorurl LIKE '%marakesh.net.ua%'  ";
}

$query="SELECT *,p.id as pid,cd.category_id as categid FROM `parscateg` as p 
LEFT JOIN `".DB_PREFIX."category_description` as cd ON p.category_id=cd.category_id
WHERE cd.language_id='1' $andquer ORDER by p.`id`";
//WHERE cd.language_id='1' $andquer ORDER by cd.`name`";
//print "<div>".$query."</div>";
$result = mysql_query($query,$link);

$nm=mysql_num_rows($result);

while ($row = mysql_fetch_array($result)) {
	$q1="SELECT `cd` . * , `c`.`parent_id`
	FROM `".DB_PREFIX."category` AS `c`
	LEFT JOIN `".DB_PREFIX."category_description` AS `cd` ON `c`.`parent_id` = `cd`.`category_id`
	WHERE `c`.`category_id` = '".$row['categid']."'";
	
	$res1 = mysql_query($q1,$link);
	$rw1 = mysql_fetch_array($res1);
	
	if($row['parse']==1){$tlink='Да';}else{$tlink='Нет';}
	print "<tr><td>".$row['categid']." - ".$rw1['name']." - ".$row['name']."</td><td style='word-wrap:break-word;'>".$row['donorurl']."</td>";
	if($row['parse']==1)
	{
		print "<td><a href='?disable=".$row['pid']."' style='color:green;'>Отключить</a></td>";
	}
	else
	{
		print "<td><a href='?enable=".$row['pid']."'  style='color:red;'>Включить</a></td>";
	}
	print "<td><a href='?del=".$row['pid']."'>Удалить</a></td>
	</tr>";
	//<td><a href='?id=".$row['pid']."&swpars=".$row['parse']."'>".$tlink."</a></td>
}
?>
</table>


<form method='post'>
<table>
<tr><td>Наша Категория </td><td><?=selectcateg($link)?></td></tr>
<tr><td>URL Категории донора</td><td><input type='text' size='51' name='donorurl' value=''/></td></tr>

</table>

<input type='submit' name='addcategsoot' value='Добавить соотвтетствие'/>
</form>