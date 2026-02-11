<?php
       class Sdp{
        public static function run(){
        $front=new Core_Controllers_Front();
        echo "<pre>";
        print_r($front);
        // //   $request=new Core_Model_Request();
        // //   $front->run($request);
       $admin=new Core_Controllers_Admin();
       echo "<pre>";
       print_r($admin);
            
        }
    }


?>