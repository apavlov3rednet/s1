<?php

$int = 1.000;
$string = 'string';

//integer, string, array, object, list, float

$ar = [];
$float = 0.4;

$name = 'Петя';
$secondName = 'Петров';

function FullName(string $name, string &$secondName) : void //выполняет мутацию
{
    $name . ' ' . $secondName+'-Водкин';
}

function Second(int $arg, int $arg2, int $arg3 = 5) : bool // bool, array, int, string, mixed, void
{
    return false;
}

class Animal 
{
    public $vid;
    public $gender;

    function __construct(array $ar) {
        $this->vid = $ar[0];
        $this->gender = $ar[1];
    }

}

final class Bird extends Animal {
    public $longFly = 0;

    function __construct(array $ar) {
        // $this->vid = $ar[0];
        // $this->gender = $ar[1];

        parent::__construct($ar); // super::construct()
        $this->longFly = $ar[2];
    }

    public function __toString() : string {
        return $this->gender;
    }
}

$animal = new Animal(['Конь', 'Мужской']);

$bird = new Bird(['Птица', 'Женский', 10000]);
?>


