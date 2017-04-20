<?php
class ModelAccountCashplusplus extends Model { 

public function getOrderStatus($order_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
public function getOrderAdress($order_id) {
  $query = $this->db->query("SELECT `payment_address_1`, `payment_address_2`, `shipping_address_1`, `shipping_address_2`, `firstname`, `lastname`, `payment_firstname`, `payment_lastname`, `shipping_firstname`, `shipping_lastname`, `payment_city`, `shipping_city`, `date_modified`, `date_added`, `invoice_prefix`, `invoice_no`, `currency_code`, `currency_value`, `total`, `payment_company`, `shipping_company`, `order_id`, `payment_postcode`, `payment_zone`, `shipping_postcode`, `shipping_zone`  FROM `" . DB_PREFIX . "order` WHERE `order_id` = '". $order_id . "'; ");
  return $query->row;
}
public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}
public function numbers($num) {
		function morph($n, $f1, $f2, $f5) {
		    $n = abs(intval($n)) % 100;
		    if ($n>10 && $n<20) return $f5;
		    $n = $n % 10;
		    if ($n>1 && $n<5) return $f2;
		    if ($n==1) return $f1;
		    return $f5;
		}

    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array(
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) {
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1;
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
           
            $out[] = $hundred[$i1]; 
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; 
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; 
            
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } 
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]);
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}
}
?>