<?php
    namespace System;
    class Controller
    {
        public function View($viewName = "")
        {
            if($viewName == "")
            {
                $class = get_class($this);
                $page = $class;
            }
            else
                $page = $viewName;
            
            
        }

        public function View()
        {
        }
    }
?>