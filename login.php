<!DOCTYPE html>
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
            $base = array('test@mail.ru' => '123456', 'test@gmail.com' => '111222');
            $action = (!empty($_REQUEST['action']) ? $_REQUEST['action'] : '');
            if($action == 'logout') {
                session_destroy();
                header ("Location: login.php");
            }
            if(!empty($_POST['authorization']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                if (array_key_exists($_POST['email'], $base)) {
                    if($base[$_POST['email']] == $_POST['password']) {
                        $_SESSION['email'] = $_POST['email'];
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Невірно введений пароль</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Email не знайден</div>';
                }
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
