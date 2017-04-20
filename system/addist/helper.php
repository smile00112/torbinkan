<?php
//error page
function addist_error($errors)
{
    global $registry, $response, $document, $language, $url, $session, $loader;
    
    $data['errors'] = $errors;
    
    if (OC_VERSION == '2.0.x')
    {
        return $loader->controller('addist/error',$data);
    }
    else
    {
        $action = new Action('addist/error', $data);
        $file = class_exists('VQMod') ? VQMod::modCheck($action->getFile()) : $action->getFile();
        if (file_exists($file))
        {
            $class = $action->getClass();
            if (!class_exists($class))
            {
                require_once($file);
            }
            $controller = new $class($registry);                                
			if (is_callable(array($controller, $action->getMethod()), false))
            {
				$output = call_user_func(array($controller, $action->getMethod()), $action->getArgs());
                if (!empty($controller->output))
                {
                    $output = $controller->output;
                }
			}
            else
            {
				$output = $controller->getChild('addist/error', $data);
			}
            $response->setOutput($output);
        }
    }
    $response->output();
}

//check requirements function
function check_requirements($force = true)
{
    if (ob_get_contents())
    {
        ob_clean();
    }
    global $language;
    $errors = array();
    
    //checking php version
    if (version_compare('5.2',PHP_VERSION) > 0)
    {
        $errors['php_version'] = $language->get('addist_error_php_version');
    }
    
    //checking mbstring
    if (!in_array('mbstring', get_loaded_extensions()))
    {
        $errors['mbstring'] = $language->get('addist_error_mbstring');
    }
    
    //checking curl
    if (!in_array('curl', get_loaded_extensions()) && !function_exists('curl_version') && !extension_loaded('curl'))
    {
        $errors['curl'] = $language->get('addist_error_curl');
    }
    
    //checking mcrypt
    if (!in_array('mcrypt', get_loaded_extensions()))
    {
        $errors['mcrypt'] = $language->get('addist_error_mcrypt');
    }
    
    //checking zip
    if (!in_array('zip', get_loaded_extensions()))
    {
        $errors['zip'] = $language->get('addist_error_zip');
    }
    
    //checking ioncube
    $extensions = get_loaded_extensions();
    if (in_array('ionCube Loader', $extensions))
    {
        if (version_compare(ioncube_loader_version(),IONCUBE_VERSION) < 0)
        {
            $errors['ioncube'] = sprintf($language->get('addist_error_ioncube_version'),IONCUBE_VERSION);
        }
    }
    else
    {
        $errors['ioncube'] = $language->get('addist_error_ioncube');
    }
    
    //checking vqmod
    if (OC_VERSION != '2.0.x')
    {
        if (!class_exists('VQMod'))
        {
            $errors['vqmod'] = $language->get('addist_error_vqmod');
        }
        else
        {
            $vqm = new ReflectionClass('VQMod');
            if ($vqm->isAbstract())
            {
                if (version_compare(VQMod::$_vqversion,VQMOD_VERSION) < 0)
                {
                    $errors['vqmod'] = sprintf($language->get('addist_error_vqmod_version'),VQMOD_VERSION);
                }
            }
            else
            {
                $rp = new ReflectionProperty(new VQMod(),'_vqversion');
                if (!$rp->isStatic() || $rp->isPrivate())
                {
                    $errors['vqmod'] = sprintf($language->get('addist_error_vqmod_version'),VQMOD_VERSION);
                }
            }
        }
    }
    
    if ($errors)
    {
        foreach($errors as $error)
        {
            addLog($error,'addist');
        }
        if ($force)
        {
            addist_error($errors);
        }
        else
        {
            return $errors;
        }
    }
    else
    {
        return true;
    }
}

function check_ioncube()
{
    $extensions = get_loaded_extensions();
    if (in_array('ionCube Loader', $extensions))
    {
        if (version_compare(ioncube_loader_version(),IONCUBE_VERSION) < 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false;
    }
}

//adutoload
function addist_autoload($class)
{
    $class = strtolower($class);
    if (substr($class,0,6) == 'addist')
    {
        check_requirements(true);
        $filename = DIR_SYSTEM . 'addist/engine/'.substr($class,6,strlen($class)).'.php';
        if (file_exists($filename))
        {
            require_once($filename);
        }
        
        $filename = DIR_SYSTEM . 'addist/library/'.substr($class,6,strlen($class)).'.php';
        if (file_exists($filename))
        {
            require_once($filename);
        }
    }
}
    
function addMessage($type, $message)
{
    global $session;
    $session->data['messages'][$type][] = $message;
    $session->data['messages'][$type] = array_unique($session->data['messages'][$type]);
}

function getStores()
{
    global $db;
    
    $stores = array();
    
    //main store
    $query = $db->query("SELECT `value` FROM `".DB_PREFIX."setting` WHERE `store_id` = '0' AND `key` = 'config_name'");
    $stores[] = array('store_id'=>'0','name'=>$query->row['value'],'url'=>IS_ADMIN ? HTTP_CATALOG : HTTP_SERVER,'ssl'=>IS_ADMIN && defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTPS_SERVER);
    
    //other stores
    $query = $db->query("SELECT * FROM `".DB_PREFIX."store`");
    foreach($query->rows as $row)
    {
        $stores[] = $row;
    }
    
    return $stores;
}

function requireOnce($file)
{
    require_once($file);
}

function load_lib($library)
{
    require_once(DIR_SYSTEM.'library/'.$library.'.php');
}

function addLog($message, $filename = false)
{
    if (!$filename)
    {
        global $config;
        $filename = $config->get('config_error_filename');
    }
    else
    {
        $filename .= '.log';
    }
    
    if (is_file(DIR_LOGS . $filename) && is_writable(DIR_LOGS . $filename))
    {
        @chmod(DIR_LOGS . $filename,0777);
    }
    
    $handle = fopen(DIR_LOGS . $filename, 'a');
    fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    fclose($handle);
    
    if (is_file(DIR_LOGS . $filename) && is_writable(DIR_LOGS . $filename))
    {
        @chmod(DIR_LOGS . $filename,0777);
    }
}

function readLog($filename = 'error')
{
    if (is_file(DIR_LOGS.$filename.'.log'))
    {
        return file_get_contents(DIR_LOGS.$filename.'.log');
    }
}
?>