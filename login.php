<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12"><p></p></div>
        <div class="col-md-3"></div>
        <div class="col-md-4">
            <?php
            session_start();
            define("DIR", __DIR__ . "/"); // поправил по Вашей рекомендации
            define("FILE", "file.txt");
            $base = array('test@mail.ru' => '123456', 'test@gmail.com' => '111222');

            function getPDO() { // отказался от getPDO(): PDO, т.к. PHP 5.6 не поддерживает строгую типизацию
                $host = 'localhost';
                $username = 'lessonsusr';
                $password = 'B9a0Q6y9';
                $dbName = 'lessons';

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    return $pdo;
                } catch (PDOException $e) {
                    // Обработка ошибки, например, логирование или вывод сообщения
                    echo '<div class="alert alert-danger" role="alert">Ошибка подключения к базе данных: ' . $e->getMessage() . '</div>';
                }
            }

            function getAllMessages(PDO $pdo) {
                $data = [];
                $sql = "SELECT m.postid, m.userid, m.message, m.date, u.userid, u.name, u.email, u.role FROM messages m, users u WHERE m.userid = u.userid";
                $statement = $pdo->prepare($sql);
                $statement->execute();

                $data = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $data;
            }

            function addNewMessage(PDO $pdo, $message) { // php 5.6 ругается на string
                $sql = "INSERT INTO messages (userid, message) VALUES (:userid, :message)";
                $queryRunner = $pdo->prepare($sql);
                $queryRunner->bindValue(':userid', $_SESSION['userid']);
                $queryRunner->bindValue(':message', $message);

                if (!$queryRunner->execute()) {
                    echo 'Something went wrong';
                }
            }

            function deleteMessage($pdo, $postid) { // php 5.6 ругается на string
                $sql = "DELETE FROM messages WHERE postid=:postid";
                $queryRunner = $pdo->prepare($sql);
                if (!$queryRunner->execute(['postid' => $postid])) {
                    echo 'Something went wrong';
                }
            }

            function getAutorization(PDO $pdo, $email, $password) {
                $sqlUserRequest = "SELECT userid, name, email, password, role FROM users WHERE email = :email";
                $sqlUserResult = $pdo->prepare($sqlUserRequest);

                $sqlUserResult->bindValue(':email', $email);
                $sqlUserResult->execute();

                $userData = $sqlUserResult->fetch(PDO::FETCH_ASSOC);

                if ($userData && password_verify($password, $userData['password'])) {
                    return $userData;
                } else {
                    return null;
                }
            }

            $pdo = getPDO();
            /*
            // обновление HASH пароля в базе
            $email = 'user@localhost.com';
            $password = password_hash('111222', PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            //$sql = "UPDATE users SET password = :password WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            */
            // Add new
            if (!empty($_POST['message'])) {
                addNewMessage($pdo, htmlspecialchars($_POST['message']));
            }

            // Delete
            if (!empty($_GET['delete_message'])) {
                deleteMessage($pdo, $_GET['delete_message']);
            }

            $messages = getAllMessages($pdo);

            $action = (!empty($_REQUEST['action']) ? $_REQUEST['action'] : '');
            if($action == 'logout') {
                session_destroy();

                $file = array($_SESSION['email'], date("Y-m-d H:i:s"), 'logout');
                $handler = fopen(DIR . FILE, "a");
                fputcsv($handler, $file, ';');
                fclose($handler);

                header ("Location: login.php");
            }
            if(!empty($_POST['authorization']) && !empty($_POST['email']) && !empty($_POST['password'])) {

                $password = $_POST['password']; // думал и тут нужно оборачивать в password_hash, а оказалось только при регистрации или при обновлени пароля $password = password_hash('123456', PASSWORD_DEFAULT);
                $login = getAutorization($pdo, $_POST['email'], $password);

                if ($login) {
                    $_SESSION['userid'] = $login['userid'];
                    $_SESSION['name'] = $login['name'];
                    $_SESSION['email'] = $login['email'];
                    $_SESSION['role'] = $login['role'];

                    $file = array($_SESSION['email'], date("Y-m-d H:i:s"), 'login');
                    $handler = fopen(DIR . FILE, "a");
                    fputcsv($handler, $file, ';');
                    fclose($handler);
                } else {
                    echo '<div class="alert alert-danger" role="alert">Неверно введен email или пароль</div>';
                }

                /*
                if (array_key_exists($_POST['email'], $base)) {
                    if($base[$_POST['email']] == $_POST['password']) {
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['role'] = $_POST['email'];

                        $file = array($_SESSION['email'], date("Y-m-d H:i:s"), 'login');
                        $handler = fopen(DIR . FILE, "a");
                        fputcsv($handler, $file, ';');
                        fclose($handler);

                    } else {
                        echo '<div class="alert alert-danger" role="alert">Невірно введений пароль</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Email не знайден</div>';
                }*/
            } elseif(!empty($_POST['authorization'])) {
                echo '<div class="alert alert-danger" role="alert">Email або пароль не введено</div>';
            }

            ?>
            <?php if(!empty($_SESSION['email'])): ?>
                <div class="alert alert-light" role="alert">Ви намагались залогінитись з Email:<br /><?= $_SESSION['email']; ?> | <a href="login.php?action=logout">Выход</a></div>
            <?php else: ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите E-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
                        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" />
                    </div>
                    <!--<div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>-->
                    <button type="submit" name="authorization" value="login" class="btn btn-primary">Sign up</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-12"><p></p></div>
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <p>Admin: admin@localhost.com / 123456</p>
            <p>User: user@localhost.com / 111222</p>
            <div class="card">
                <div class="card-header">
                    Chat
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($messages as $message) : ?>
                        <li class="list-group-item">
                            <strong><?= $message['name'] ?></strong> at
                            <?= $message['date'] ?> :
                            <i><?= $message['message'] ?></i>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                                <a href="?delete_message=<?= $message['postid'] ?>">X</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if(!empty($_SESSION['email'])): ?>
                <p></p>
                <div class="card">
                    <div class="card-header">
                        Add <u><?= $_SESSION['name'] ?></u> message
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="exampleInputName" class="form-label">Type message this:</label>
                                    <input type="text" class="form-control" name="message" />
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-12"><p></p></div>
        <div class="col-md-2"></div>
        <div class="col-md-6">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">login</th>
                    <th scope="col">date</th>
                    <th scope="col">action</th>
                </tr>
                </thead>
                <tbody>
                <?php

                if($action == 'truncate') {
                    $handler = fopen(DIR . FILE, "w");
                    fclose($handler);
                }

                if($action == 'deleteid' && !empty($_GET['id'])) {
                    $oldFile = file(DIR . FILE);
                    unset($oldFile[$_GET['id']]);
                    $newFile = fopen(DIR . FILE, "w+");
                    foreach($oldFile as $string) {
                        fwrite($newFile, $string);
                    }
                    fclose($newFile);
                    header ("Location: login.php");
                }

                $str = 0;
                $handler = fopen(DIR . FILE, "r");
                while (!feof($handler)) {
                    $csvFile = fgetcsv($handler, '', ';');
                    if($csvFile[0]) {
                        echo '<tr>
					  <th scope="row">' . $str . '</th>
					  <td>' . $csvFile[0] . '</td>
					  <td>' . $csvFile[1] . '</td>
					  <td>' . $csvFile[2] . '</td>
					  <td><a href="login.php?action=deleteid&id=' . $str . '">x</a></td>
					</tr>' . PHP_EOL;
                    }
                    $str++;
                }
                fclose($handler);

                $pdo = null;

                ?>
                </tbody>
            </table>
            <p><a href="login.php?action=truncate">Очистка файла</a></p>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
</body>
</html>
