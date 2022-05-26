<?php
/// Функции над юзером
function getUsers($connect){
    $res = [];
    foreach($connect->query("SELECT * FROM users") as $row){
        array_push($res, $row);
    };
    http_response_code(200);
    echo json_encode($res);
}
function deleteUser($connect, $data){
    $id = $data['id'];
    try {
      $connect->query("DELETE FROM users WHERE id = '$id'");  
      http_response_code(200);
      echo 'Пользователь удален';
    }
    catch(Exception $e){
        http_response_code(403);
        echo 'Ошибка';
    }

}
function register($connect, $data){
        $res = [];
        $check = [];
        $user_name = $data['user_name'];
        $password = md5($data['password']);
        $token = md5(md5($data['password']));
        $mobile_number = $data['mobile_number'];
        $name = $data['name'];
        $last_name = $data['last_name'];
        $middle_name = $data['middle_name'];
        $role = $data['role'];
        foreach($connect->query("SELECT user_name FROM users WHERE user_name = '$user_name'") as $row){
            array_push($check, $row);
        };
        if (!$check){
            $connect->query("INSERT INTO users (user_name, password, token, mobile_number, name, last_name, middle_name, role) VALUES ('$user_name', '$password','$token', '$mobile_number', '$name', '$last_name','$middle_name','$role')");
            http_response_code(200);
            $ans = [
                'status'=> true,
                'message'=> 'Запись успешно вставлена',
            ];
           echo json_encode($ans);
        }
        else{
            http_response_code(501);
            $ans = [
                'status'=> false,
                'message'=> 'Ошибка при записи данных',
            ];
            echo json_encode($ans);
        }
        
}
function changeLogin($connect, $data){
    $res = [];
    $login = $data['login'];
    $password = md5($data['password']);
    $user = $data['user'];
    try{
        $connect->query("UPDATE users SET user_name='$login', password='$password' WHERE user_name = '$user'");
        $connect->query("UPDATE purchased_order SET user_name='$login' WHERE user_name = '$user'");
        http_response_code(200);
        $ans = [
            'status'=> true,
            'message'=> 'Данные успешно обновлены',
            ];
        echo json_encode($ans);
    }
    catch(Exception $e){
        http_response_code(403);
        $ans = [
            'status'=> false,
            'message'=> 'Ошибка при записи данных',
            ];
        echo json_encode($ans);
    }
    
}
function login($connect,$data){
    $res = [];
    $login = $data['login'];
    $password = md5($data['password']);
    foreach($connect->query("SELECT * FROM users WHERE user_name='$login' and password='$password'") as $row){
        array_push($res, $row);
    };
    if ($res){
        http_response_code(200);
        echo json_encode($res);
    }
    else{
        http_response_code(403);
        $ans = [
            'status'=> false,
            'message'=> 'Не правильный логин или пароль',
            ];
        echo json_encode($ans);
    }
}
function autologin($connect, $data){
    $res = [];
    $token = $data['token'];
    foreach($connect->query("SELECT * FROM users WHERE token = '$token'") as $row){
        array_push($res, $row);
    };
    if ($res){
        http_response_code(200);
        echo json_encode($res);
    }
    else{
        http_response_code(403);
        $ans = [
            'status'=> false,
            'message'=> 'Не правильный логин или пароль',
            ];
        echo json_encode($ans);
    }
}
function getOrder($connect, $data){
    $res = [];
    $ans = [];
    $count = [];
    $user = $data['user'];
    foreach($connect->query("SELECT * FROM purchased_order WHERE user_name = '$user'") as $row){
        array_push($res, $row['order_id']);
        array_push($count, $row['ticket_count']);
    };
    if ($res){
        $i = 0;
        foreach($res as $row) { 
            // $order_id =  $row;
            // $ans += $order_id;
            // array_push($ans,$order_id);
            
            foreach($connect->query("SELECT * FROM order_list WHERE id = $row") as $rows)
            {
                $rows['ticket_count'] = $count[$i];
                $i+=1;
                // $rows->append('123');
                array_push($ans, $rows);
            };
        }
        http_response_code(200);
        echo json_encode($ans);
    }
    else{
        http_response_code(403);
        $ans = [
            'status'=> false,
            'message'=> 'У вас еще нет покупок',
            ];
        echo json_encode($ans);
    }
}
// Функции над турами
function createTours($connect, $data){
    try{
        $res = [];
        $name = $data['name'];
        $hotel = $data['hotel'];
        $country = $data['country'];
        $depart_date = $data['depart_date'];
        $arrive_date = $data['arrive_date'];
        $ticket_limit = $data['ticket_limit'];
        $desc_plus = $data['description_plus'];
        $desc_minus = $data['description_minus'];
        $price = $data['price'];
        $discount = $data['discount'];
        $res = [];
        foreach($connect->query("SELECT * FROM countrys WHERE name = '$country'") as $row){
            array_push($res, $row);
        };
        if(!$res){
           $connect->query("INSERT INTO countrys (name) VALUES ('$country')"); 
        }
        $connect->query("INSERT INTO order_list (name, hotel, country, depart_date, arrive_date, ticket_limit, description_plus, description_minus, price, discount) VALUES ('$name', '$hotel', '$country', '$depart_date', '$arrive_date', $ticket_limit, '$desc_plus',  '$desc_minus',$price, $discount)");
        http_response_code(200);
        $ans = [
            'status'=> true,
            'message'=> 'Запись успешно вставлена',
        ];
       echo json_encode($ans);
    } catch(Exception $e){
        http_response_code(501);
        $ans = [
            'status'=> false,
            'message'=> 'Ошибка при записи данных',
        ];
        echo json_encode($ans);
   }
    
}
function getTours($connect){
    $res = [];
    foreach($connect->query("SELECT * FROM order_list") as $row){
        array_push($res, $row);
    };
    http_response_code(200);
    echo json_encode($res);
}
function deleteTour($connect, $data){
    $id = $data['id'];
    $connect->query("DELETE FROM order_list WHERE id = '$id'");  
    http_response_code(200);
    echo 'Тур удален';

}
function getDataForCountry($connect, $data){
    $res = [];
    foreach($connect->query("SELECT * FROM order_list WHERE country = '$data'") as $row){
        array_push($res, $row);
      };
    if (!$res){
        http_response_code(404);
       $ans = [
        'status'=> false,
        'message'=> 'Запись не найдена',
       ];
       echo json_encode($ans);
    }
    else{
        echo json_encode($res);
    }
}

/// Операции над заказами
function setOrder($connect, $data){
    try{
        $res = [];
        $elem = $data['elem'];
        $user = $data['email'];
        $count = $data['tickets'];
        $connect->query("UPDATE order_list SET ticket_limit=ticket_limit-$count WHERE id = $elem");
        foreach($connect->query("SELECT * FROM order_list") as $row){
            array_push($res, $row);
        };
        foreach($res as $e){
            $id = $e['id'];
            if($e['ticket_limit'] <= 0){
                $connect->query("DELETE FROM order_list WHERE id = $id");
                echo json_encode('Запись удалена');
            }
        };
        $connect->query("INSERT INTO purchased_order (user_name,order_id,ticket_count) VALUES ('$user','$elem',$count)");
        http_response_code(201);
        $ans = [
            'status'=> true,
            'message'=> 'Запись успешно вставлена',
        ];
       echo json_encode($ans);
    } catch(Exception $e){
        http_response_code(501);
        $ans = [
            'status'=> false,
            'message'=> 'Ошибка при записи данных',
        ];
        echo json_encode($ans);
   }
}
function getCountrys($connect){
    $res = [];
    foreach($connect->query("SELECT name FROM countrys") as $row){
        array_push($res, $row);
      };
    http_response_code(200);
    echo json_encode($res);
}









function addTest($connect,$data){
    echo json_encode($data);
    $id = $data['id'];
    $name = $data['name'];
    $connect->query("INSERT into test (id, name) VALUES($id, '$name')");
    http_response_code(201);
    $ans = [
        'status'=> true,
        'message'=> 'Запись успешно добавлена',
       ];
    echo json_encode($ans);
}

?>