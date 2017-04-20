<?
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);


define ('BASEPATH', true);
require ('../../application/configs/database.cfg.php');

$link = mysql_connect($conf['db_hostname'], $conf['db_username'], $conf['db_password']);
if (!$link) 
{
    die('Could not connect: ' . mysql_error());
}
$db_selected = mysql_select_db($conf['db_database'], $link);
if (!$db_selected) 
{
    die ('Can\'t use foo : ' . mysql_error());
}
mysql_query ("SET NAMES UTF8");

if ($_FILES['userfile'])
{
	$uploaddir = 'import/';
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $_FILES['userfile']['name'])) 
	{
		//echo 22;
		//$file = file ('import/'.$_FILES['userfile']['name']);
		$file =  simplexml_load_file($uploaddir . $_FILES['userfile']['name']);
		//$file = simplexml_load_file($uploaddir . 'products_2014_06_03.xml');
		if ($file)
		{
			//echo 'yes';
			foreach ($file->product as $item) 
			{
				$id = (string)$item->id_folder;
				$id = preg_replace("([^0-9-])", "", $id);
				//echo $id.'<br>';
				$data[$id][] = array ('name' => (string)$item->name, 'price' => (float)$item->price1, 'art' => (string)$item->id, 'size' => (string)$item->size, 'count' => ($item->unit_name.' '.$item->pcs.'шт.'));	
				//echo "<h2>". $item->name. "</h2>
				//echo "<p>". $item->description. "</p>";
			}
			print_r ($data);
			$res = mysql_query ("SELECT * FROM techcms_catalog_items WHERE marking != ''");
			while ($theme = mysql_fetch_assoc ($res))
			{
				//print_r ($theme);
				//print_r ($data[$theme['marking']]);
				if ($data[$theme['marking']])
				{
					unset ($array);
					unset ($array2);
					foreach ($data[$theme['marking']] as $item2)
					{
						//print_r ($item2);
						//echo $item2['name'].'<br>';
						$array[] = array ('price' => (float)$item2['price'], 'marking' => $item2['art'], 'name' => $item2['name'], 'count' => $item2['count'], 'size' => $item2['size']);
						$array2[] = array ('Цена' => (float)$item2['price'], 'Артикул' => $item2['art'], 'Наименование товара' => $item2['name'], 'Единица измерения' => $item2['count'], 'Размер' => $item2['size']);
					}
					if ($array)
					{
						mysql_query ("UPDATE techcms_catalog_items SET multiprice = '".serialize ($array)."', multiprice_sys = '".serialize ($array2)."' WHERE id = '".$theme['id']."'");
					}
					//print_r ($array);
					$count++;
				}
			}
			echo 'Загружено: '.$count;
		}
		else
		{
			echo 'no';
		}
	} 
	else 
	{
		print "Не загружено!";
	}
}
else 
{ ?>

<form enctype="multipart/form-data" action="" method="post">
Файл для импорта (в формате XML): <input name="userfile" type="file"><br>
<input type="submit" value="Импорт">
</form>

<? } ?>
