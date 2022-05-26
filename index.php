<?php
    require_once 'dpconnect.php';
    require 'functions.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Credentials: true');
    header('Content-type: json/application');



    global $pdo;
    $res = [];
    $q = $_GET['q'];
    $params = explode('/', $q);
    $type = $params[0];
    $id =  $params[1];


    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if ($type == 'getDataForCountry'){
                if (isset($id)){
                    getDataForCountry($pdo, $id);
                }
                else{
                    // getTests($pdo); 
                }
            }
            if ($type == 'getDataForCountry'){
                if (isset($id) and $id == 'cookies'){
                    autoAuthorize($pdo);
                }
                else{
                    // getTests($pdo); 
                }
            }
            if ($type == 'getUsers'){
                getUsers($pdo);
            }
            if ($type == 'getTours'){
                getTours($pdo);
            }
            if ($type == 'getCountrys'){
                getCountrys($pdo);
            }
            break;
        case 'POST':
            if ($type == 'auth'){
                login($pdo,$_POST);
                // insertData($pdo,$_POST);
            }
            else if($type == 'autorize'){
                autologin($pdo, $_POST);
            }
            else if($type == 'setOrder'){
                setOrder($pdo, $_POST);
            }
            else if($type == 'getOrder'){
                getOrder($pdo, $_POST);
            }
            else if($type == 'changeLogin'){
                changeLogin($pdo, $_POST);
            }
            else if($type == 'register'){
                register($pdo, $_POST);
            }
            else if($type == 'deleteUser'){
                deleteUser($pdo, $_POST);
            }
            else if($type == 'deleteTour'){
                deleteTour($pdo, $_POST);
            }
            else if($type == 'createTours'){
                createTours($pdo, $_POST);
            }
            break;
    }

    

