<?php
$conn = new mysqli(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASSWORD"),
    getenv("DB_NAME")
);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$sql = "";

if (isset($_POST['akcja'])) {
    switch ($_POST['akcja']) {
        case "wszyscy":
            $sql = "SELECT * FROM uczniowie";
            break;

        case "miejscowosci":
            $sql = "SELECT DISTINCT miejscowosc FROM uczniowie";
            break;

        case "hobby":
            $sql = "SELECT hobby, COUNT(*) AS liczba FROM uczniowie GROUP BY hobby";
            break;

        case "maile":
            $sql = "SELECT imie, nazwisko, email FROM uczniowie";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kwerendy SQL</title>
</head>
<body>

<form method="post">
    <button type="submit" name="akcja" value="wszyscy">Wszyscy użytkownicy</button>
    <button type="submit" name="akcja" value="miejscowosci">Miejscowości</button>
    <button type="submit" name="akcja" value="hobby">Liczba osób według hobby</button>
    <button type="submit" name="akcja" value="maile">Lista adresów email</button>
</form>

<?php
if ($sql != "") {
    $wynik = $conn->query($sql);

    if ($wynik && $wynik->num_rows > 0) {
        echo "<table border='1' cellpadding='6'><tr>";
        while ($pole = $wynik->fetch_field()) {
            echo "<th>" . htmlspecialchars($pole->name) . "</th>";
        }
        echo "</tr>";

        while ($wiersz = $wynik->fetch_assoc()) {
            echo "<tr>";
            foreach ($wiersz as $wartosc) {
                echo "<td>" . htmlspecialchars((string)($wartosc ?? "")) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Brak wyników.</p>";
    }
}

$conn->close();
?>

</body>
</html>