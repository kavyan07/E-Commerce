<?php
    class Core_Controllers_Front{
      protected $_request;
      public function __construct(){
        $this->request=new Core_Controllers_admin();
      }
    }


?>