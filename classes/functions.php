<?php
    class Functions
    {
        public static function isValidFolderName($string)
        {
            return (strspn($string, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789_-") == strlen($string));
        }
    }
?>