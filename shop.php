<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$host = "localhost";
$db   = "shop2";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database verbinding mislukt",
        "details" => $e->getMessage()
    ]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {


    case 'GET':
        try {
            $stmt = $pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            $producten = $stmt->fetchAll();

            if (empty($producten)) {
                http_response_code(204);
                echo json_encode([]);
                exit;
            }

            http_response_code(200);
            echo json_encode($producten);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Fout bij ophalen producten",
                "details" => $e->getMessage()
            ]);
        }
        break;

    case 'POST':
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if ($data === null) {
            http_response_code(400);
            echo json_encode(["error" => "Ongeldige of ontbrekende JSON"]);
            exit;
        }

        if (empty($data['naam']) || !isset($data['prijs'])) {
            http_response_code(400);
            echo json_encode(["error" => "Naam en prijs zijn verplicht"]);
            exit;
        }

        if (!is_numeric($data['prijs'])) {
            http_response_code(400);
            echo json_encode(["error" => "Prijs moet een getal zijn"]);
            exit;
        }

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO products (naam, prijs)
                 VALUES (:naam, :prijs)"
            );
            $stmt->execute([
                ":naam"  => $data['naam'],
                ":prijs" => $data['prijs']
            ]);

            http_response_code(201);
            echo json_encode(["message" => "Product succesvol toegevoegd"]);
        } catch (PDOException $e) {

            // unieke productnaam
            if ($e->getCode() === "23000") {
                http_response_code(400);
                echo json_encode(["error" => "Productnaam bestaat al"]);
                exit;
            }

            http_response_code(500);
            echo json_encode([
                "error" => "Fout bij opslaan product",
                "details" => $e->getMessage()
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
