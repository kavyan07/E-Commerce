<?php
class Core_Controllers_Front_Action
{
      public function getRequest(){
         $request = Sdp::getModel("core/request");
         return $request;

      }
}

?>