<?php

class TclConfig
{

    private static $_configArr;
    private static $_config;
    private $_pathDelimiter = ".";

    public function __construct()
    {
        $this->_configArr = array(
             "mysql" =>
             array(
                  "host"     => "172.16.174.159",
                  "dbname"   => "kubectl",
                  "username" => "kubeuser",
                  "password" => "Admin@123"
             )
        );
    }

    public static function config($key)
    {
        self::$_config = new self();
        $key = trim($key);
        return self::$_config->getConfig($key);
    }

    public function getConfig($key)
    {
        $path = explode($this->_pathDelimiter, $key);

        foreach ($path as $part)
        {
            if (isset($this->_configArr[$part]))
            {
                $this->_configArr = $this->_configArr[$part];
            }
            else
            {
                return null;
            }
        }
        return $this->_configArr;
    }

}

?>
