<?php
    namespace System\Configuration;
    class ConfigurationManager
    {
        public static function SitePath() : string
        {
            return $config['General']['SitePath'];
        }
    }
?>