<?php
echo "<pre>";
class A
{
    protected $n = 10;
    public function i($n)
    {
        $this->n = $n;

    }

    public function g()
    {
        return $this->n;
    }
}
class B
{
    public $a = null;
    public function a()
    {
        if ($this->a == null) {
            $this->a = new A;
        }
        print_r($this->a);
        return $this->a;

    }

}

$b = new B;
$b->a()->i(20);
echo $b->a()->g();


?>



<!-- <?php

class Engine
{
    public function start()
    {
        echo "Engine Started <br>";
    }
}

class Car
{
    private $engine = null;

    public function startCar()
    {
        echo "Starting Car... <br>";
        
        if ($this->engine === null) {
            echo "Engine object created <br>";
            $this->engine = new Engine();
        }

        $this->engine->start();
    }
}

// Execution
$car = new Car();   // Engine NOT created here

$car->startCar();   // Engine created here

?> -->
