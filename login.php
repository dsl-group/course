<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        function appendComment(postid, name, date, message, role) {
            let $blured = $("");

            if (message.toLowerCase().includes("заборона")) {
                console.log(message);
                $blured = " blured";
            } else {
                $blured = "";
            }

            $("#list-of-messages").append(
                "<li class='list-group-item" + $blured + "' id='postid" + postid + "'><strong>" + name + "</strong> at " + date + ": <i>" + message + "</i>" + role + "</li>"
            );
        }

        $(document).ready(function () {

            $.ajax({
                url: 'messages.php',
                method: 'GET',
                data: { action: 'list' },
                success: function (request) {
                    if (request.data) {
                        console.log(request.data);
                        request.data.map(function (comment) {
                            appendComment(comment.postid, comment.name, comment.date, comment.message, comment.role);
                        })
                    }
                }
            })

            $("form[name='chat']").submit(function (event) {
                event.preventDefault();

                //let data1 = $(this).serialize();
                //let data2 = {
                //	   name: $(this).find('input[name="name"]').val(),
                //	message: $(this).find('input[name="message"]').val()
                //};
                //console.log('Variant 1 :' + data1);
                //console.log('Variant 2 :');

                let message = $(this).find('input[name="message"]').val();
                console.log(message);

                $.ajax({
                    url: 'messages.php',
                    method: 'POST',
                    data: { action: 'createMessage', message: message },
                    /*
                    success: function (request) {
                        appendComment('<?= $_SESSION['name'] ?>', new Date().toLocaleString(), message, '<?= $_SESSION['role'] ?>');
							$("form[name='chat']").trigger("reset");
						}
						*/
                    success: function () {
                        // После успешной отправки, делаем запрос для получения нового комментария из базы
                        $.ajax({
                            url: 'messages.php',
                            method: 'GET',
                            data: { action: 'selectID', message: message },
                            success: function (response) {
                                // Проверяем, есть ли данные в ответе
                                if (response.data) {
                                    // Получаем данные нового комментария из базы
                                    console.log(response.data);
                                    response.data.map(function (comment) {
                                        appendComment(comment.postid, comment.name, comment.date, comment.message, comment.role);
                                    })
                                }
                                $("form[name='chat']").trigger("reset");
                            }
                        });
                    }
                })
            })

        });

        $(document).on('click', '.deleteMessage', function (event) {
            event.preventDefault();

            var messageId = $(this).attr('href').split('=')[1];

            $.ajax({
                url: 'messages.php',
                method: 'GET',
                data: { action: 'deleteMessage', id: messageId },
                success: function (response) {
                    if (response.success) {
                        $('#postid' + messageId).remove();
                    } else {
                        console.log('Ошибка при удалении сообщения');
                    }
                }
            });
        });

    </script>
    <style>
        .blured {
            filter: blur(5px);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12"><p></p></div>
        <div class="col-md-3"></div>
        <div class="col-md-4">
            <?php

            define("DIR", __DIR__ . "/"); // поправил по Вашей рекомендации
            define("FILE", "file.txt");

            $base = array('test@mail.ru' => '123456', 'test@gmail.com' => '111222');

            require_once('db.php');

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
            //if (!empty($_POST['message'])) {
            //	addNewMessage($pdo, htmlspecialchars($_POST['message']));
            //}

            // Delete
            //if (!empty($_GET['delete_message'])) {
            //	deleteMessage($pdo, $_GET['delete_message']);
            //}

            // $messages = getAllMessages($pdo);

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
                <form action="login.php" name="login" method="post">
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
                <ul class="list-group list-group-flush" id="list-of-messages">

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
                            <form method="post" name="chat">
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
