<?php
    $options = array(
        PDO:: ATTR_ERRMODE            =>PDO::ERRMODE_EXCEPTION,
        PDO:: ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
    );
    $user = 'postgres';
    $pass = 'qwerty2002';
    try {
        $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=flying.py;', $user, $pass, $options);

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }