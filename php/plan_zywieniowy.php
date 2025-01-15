<?php

    session_start();
    header('Content-Type: application/json'); 
    $method = $_SERVER['REQUEST_METHOD'];

    include_once('db_connect.php');

    //pobranie id dla odpowiedniego loginu
    $login = $_SESSION['login'];
    $data = date('Y-m-d');
    $stmt = $conn->prepare('SELECT id FROM uzytkownicy WHERE login = ?');
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $userId = $user['id'];
    } else {
        http_response_code(404);
    }
    
    //dodawanie produktu do bazy
    if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['posilek'], $input['kalorie'])) {
        http_response_code(400);
        exit;
    }

    $posilek = $input['posilek'];
    $kalorie = $input['kalorie'];
    $nazwa = $input['nazwa'];
    $bialko = $input['bialko'];
    $tluszcz = $input['tluszcz'];
    $weglowodany = $input['weglowodany'];

    $stmt = $conn->prepare("INSERT INTO plan_zywieniowy (user_id, data, posilek, kalorie, bialko, tluszcz, weglowodany, nazwa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssss', $userId, $data, $posilek, $kalorie, $bialko, $tluszcz, $weglowodany, $nazwa);
    $stmt->execute();

    //zwracamy JSONa z potwierdzeniem wykonania akcji  
    echo json_encode(['success' => true, 'message' => 'Dane zapisane poprawnie']);
    exit;
    }

    //pobieranie i aktualizowanie liczby kalorii
    if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_totals') {

        $stmt = $conn->prepare("SELECT posilek, SUM(kalorie) as suma_kalorie FROM plan_zywieniowy WHERE user_id = ? AND data = CURDATE() GROUP BY posilek");
        $stmt->bind_param('s', $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $totals = [
            'sniadanie' => 0,
            'obiad' => 0,
            'kolacja' => 0
        ];
    
        foreach ($result as $row) {
            $totals[$row['posilek']] = $row['suma_kalorie'];
        }
    
        echo json_encode(['success' => true, 'totals' => $totals]);
        exit;
    }
    
    if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_products') {
    
        $stmt = $conn->prepare("SELECT posilek, nazwa, kalorie, tluszcz, weglowodany, bialko FROM plan_zywieniowy WHERE user_id = ? AND data = ?");
        $stmt->bind_param('ss', $userId, $data);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode(['success' => true, 'products' => $products]);
        exit;
    }
?>