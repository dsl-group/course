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
    protected $name;
    protected $health;
    protected $stamina;
    protected $weapon;
    protected $damage;
    protected $attackPhrases;
    protected $winPhrases;
    protected $losePhrases;
    protected $generalPhrases;

    public function __construct($name, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases) {
        $this->name = $name;
        $this->health = $health;
        $this->stamina = $stamina;
        $this->damage = $damage;
        $this->attackPhrases = $attackPhrases;
        $this->winPhrases = $winPhrases;
        $this->losePhrases = $losePhrases;
        $this->generalPhrases = $generalPhrases;
    }

    public function setWeapon(Weapon $weapon) {
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

    // Розрахунок урону з урахуванням базового урону та урону від зброї
    public function attack() {
        $totalDamage = $this->damage + $this->weapon->getDamage();
        return $totalDamage;
    }

    // Фрази при атакуванні ворога
    public function sayOnAttack() {
        return $this->name . ": " . $this->attackPhrases[array_rand($this->attackPhrases)];
    }

    // Фраза при перемозі ворога
    public function sayOnWin() {
        return $this->name . ": " . $this->winPhrases[array_rand($this->winPhrases)];
    }

    // Рандомні фрази
    public function say() {
        return $this->generalPhrases[array_rand($this->generalPhrases)];
    }

    // Фраза при програші + додавання рандомної фрази
    public function sayOnLose() {
        return $this->name . ": " . $this->losePhrases[array_rand($this->losePhrases)] . ', ' . $this->say();
    }
}

class Battle {
    public static function fight(Hero $hero1, Hero $hero2) {
        $hero1_health = $hero1->getHealth() + $hero1->getStamina() + $hero1->attack();
        $hero2_health = $hero2->getHealth() + $hero2->getStamina() + $hero2->attack();

        $battleLog = [];

        // Бій між героями
        while ($hero1_health > 0 && $hero2_health > 0) {
            // Атака першого героя
            $hero2_health -= $hero1->attack();
            $battleLog[] = $hero1->sayOnAttack();

            // Атака другого героя
            $hero1_health -= $hero2->attack();
            $battleLog[] = $hero2->sayOnAttack() . PHP_EOL;
        }

        if ($hero1_health > $hero2_health) {
            $battleLog[] = $hero1->sayOnWin();
            $battleLog[] = $hero2->sayOnLose();
        } elseif ($hero1_health < $hero2_health) {
            $battleLog[] = $hero2->sayOnWin();
            $battleLog[] = $hero1->sayOnLose();
        } else {
            $battleLog[] = "Нічия";
        }

        return $battleLog;
    }
}

// Створюємо героїв
$heroName1 = new Hero(
    "Герой 1",								// name
    100,										// health
    75,										// stamina
    55,										// damage
    ["Я атакую!", "Тобі кінець!", "Ти не втечеш!"],	// attackPhrases
    ["Перемога!", "Я великий переможець!"],			// winPhrases
    ["Я Програв :(", "Моя поразка :(", ""],			// losePhrases
    ["ганьба", "лузер", "фіаско"]					// generalPhrases
);
$heroName2 = new Hero(
    "Герой 2",							    // name
    90, 										// health
    70,										// stamina
    45,										// damage
    ["Я тебе переможу!", "Підемо на бій!"],		    // attackPhrases
    ["Перемога наша!", "Ми виграли!"],			    // winPhrases
    ["Програш", "Поразка"],						    // losePhrases
    ["факап", "чайник"]							    // generalPhrases
);

// Створюємо об'єкти зброї
$bow = new Weapon("Лук", 75);
$sword = new Weapon("Меч", 60);

// Додаємо зброю до героїв
$heroName1->setWeapon($bow);
$heroName2->setWeapon($sword);

// Бій між героями
$battleLog = Battle::fight($heroName1, $heroName2);

// Вивід результату бою та логу атак
foreach ($battleLog as $log) {
    echo $log . PHP_EOL;
}

// Герой 1: Я атакую!
// Герой 2: Я тебе переможу!

// Герой 1: Ти не втечеш!
// Герой 2: Підемо на бій!

// Герой 1: Ти не втечеш!
// Герой 2: Я тебе переможу!

// Герой 1: Я великий переможець!
// Герой 2: Поразка, чайник