```php
<?php

/* ======================================================
1️⃣ BANK ACCOUNT EXAMPLE
====================================================== */

/* ❌ Incorrect Code: Private members accessed outside */
class BankAccount_Incorrect
{
    private $balance = 1000;

    private function withdraw($amount)
    {
        $this->balance -= $amount;
    }
}

$acc1 = new BankAccount_Incorrect();
// echo $acc1->balance;      // ❌ ERROR: Cannot access private property BankAccount_Incorrect::$balance
// $acc1->withdraw(100);     // ❌ ERROR: Call to private method BankAccount_Incorrect::withdraw()



/* ✅ Correct Code */
class BankAccount_Correct
{
    private $balance = 1000;

    public function getBalance()
    {
        return $this->balance;
    }

    public function withdraw($amount)
    {
        if ($amount <= $this->balance) {
            $this->balance -= $amount;
        }
    }
}

$acc2 = new BankAccount_Correct();
echo $acc2->getBalance();
$acc2->withdraw(100);



/* ======================================================
2️⃣ CHILD CLASS ACCESSING PRIVATE PARENT MEMBERS
====================================================== */

/* ❌ Incorrect Code */
class Employee_Incorrect
{
    private $salary = 50000;
}

class Manager_Incorrect extends Employee_Incorrect
{
    public function displaySalary()
    {
        // echo $this->salary;
        // ❌ ERROR: Cannot access private property Employee_Incorrect::$salary
    }
}



/* ✅ Correct Code */
class Employee_Correct
{
    protected $salary = 50000;
}

class Manager_Correct extends Employee_Correct
{
    public function displaySalary()
    {
        echo $this->salary;
    }
}

$m = new Manager_Correct();
$m->displaySalary();



/* ======================================================
3️⃣ FUNCTION VISIBILITY
====================================================== */

/* ❌ Incorrect Code */
class Car_Incorrect
{
    private function startEngine() {}
    protected function checkFuel() {}
}

$c1 = new Car_Incorrect();
// $c1->startEngine();   // ❌ ERROR: Call to private method Car_Incorrect::startEngine()
// $c1->checkFuel();     // ❌ ERROR: Call to protected method Car_Incorrect::checkFuel()



/* ✅ Correct Code */
class Car_Correct
{
    public function drive()
    {
        $this->startEngine();
        return "Driving";
    }

    private function startEngine()
    {
        echo "Engine started ";
    }

    protected function checkFuel() {}
}

$c2 = new Car_Correct();
echo $c2->drive();



/* ======================================================
4️⃣ VARIABLE VISIBILITY
====================================================== */

/* ❌ Incorrect Code */
class Student_Incorrect
{
    private $grades = [85, 90, 78];
    protected $name = "John";
}

class Teacher_Incorrect extends Student_Incorrect
{
    public function avg()
    {
        // return array_sum($this->grades);
        // ❌ ERROR: Cannot access private property Student_Incorrect::$grades
    }
}

$s1 = new Student_Incorrect();
// echo $s1->name;
// ❌ ERROR: Cannot access protected property Student_Incorrect::$name



/* ✅ Correct Code */
class Student_Correct
{
    private $grades = [85, 90, 78];
    protected $name = "John";

    public function getAverage()
    {
        return array_sum($this->grades) / count($this->grades);
    }

    public function getName()
    {
        return $this->name;
    }
}

$s2 = new Student_Correct();
echo $s2->getName();
echo $s2->getAverage();

/* ======================================================
6️⃣ REDUCING VISIBILITY IN CHILD
====================================================== */

/* ❌ Incorrect Code */
class Parent_Incorrect
{
    public function show() {}
}

class Child_Incorrect extends Parent_Incorrect
{
    // private function show() {}
    // ❌ ERROR: Access level must be public (as in parent)
}



/* ✅ Correct Code */
class Parent_Correct
{
    protected function show()
    {
        echo "Parent show ";
    }
}

class Child_Correct extends Parent_Correct
{
    public function show()
    {
        echo "Child show ";
    }
}

$child = new Child_Correct();
$child->show();



/* ======================================================
7️⃣ ABSTRACT VISIBILITY MISMATCH
====================================================== */

/* ❌ Incorrect Code */
// abstract class Abstract_Incorrect
// {
//     abstract protected function demo();
// }

// class AbstractChild_Incorrect extends Abstract_Incorrect
// {
//     // private function demo() {}
//     // ❌ ERROR: Access level must be protected or public
// }



/* ✅ Correct Code */
abstract class Abstract_Correct
{
    abstract protected function demo();
}

class AbstractChild_Correct extends Abstract_Correct
{
    public function demo()
    {
        echo "Abstract fixed ";
    }
}

$a = new AbstractChild_Correct();
$a->demo();



/* ======================================================
8️⃣ UNDEFINED METHOD
====================================================== */

/* ❌ Incorrect Code */
class Shop_Incorrect
{
    public function addItem() {}
}

$sh1 = new Shop_Incorrect();
// $sh1->displayTotal();
// ❌ ERROR: Call to undefined method Shop_Incorrect::displayTotal()



/* ✅ Correct Code */
class Shop_Correct
{
    public $items = [];

    public function addItem($i)
    {
        $this->items[] = $i;
    }

    public function displayTotal()
    {
        return count($this->items);
    }
}

$sh2 = new Shop_Correct();
$sh2->addItem("Book");
echo $sh2->displayTotal();


?>
```
