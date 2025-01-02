<?php
session_start();

$blad_logowania = false;

if (!empty($_POST['login']) && !empty($_POST['haslo'])) {
    $login = htmlspecialchars($_POST['login']);
    $haslo = htmlspecialchars($_POST['haslo']);

    include_once('db_connect.php');

    $stmt = $conn->prepare("SELECT login, haslo FROM uzytkownicy WHERE login = ?");
    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $haslo_z_bazy = $row['haslo'];

        if (password_verify($haslo, $haslo_z_bazy)) {
            $_SESSION['login'] = $login;
            echo 'success';
            exit;
        } else {
            $blad_logowania = true;
        }
    } else {
        $blad_logowania = true;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Proszę wypełnić wszystkie pola.";
}

if ($blad_logowania) {
    echo "Nieprawidłowy login lub hasło.";
}
?>