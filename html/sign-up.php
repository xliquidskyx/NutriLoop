<?php
    session_start();
    if(isset($_SESSION['login'])) {
        header('Location: dashboard.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się - NutriLoop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/sign-up.css">
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500&family=Poppins:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
          <a class="navbar-brand" href="home.php">
            <h1 class="logo">NutriLoop</h1>
          </a>
          <a href="sign-in.php"><button type="button" class="btn btn-primary btn-sm">Zaloguj się</button></a>
        </div>
      </nav>

        <form action="../php/utworz_konto.php" method="post" id="multiStepForm">
            
            <container class="main-form active" id="first-step">
                <h2>Dane podstawowe</h2>
                <div class="row g-3">
                    <div class="col">
                        <label for="login" class="label">Login</label>
                        <input id="login" type="text" name="login" class="form-control" placeholder="Podaj swój login..." aria-label="Login" min="1" max="20" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="imie" class="label">Imię</label>
                        <input id="imie" type="text" name="imie" class="form-control" placeholder="Podaj imię..." aria-label="Imię" min="1" max="20" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="email" class="label">Email</label>
                        <input id="email" type="email" name="email" class="form-control" placeholder="Podaj e-mail..." aria-label="Email" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="haslo" class="label">Hasło</label>
                        <input id="haslo" type="password" name="haslo" class="form-control password" placeholder="Podaj hasło..." aria-label="Hasło" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="powtorz-haslo" class="label">Powtórz hasło</label>
                        <input id="powtorz-haslo" type="password" name="powtorz-haslo" class="form-control repeated" placeholder="Powtórz hasło..." aria-label="Hasło" required>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm next-step">Dalej</button>
            </container>

            <container class="main-form" id="second-step">
                <h2>Dane szczegółowe</h2>
                <div class="row g-3">
                    <div class="col">
                        <label for="data" class="label">Data urodzenia</label>
                        <input id="data" type="date" name="data" class="form-control" placeholder="12.12.2024" aria-label="Data urodzenia" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="wzrost" class="label">Wzrost</label>
                        <input id="wzrost" type="number" name="wzrost" class="form-control" placeholder="170cm" aria-label="Wzrost" min="100" max="250" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="waga" class="label">Waga</label>
                        <input id="waga" type="number" name="waga" class="form-control" placeholder="60kg" aria-label="Waga" min="20" max="1000" required>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <p class="label">Płeć</p>
                        <input id="kobieta" type="radio" name="plec"  value="kobieta" aria-label="Kobieta" checked>
                        <label for="kobieta">Kobieta</label>
                        <input id="mezczyzna" type="radio" name="plec" value="mężczyzna" aria-label="Mężczyzna">
                        <label for="mezczyzna">Mężczyzna</label>
                    </div>
                </div>
                <div class="form-navigation">
                    <button type="button" class="btn btn-primary btn-sm prev-step">Wstecz</button>
                    <button type="submit" class="btn btn-primary btn-sm">Załóż konto</button>
                </div>
            </container>
        </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/form.js"></script>
</body>
</html>