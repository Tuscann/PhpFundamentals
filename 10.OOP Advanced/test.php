<?php
declare(strict_types = 1);
namespace az;

interface VehicleInterface
{
    public function Drive(float $distance);
    public function Refuel(float $fuel);
}

abstract class Vehicle implements VehicleInterface
{
    private $type;
    private $fuelQuantity;
    private $fuelPerKm;
    private $maxDistance;

    public function __construct(string $type, float $fuelQuantity, float $fuelPerKm)
    {
        $this->setType($type);
        $this->setFuelQuantity($fuelQuantity);
        $this->setFuelPerKm($fuelPerKm);
        $this->setMaxDistance();
    }

    protected function setFuelQuantity(float $fuelQuantity)
    {
        $this->fuelQuantity = $fuelQuantity;
    }

    protected function setFuelPerKm(float $fuelPerKm)
    {
        $this->fuelPerKm = $fuelPerKm;
    }

    protected function setMaxDistance()
    {
        $this->maxDistance = $this->fuelQuantity / $this->fuelPerKm;
    }

    protected function setType(string $type)
    {
        $this->type = $type;
    }

    public function getMaxDistance(): float
    {
        return $this->maxDistance;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFuelQuantity(): float
    {
        return $this->fuelQuantity;
    }

    public function getFuelPerKm(): float
    {
        return $this->fuelPerKm;
    }

    public function __toString()
    {
        return $this->getType() . ": " . number_format($this->getFuelQuantity(), 2, ".", "");
    }

    public function Drive(float $distance)
    {
        if($distance > $this->getMaxDistance())
        {
            throw new \Exception("{$this->getType()} needs refueling");
        }
        $fuelQuantity = $this->getFuelQuantity() - ($distance * $this->getFuelPerKm());
        $this->setFuelQuantity($fuelQuantity);
        echo "{$this->getType()} travelled $distance km" . PHP_EOL;
    }
}

class Car extends Vehicle
{
    public function Refuel(float $fuel)
    {
        $this->setFuelQuantity($this->getFuelQuantity() + $fuel);
    }

    public function __construct(string $type, float $fuelQuantity, float $fuelPerKm)
    {
        parent::__construct($type, $fuelQuantity, $fuelPerKm = $fuelPerKm + 0.9);
    }
}

class Truck extends Vehicle
{
    public function Refuel(float $fuel)
    {
        $fuelToAdd = $fuel * (95/100);
        $this->setFuelQuantity($this->getFuelQuantity() + $fuelToAdd);
    }

    public function __construct(string $type, float $fuelQuantity, float $fuelPerKm)
    {
        parent::__construct($type, $fuelQuantity, $fuelPerKm = $fuelPerKm + 1.6);
    }
}

$carInfo = explode(" ", trim(fgets(STDIN)));
$truckInfo = explode(" ", trim(fgets(STDIN)));
$Car = new Car($carInfo[0], floatval($carInfo[1]), floatval($carInfo[2]));
$Truck = new Truck($truckInfo[0], floatval($truckInfo[1]), floatval($truckInfo[2]));
$n = intval(fgets(STDIN));
for($i = 0; $i < $n; $i++)
{
    try {
        $command = explode(" ", trim(fgets(STDIN)));
        ${$command[1]}->{$command[0]}(floatval($command[2]));
    }catch(\Exception $e){
        echo $e->getMessage() . PHP_EOL;
    }
}
echo $Car . PHP_EOL;
echo $Truck;