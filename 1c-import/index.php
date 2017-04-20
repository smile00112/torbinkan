<?
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);


define ('BASEPATH', true);
require ('../config.php');

$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if (!$link) 
{
    die('Could not connect: ' . mysql_error());
}
$db_selected = mysql_select_db(DB_DATABASE, $link);
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

                /*$id = (string)$item->id_folder;
     			$id = preg_replace("([^0-9-])", "", $id);
                $product_info = mysql_fetch_assoc(mysql_query('SELECT * FROM `oc_product` WHERE `sku` = \''.$id.'\''));
				//echo $id.'<br>';
                if ($product_info['product_id']) {
				$data[$product_info['product_id']][] = array ('name' => (string)$item->name, 'price' => (float)$item->price1, 'sku' => (string)$item->id, 'size' => (string)$item->size, 'count' => ($item->unit_name.' '.$item->pcs.'шт.'));
                echo $id.'<br>';
                }  */


				$id = (string)$item->id;
                $folder = (string)$item->id_folder;
				$id = preg_replace("([^0-9-])", "", $id);
                //$product_info = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product` WHERE sku = {$id}"));
				//echo $id.'<br>';
				$data[$id][] = array ('name' => (string)$item->name, 'price' => (float)$item->price1, 'sku' => (string)$item->id, 'size' => (string)$item->size, 'weight' => (string)$item->weight, 'pcs' => (string)$item->pcs.' шт.', 'unit_name' => (string)$item->unit_name, 'count' => ($item->unit_name.' '.$item->pcs.'шт.'));
                $data2[$folder][] = array ('name' => (string)$item->name, 'price' => (float)$item->price1, 'sku' => (string)$item->id, 'size' => (string)$item->size, 'weight' => (string)$item->weight, 'pcs' => (string)$item->pcs.' шт.', 'unit_name' => (string)$item->unit_name, 'count' => ($item->unit_name.' '.$item->pcs.'шт.'));

                //echo "<h2>". $item->name. "</h2>
				//echo "<p>". $item->description. "</p>";
			}


            /* ОБНОВЛЕНИЕ СТАРЫХ */
           $count2 = 0;
            //print_r($data);
//			echo 'sdsdklfjlsdjlfksjdklfjsldjflk';


		   	$res = mysql_query ("SELECT * FROM oc_product_option_value WHERE model_option != ''");
			while ($theme = mysql_fetch_assoc ($res)) {
				//print_r ($theme);
	//			print_r ($data[trim($theme['marking'])]);
				
				//print_r ($data['9-24']);
			   if ($data[$theme['model_option']]) {

              /*$product_id2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product_option_value` WHERE model_option = '".$theme['model_option']."' LIMIT 1"));
              $product_info2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product` WHERE product_id = '".$product_id2['product_id']."'"));

                   $price_updater = 5000000;
                   foreach ($data[$theme['model_option']] as $item5)  {
                   if ($price_updater > $item5['price']) {
                   $price_updater = $item5['price'];
                   }
                   }

                   $update_product_price = mysql_query("UPDATE`oc_product` SET `price` = '".$price_updater."' WHERE `product_id` = '".$product_info2['product_id']."' ");
                  //$theme2['price'] = $price_update;
                                                             */

					unset ($array);
					unset ($array2);
					foreach ($data[$theme['model_option']] as $item2)
					{

                        mysql_query ("DELETE FROM oc_product_option_value WHERE `model_option` = ".$item2['sku']);
						//print_r ($item2);
						//echo $item2['name'].'<br>';
					   /*if ($item2['price'] > 0)
						{
							//$array[] = array ('price' => (float)$item2['price'], 'marking' => $item2['art'], 'name' => $item2['name'], 'count' => $item2['count'], 'size' => $item2['size']);
							//$array2[] = array ('Цена' => (float)$item2['price'], 'Артикул' => $item2['art'], 'Наименование товара' => $item2['name'], 'Единица измерения' => $item2['count'], 'Размер' => $item2['size']);

                            $product_id = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product_option_value` WHERE model_option = '".$theme['model_option']."' LIMIT 1"));
                            $product_info = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product` WHERE product_id = ".$product_id['product_id'].""));

                            if ($product_info['price'] > $item2['price']) {

                           $price_update = $product_info['price']-$item2['price'];
                            $price_prefix = '-';
                            } else {
                            $price_update = $item2['price']-$product_info['price'];
                            $price_prefix = '+';
                            }

                            echo $item2['price'].'|'.$product_info['price'].'|'.$product_info['product_id'].'';

                            mysql_query ("UPDATE oc_product_option_value SET
                            amount_option = '".$item2['pcs']."',
                            quantity = 100,
                            price = '".$price_update."',
                            price_prefix = '".$price_prefix."',
                            unit_option = '".$item2['unit_name']."',
                            weight = '".$item2['weight']."'
                            WHERE model_option = '".$theme['model_option']."'");
      


                        echo $item2['name'].' товар обновлен.<br>';
                        } */
					}
					$count2++;
				}
			}
		   //	echo '<b>Всего обновлено: '.$count2.'</b><br>';



            # Заполняем отсутствующие опции товаров:
		   	$res1 = mysql_query ("SELECT * FROM oc_product WHERE sku != ''");
			while ($theme2 = mysql_fetch_assoc ($res1)) {


				if ($data2[$theme2['sku']]) {

                   $price_updater = 50000000;
                   foreach ($data2[$theme2['sku']] as $item4)  {
                   if ($price_updater > $item4['price']) {
                   $price_updater = $item4['price'];
                   }
                   }

                   $update_product_price = mysql_query("UPDATE`oc_product` SET `price` = '".$price_updater."' WHERE `product_id` = '".$theme2['product_id']."' ");
                   $theme2['price'] = $price_updater;

					unset ($array3);
					unset ($array4);
					foreach ($data2[$theme2['sku']] as $item3)  {

                        $check_option = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) FROM `oc_product_option_value` WHERE product_id = '".$theme2['product_id']."' and model_option = '".$item3['sku']."' "));
                       //$check_option['COUNT(*)'].'<br>';
						if ($check_option['COUNT(*)'] == 0) {

                            if ($theme2['price'] > $item3['price']) {
                            $price_update = $theme2['price']-$item3['price'];
                            $price_prefix = '-';
                            } else {
                            $price_update = $item3['price']-$theme2['price'];
                            $price_prefix = '+';
                            }

                            $last_option = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_option_value_description` ORDER by `option_value_id` DESC LIMIT 1 "));
                            $new_id = $last_option['option_value_id']+1;

                            $last_option_id = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product_option` WHERE product_id = '".$theme2['product_id']."' LIMIT 1 "));
                            if ($last_option_id['product_option_id'] == '') {
                            mysql_query ("INSERT INTO `oc_product_option` (
                            `product_id`,
                            `option_id`
                            ) VALUES (
                            '".mysql_real_escape_string($theme2['product_id'])."',
                            '".mysql_real_escape_string('14')."'
                            )
                            ");
                         }
                            $last_option_id = mysql_fetch_assoc(mysql_query("SELECT * FROM `oc_product_option` WHERE product_id = '".$theme2['product_id']."' LIMIT 1 "));


                            mysql_query ("INSERT INTO `oc_option_value_description` (
                            `option_value_id`,
                            `language_id`,
                            `option_id`,
                            `name`) VALUES (
                            '".mysql_real_escape_string($new_id)."',
                            '".mysql_real_escape_string('1')."',
                            '".mysql_real_escape_string('14')."',
                            '".mysql_real_escape_string($item3['size'])."'
                            )
                            ");

                            mysql_query ("INSERT INTO `oc_option_value_description` (
                            `option_value_id`,
                            `language_id`,
                            `option_id`,
                            `name`) VALUES (
                            '".mysql_real_escape_string($new_id)."',
                            '".mysql_real_escape_string('2')."',
                            '".mysql_real_escape_string('14')."',
                            '".mysql_real_escape_string($item3['size'])."'
                            )
                            ");

                             mysql_query ("INSERT INTO `oc_option_value` (
                            `option_value_id`,
                            `image`,
                            `option_id`,
                            `sort_order`) VALUES (
                            '".mysql_real_escape_string($new_id)."',
                            '".mysql_real_escape_string('no_image.jpg')."',
                            '".mysql_real_escape_string('14')."',
                            '".mysql_real_escape_string('0')."'
                            )
                            ");


                            mysql_query ("INSERT INTO `oc_product_option_value` (
                            `amount_option`,
                            `option_value_id`,
                            `quantity`,
                            `product_id`,
                            `product_option_id`,
                            `price`,
                            `option_id`,
                            `price_prefix`,
                            `model_option`,
                            `unit_option`,
                            `weight`) VALUES (
                            '".mysql_real_escape_string($item3['pcs'])."',
                            '".mysql_real_escape_string($new_id)."',
                            '100',
                            '".mysql_real_escape_string($theme2['product_id'])."',
                            '".mysql_real_escape_string($last_option_id['product_option_id'])."',
                            '".mysql_real_escape_string($price_update)."',
                            '".mysql_real_escape_string('14')."',
                            '".mysql_real_escape_string($price_prefix)."',
                            '".mysql_real_escape_string($item3['sku'])."',
                            '".mysql_real_escape_string($item3['unit_name'])."',
                            '".mysql_real_escape_string($item3['weight'])."'
                            )
                            ");

                            /*mysql_query ("UPDATE oc_option_value_description SET
                            amount_option = '".$item2['pcs']."',
                            quantity = '".$item2['size']."',
                            price = '".$item2['price']."',
                            unit_option = '".$item2['unit_name']."',
                            weight = '".$item2['weight']."'
                            WHERE option_id = 14 and option_value_id = '".$theme['model_option']."'"); */


                        $count++;
                        }
					}


				}

            }


		}
		else
		{
			echo 'no';
		}


                 echo '<b>Всего добавлено: '.$count.'</b>';

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
