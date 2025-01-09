<?php

        include_once('db_connect.php');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Pobieranie danych z formularza
            $login = htmlspecialchars($_POST['login']);
            $imie = htmlspecialchars($_POST['imie']);
            $email = htmlspecialchars($_POST['email']);
            $haslo = htmlspecialchars($_POST['haslo']);
            $data = htmlspecialchars($_POST['data']);
            $wzrost = htmlspecialchars($_POST['wzrost']);
            $waga = htmlspecialchars($_POST['waga']);
            $plec = htmlspecialchars($_POST['plec']);
            // Prosta walidacja
            if (empty($login) || empty($imie) || empty($email) || empty($haslo) || empty($data) || empty($wzrost) || empty($waga) || empty($plec)) {
                http_response_code(400);
                echo "Wszystkie pola są wymagane.";
                exit;
            }

            // Hashowanie hasła
            $hashedPassword = password_hash($haslo, PASSWORD_DEFAULT);

            // Przygotowanie zapytania SQL
            $stmt = $conn->prepare("INSERT INTO uzytkownicy (login, imie, email, haslo, data_urodzenia, wzrost, waga, plec) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $login, $imie, $email, $hashedPassword, $data, $wzrost, $waga, $plec);

            // Wykonanie zapytania
            if ($stmt->execute()) {
                session_start();
                $_SESSION['login'] = $login;
                header('Location: ../html/dashboard.php');
            } else {
                http_response_code(500);
                echo "Wystąpił błąd podczas rejestracji.";
            }
            } else {
            http_response_code(405);
            echo "Nieobsługiwana metoda żądania.";
            }
        
            $conn->close();
?>