<?php

interface dao{

    public function Insert($entitie);
}
abstract class grandPere{
    abstract public function calcule();
}
final class Pere{

}
class FirstClass extends grandPere implements dao{

    public $name;
    public $address = "non";
    public $age;
    private $king;
    public const SEX = "";
    public const MINCHAR = 3;

    public function __construct()
    {
        
    }
    public function __destruct()
    {
        
    }
    // cal method is called when invoking function not Accessible or NotFound
    public function __call($name, $arguments)
    {
        echo "this method " . $name . " Is NotAccessible Or Not exist";
    }
    // __get method is called when try to get value of property not Accessible or NotFound
    public function __get($name)
    {
        
    }
    // __set method is called when try to set value to property not Accessible or NotFound
    public function __set($name, $value)
    {
        
    }


    final public function afficher(){
        echo "hello " . $this->name;
    }
    public function allowed(){
        if(strlen($this->name) > self::MINCHAR){
            echo "good";
        }
    }
    public function setKing($king){
        $this->king=$king;
    }

    public function calcule()
    {
        
    }
    public function Insert($entitie)
    {
        
    }
}

$person = new FirstClass();
$person->name='meed';
$person->age= 12;
$person->setKing("ragnar");

//echo $person::MINCHAR;
//echo FirstClass::MINCHAR;

//$person->afficher();

echo '<pre>';
print_r($person);
//var_dump($person);
echo '</pre>';

?>