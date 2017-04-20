<?php
class AddistClass
{
    protected $registry;
    
    public function __construct($registry)
    {
        $this->registry = $registry;
    }
    
	public function __get($key)
    {
        $object = $this->registry->get('addist_'.$key);
        if (!empty($object))
        {
            return $object;
        }
        else
        {
            return $this->registry->get($key);
        }
	}
    
	public function __set($key, $value)
    {
		$this->registry->set($key, $value);
	}
}
?>