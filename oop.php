<?php

class Weapon {
    protected $name;
    protected $damage;

    public function __construct($name, $damage) {
        $this->name = $name;
        $this->damage = $damage;
    }

    public function getName() {
        return $this->name;
    }

    public function getDamage() {
        return $this->damage;
    }
}

class Hero {
    protected $health;
    protected $stamina;
    protected $weapon;

    public function __construct($health, $stamina, $weapon) {
        $this->health = $health;
        $this->stamina = $stamina;
        $this->weapon = $weapon;
    }

    public function getHealth() {
        return $this->health;
    }

    public function getStamina() {
        return $this->stamina;
    }

    public function getDamage() {
        return $this->weapon->getDamage(); // Отримати урон оружія героя
    }

    public function getWeaponName() {
        return $this->weapon->getName(); // Отримати назву оружія героя
    }
}

class Battle {
    public static function fight(Hero $hero1, Hero $hero2) {
        $hero1_health = $hero1->getHealth() + $hero1->getStamina() + $hero1->getDamage();
        $hero2_health = $hero2->getHealth() + $hero2->getStamina() + $hero2->getDamage();

        if ($hero1_health > $hero2_health) {
            return "Переможець: Герой 1";
        } elseif ($hero1_health < $hero2_health) {
            return "Переможець: Герой 2";
        } else {
            return "Нічия";
        }
    }
}

// Створюємо об'єкти зброї
$bow = new Weapon("Лук", 75);
$sword = new Weapon("Меч", 60);

// Створюємо об'єкти героїв
$heroName1 = new Hero(100, 50, $bow);
$heroName2 = new Hero(90, 70, $sword);

// Бій між героями
echo Battle::fight($heroName1, $heroName2);

// Переможець: Герой 1

