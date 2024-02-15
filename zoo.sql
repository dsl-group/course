-- id, звірятко, група, вік, стать,
CREATE TABLE Animals (
    animal_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    age INT,
    sex VARCHAR(255)
);
INSERT INTO Animals (name, species, age, sex) VALUES
('Лев', 'Звірі', 5, 'Самець'),
('Тигр', 'Звірі', 4, 'Самиця'),
('Слон', 'Звірі', 10, 'Самець');

-- id, ім'я співробітника, посада
CREATE TABLE Employees (
    employee_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL
);
INSERT INTO Employees (name, position) VALUES
('Іванов Іван', 'Доглядач'),
('Петров Петро', 'Ветеринар'),
('Сидоров Семен', 'Адміністратор');

-- id, id співробітника, id тварини за якою наглядає співробітник, розклад
CREATE TABLE AnimalCare (
    care_id SERIAL PRIMARY KEY,
    employee_id INT REFERENCES Employees(employee_id),
    animal_id INT REFERENCES Animals(animal_id),
    schedule VARCHAR(255) NOT NULL
);
INSERT INTO AnimalCare (employee_id, animal_id, schedule) VALUES
(1, 1, 'Понеділок - П\'ятниця, 9:00 - 17:00'),
(2, 2, 'Щоденно, залежно від потреби'),
(3, 3, 'Понеділок - Субота, 8:00 - 16:00');

-- id, назва продукту, тип (класифікація) продукту, вага на складі, строк придатності
CREATE TABLE AnimalFood (
    food_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    weight INT,
    expiry_date DATE
);
INSERT INTO AnimalFood (name, type, weight, expiry_date) VALUES
('М\'ясо', 'М\'ясо', 100, '2024-03-01'),
('Риба', 'М\'ясо', 50, '2024-02-20'),
('Сіно', 'Трава', 200, '2024-04-01');

-- id тварини, id продукту, вага виписаного корму, дата
CREATE TABLE AnimalFeeding (
    feeding_id SERIAL PRIMARY KEY,
    animal_id INT REFERENCES Animals(animal_id),
    food_id INT REFERENCES AnimalFood(food_id),
    weight INT,
    feeding_time TIMESTAMP
);
INSERT INTO AnimalFeeding (animal_id, food_id, weight, feeding_time) VALUES
(1, 1, 5, '2024-02-15 10:00:00'),
(2, 2, 3, '2024-02-15 12:00:00'),
(3, 3, 10, '2024-02-15 14:00:00');
