<?php
class seo_generator extends AddistModel
{
    public $info = array(
        'hash'              =>  '500b10fef0885805efd8e02213f33715',
        'version'           =>  '0.1.1',
        'link'              =>  'http://addist.ru/index.php?route=product/product&product_id=19',
        'trial'             =>  '0',
        'release'           =>  'stable',
    );
    
    public $fields = array(
        'product_template'       =>  '[name]',
        'category_template'      =>  '[name]',
        'manufacturer_template'  =>  '[name]',
        'information_template'   =>  '[title]',
        'news_template'          =>  '[name]',
    );
    
    public $mods = array('ocmod'=>array('seo_generator.ocmod.xml'),'vqmod'=>array('seo_generator.xml'),);
    
    public $files = array('system/addist/extension/seo_generator/vqmod/seo_generator.xml', 'system/addist/extension/seo_generator/install.php', 'system/addist/extension/seo_generator/ocmod/seo_generator.ocmod.xml', 'admin/model/tool/seo_generator.php', 'admin/language/english/module/seo_generator.php', 'admin/language/russian/module/seo_generator.php', 'admin/view/template/module/seo_generator.tpl', 'admin/controller/module/seo_generator.php');
    
    public function install()
    {
        
    }
}
?>