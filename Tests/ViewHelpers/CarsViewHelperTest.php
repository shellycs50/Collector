<?php
require_once 'src/ViewHelpers/CarsViewHelper.php';
require_once 'src/Entities/Car.php';
use PHPUnit\Framework\TestCase;

class CarsViewHelperTest extends TestCase
{
    public function test_displayAllCars_success(): void
    {
        $testObjs = [];
        $testCar = new Car(1, 'Testcar', 5, 'Testmake', 100, 'Testbodytype', 2000);
        $result = CarsViewHelper::displayAllCars([$testCar]);
        
        $this->assertEquals("<div class='car-grid'><div class='car-wrapper'><p class='car-title'>Testmake Testcar</p><p class='car-year'>2000</p><img src='' alt='car image'/><p>Type: Testbodytype</p><a href='edit.php?edit_id=1'>Edit</a><a href='index.php?delete=1'>Delete</a></div></div>", $result);
    }

    public function test_displayAllCars_failure(): void 
    {
        $result = CarsViewHelper::displayAllCars([]);
        
        $this->assertEquals("<div class='all-cars-error-message'><p>We couldn't find any cars! Try adding one <a href='#'>here</p></div>", $result);
    }
}