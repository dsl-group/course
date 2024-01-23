CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(255) NOT NULL,
    faculty_id INT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(faculty_id)
);

CREATE TABLE faculties (
    faculty_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_name VARCHAR(255) NOT NULL
);

CREATE TABLE subjects (
    subject_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(255) NOT NULL
);

CREATE TABLE points (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    subject_id INT,
    point INT,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

INSERT INTO faculties (faculty_name) VALUES ('Факультет Науковий'), ('Факультет Культури'), ('Факультет Управління');
INSERT INTO students (student_name, faculty_id) VALUES ('Антон', 1),  ('Вікторія', 2), ('Юлія', 3);
INSERT INTO subjects (subject_name) VALUES ('Математика'), ('Історія'), ('Адміністрування');
INSERT INTO points (student_id, subject_id, point) VALUES
    (1, 1, 90),
    (1, 2, 85),
    (2, 1, 78),
    (2, 3, 92),
    (3, 2, 88),
    (3, 3, 95);





