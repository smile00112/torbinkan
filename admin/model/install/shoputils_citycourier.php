<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

class ModelInstallShoputilsCitycourier extends Model {

    private $_version = '1.0';
    private $_tablename_description = 'shoputils_citycourier_description';

    public function install(){
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('shoputils_citycourier');

        if (!array_key_exists('version', $settings)){
            $query = $this->db->query("show tables like '".DB_PREFIX . $this->_tablename_description."'");
            if (!$query->rows){
                $sql = "CREATE TABLE `". DB_PREFIX . $this->_tablename_description . "` (
                    `language_id` int( 11 ) NOT NULL,
                    `name` text NOT NULL,
                    `description` text NOT NULL
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT = 'Shoputils citycourier shipping '";
                $this->db->query($sql);

                $sql = "ALTER TABLE `".DB_PREFIX . $this->_tablename_description."` ADD UNIQUE `IDX_".DB_PREFIX . $this->_tablename_description."` ( `language_id` )";
                $this->db->query($sql);

            }
            $settings['version'] = $this->_version;
            $this->model_setting_setting->editSetting('shoputils_citycourier', $settings);
        }
    }
}
/* uninstall script
DROP TABLE IF EXISTS oc_shoputils_shoputils_citycourier_description;
DELETE FROM oc_setting WHERE `group` = 'shoputils_citycourier';
*/
?>