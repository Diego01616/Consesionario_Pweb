<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="CSS/estilo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <title>Document</title>
</head>
<body>

    <div id="navbar-placeholder"></div>

     <script>
    fetch("Nav.php")
        .then(res => res.text())
        .then(data => {
            document.getElementById("navbar-placeholder").innerHTML = data;
        })
        .catch(err => console.error("No se pudo cargar el navbar:", err));
    </script>
    
</body>
</html>