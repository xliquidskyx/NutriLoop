<?php
  session_start();
  if(!isset($_SESSION['login'])){
    header('Location: home.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nutriloop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500&family=Poppins:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
          <a class="navbar-brand" href="home.php">
            <h1 class="logo">NutriLoop</h1>
          </a>
          <a href="../php/wyloguj.php"><button type="button" class="btn btn-primary btn-sm">Wyloguj się</button></a>
        </div>
      </nav>


    <div class="container text-center">
      <div class="row">
        <h2 id="greeting">Witaj, <?php echo $_SESSION['login']?>!</h2></div>
        <p id="date"></p>
      <div class="row">
        <div class="col">
          <h2>Śniadanie</h2>
          <ul id="meal-plan-1" class="list-group list-group-flush meal-plan">
          </ul>
          <button class="btn btn-primary btn-sm add-product-btn">Dodaj produkt</button>
        </div>
        <div class="col">
          <h2>Obiad</h2>
          <ul id="meal-plan-2" class="list-group list-group-flush meal-plan">
          </ul>
          <button class="btn btn-primary btn-sm add-product-btn">Dodaj produkt</button>
        </div>
        <div class="col">
          <h2>Kolacja</h2>
          <ul id="meal-plan-3" class="list-group list-group-flush meal-plan">
          </ul>
          <button class="btn btn-primary btn-sm add-product-btn">Dodaj produkt</button>
        </div>
      </div>
    </div>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Dodaj Produkt</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="text" id="search-input" class="form-control" placeholder="Wpisz nazwę produktu...">
            <button class="btn btn-success mt-3" id="search-btn">Szukaj</button>
            <ul id="search-results" class="list-group">
              <!-- Wyniki wyszukiwania -->
            </ul>
          </div>
        </div>
      </div>
    </div>
    <script src="../js/api-connect.js"></script>
    <script>
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let yyyy = String(today.getFullYear());

        today = dd + '.' + mm + '.' + yyyy;
        document.getElementById('date').innerHTML = 'Plan na ' + today;
    </script>
</body>
</html>