<?php
class addist_installer extends AddistModel
{
    public $info = array(
        'hash'              =>  'ace84896960d67fe987d471fcda564bb',
        'version'           =>  '0.1.1',
        'trial'             =>  '0',
    );
    
    public $fields = array(
        'customer_id'           =>  0,
        'token'                 =>  '',
        'customer'              =>  '',
        'email'                 =>  '',
        'last_refresh'          =>  '',
        'debug'                 =>  false,
    );
    
    public $mods = array('ocmod'=>array('seo_generator.ocmod.xml'),'vqmod'=>array('seo_generator.xml'),);
    
    public $files = array('system/addist/extension/seo_generator/vqmod/seo_generator.xml', 'system/addist/extension/seo_generator/install.php', 'system/addist/extension/seo_generator/ocmod/seo_generator.ocmod.xml', 'admin/model/tool/seo_generator.php', 'admin/language/english/module/seo_generator.php', 'admin/language/russian/module/seo_generator.php', 'admin/view/template/module/seo_generator.tpl', 'admin/controller/module/seo_generator.php');
    
    public function __construct($registry)
    {
        parent::__construct($registry);
    }
}
?>