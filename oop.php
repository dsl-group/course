<?php

abstract class Weapon {
    protected $name;
    protected $damage;

    public function __construct(string $name, int $damage) {
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

class Onion extends Weapon {
    const NAME = 'Лук';
    const DAMAGE = [10, 15];

    public function __construct() {
        parent::__construct(self::NAME, mt_rand(self::DAMAGE[0], self::DAMAGE[1]));
    }
}

class Crossbow extends Weapon {
    const NAME = 'Арбалет';
    const DAMAGE = [11, 16];

    public function __construct() {
        parent::__construct(self::NAME, mt_rand(self::DAMAGE[0], self::DAMAGE[1]));
    }
}

class MagicStaff extends Weapon {
    const NAME = 'Магічний посох';
    const DAMAGE = [12, 17];

    public function __construct() {
        parent::__construct(self::NAME, mt_rand(self::DAMAGE[0], self::DAMAGE[1]));
    }
}

class Sword extends Weapon {
    const NAME = 'Меч';
    const DAMAGE = [13, 18];

    public function __construct() {
        parent::__construct(self::NAME, mt_rand(self::DAMAGE[0], self::DAMAGE[1]));
    }
}

class Pistol extends Weapon {
    const NAME = 'Пістолет';
    const DAMAGE = [14, 20];

    public function __construct() {
        parent::__construct(self::NAME, mt_rand(self::DAMAGE[0], self::DAMAGE[1]));
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
        // print_r($this->damage . '+' . $this->weapon->getDamage() . PHP_EOL);
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

class Warrior extends Hero {
    const NAME = 'Воїн';

    public function __construct($name, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases) {
        parent::__construct(self::NAME, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases);
    }
}

class Magician extends Hero {
    const NAME = 'Маг';

    public function __construct($name, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases) {
        parent::__construct(self::NAME, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases);
    }
}

class Archer extends Hero {
    const NAME = 'Лучник';

    public function __construct($name, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases) {
        parent::__construct(self::NAME, $health, $stamina, $damage, $attackPhrases, $winPhrases, $losePhrases, $generalPhrases);
    }
}

class Battle {
    public static function fight(Hero $hero1, Hero $hero2) {
        // Випадкове число, щоб визначити, який герой атакує першим
        $first_attacker = rand(1, 2);

        $hero1_health = $hero1->getHealth() + $hero1->getStamina();
        $hero2_health = $hero2->getHealth() + $hero2->getStamina();

        $battleLog = [];

        // Бій між героями
        while ($hero1_health > 0 && $hero2_health > 0) {
            // Атака першого героя
            if($first_attacker === 1) {
                $hero2_health -= $hero1->attack();
                $battleLog[] = "(Життя " . $hero1_health . ") " . $hero1->sayOnAttack() . " і наносить урон -" . $hero1->attack();
            } else {
                // Атака другого героя
                $hero1_health -= $hero2->attack();
                $battleLog[] = "(Життя " . $hero2_health . ") " . $hero2->sayOnAttack() . " і наносить урон -" . $hero2->attack() . PHP_EOL;
            }

            // Визначення наступного атакуючого героя
            $first_attacker = ($first_attacker === 1) ? 2 : 1;
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
$heroName1 = new Archer(
    "Герой 1",								// name
    100,										// health
    75,										// stamina
    20,										// damage
    ["Я атакую!", "Тобі кінець!", "Ти не втечеш!"],	// attackPhrases
    ["Перемога!", "Я великий переможець!"],			// winPhrases
    ["Я Програв :(", "Моя поразка :("],			// losePhrases
    ["ганьба", "лузер", "фіаско"]					// generalPhrases
);
$heroName2 = new Warrior(
    "Герой 2",							    // name
    100, 										// health
    70,										// stamina
    21,										// damage
    ["Я тебе переможу!", "Підемо на бій!"],		    // attackPhrases
    ["Перемога наша!", "Ми виграли!"],			    // winPhrases
    ["Програш", "Поразка"],						    // losePhrases
    ["факап", "чайник"]							    // generalPhrases
);

// Додаємо зброю до героїв
$heroName1->setWeapon( new Onion() ); // Archer
$heroName2->setWeapon( new Sword() ); // Warrior

// Бій між героями
$battleLog = Battle::fight($heroName1, $heroName2);

// Вивід результату бою та логу атак
foreach ($battleLog as $log) {
    echo $log . PHP_EOL;
}
