<?php

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

function getIdMessages(PDO $pdo, $message) {
    $data = [];
    $sql = "SELECT m.postid, m.userid, m.message, m.date, u.userid, u.name, u.email, u.role FROM messages m, users u WHERE m.userid = u.userid AND m.message = :message ORDER BY m.date DESC"; // добавлена сортировка, чтобы вытаскивал последний комментарий даже если он одиннаковый с предыдущим
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':message', $message);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

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

?>