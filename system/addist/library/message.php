<?php
class AddistMessage extends Model
{
    public function add($type, $message, $replace = false)
    {
        if ($replace)
        {
            unset($this->session->data['messages'][$type]);
        }
        $this->session->data['messages'][$type][] = $message;
        $this->session->data['messages'][$type] = array_unique($this->session->data['messages'][$type]);
    }
    
    public function get($type,$slice = true)
    {
        $result = array();
        if (isset($this->session->data['messages'][$type]))
        {
            $result = $this->session->data['messages'][$type];
            unset($this->session->data['messages'][$type]);
        }
        if ($slice)
        {
            $result = array_slice($result,0,5);
        }
        return $result;
    }
}
?>