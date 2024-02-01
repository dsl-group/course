<?php

header('Content-Type: application/json; charset=utf-8');

session_start();

require_once('db.php');

$pdo = getPDO();

$action = (!empty($_REQUEST['action']) ? $_REQUEST['action'] : '');
$idMessage = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
$message = (!empty($_REQUEST['message']) ? $_REQUEST['message'] : '');

switch($action) {

    case 'createMessage':

        if (!empty($_POST['message'])) {
            addNewMessage($pdo, htmlspecialchars($message));
        }

        $pdo = null;

        $response = ['success' => true];

        break;

    case 'deleteMessage':

        deleteMessage($pdo, $idMessage);

        $pdo = null;

        $response = ['success' => true];

        break;

    case 'list':

        $messages = getAllMessages($pdo);

        $pdo = null;

        foreach ($messages as $message) {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $role = ' <a href="?delete_message=' . $message['postid'] . '" class="deleteMessage" id="deleteMessage' . $message['postid'] . '">X</a>';
            } else {
                $role = '';
            }
            $resultMessages[] = [
                'postid'  => $message['postid'],
                'name'    => $message['name'],
                'date'    => $message['date'],
                'message' => $message['message'],
                'email'   => $message['email'],
                'role'    => $role
            ];
        }

        $response = ['data' => $resultMessages];

        break;

    case 'selectID':

        $messages = getIdMessages($pdo, $message);

        $pdo = null;

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $role = ' <a href="?delete_message=' . $messages['postid'] . '" class="deleteMessage" id="deleteMessage' . $message['postid'] . '">X</a>';
        } else {
            $role = '';
        }
        $resultMessages[] = [
            'postid'  => $messages['postid'],
            'name'    => $messages['name'],
            'date'    => $messages['date'],
            'message' => $messages['message'],
            'email'   => $messages['email'],
            'role'    => $role
        ];

        $response = ['data' => $resultMessages];

        break;
}

echo json_encode($response);