<?php
    require_once 'login.php';

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }

    catch (PDOException $e){
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    $query = "INSERT INTO cats VALUES(NULL, 'Cougar', 'Growler', 2)";

    $result = $pdo->query($query);
    
?>