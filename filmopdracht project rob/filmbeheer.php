<?php
// --- DATABASE CONNECTIE ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "film project";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// --- ACTEURS OPHALEN ---
$acteurs = [];
$result = $conn->query("SELECT acteur_id, acteurnaam FROM acteur ORDER BY acteurnaam");
while ($row = $result->fetch_assoc()) {
    $acteurs[] = $row;
}

// --- FILM TOEVOEGEN OF BEWERKEN ---
$melding = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filmnaam = trim($_POST['filmnaam']);
    $genre = trim($_POST['genre']);
    $film_id = $_POST['film_id'] ?? null;

    if (empty($filmnaam) || empty($genre)) {
        $melding = "⚠️ Vul alle verplichte velden in.";
    } else {
        if (empty($film_id)) {
            // Nieuwe film toevoegen
            $stmt = $conn->prepare("INSERT INTO film (filmnaam, genre) VALUES (?, ?)");
            $stmt->bind_param("ss", $filmnaam, $genre);
            $stmt->execute();
            $film_id = $conn->insert_id;
            $stmt->close();
            $melding = "🎬 Film succesvol toegevoegd!";
        } else {
            // Bestaande film bijwerken
            $stmt = $conn->prepare("UPDATE film SET filmnaam=?, genre=? WHERE film_id=?");
            $stmt->bind_param("ssi", $filmnaam, $genre, $film_id);
            $stmt->execute();
            $stmt->close();
            $melding = "✅ Film bijgewerkt!";
        }

        // --- Acteurs en rollen koppelen ---
        if (isset($_POST['acteur_id']) && is_array($_POST['acteur_id']) &&
            isset($_POST['rol']) && is_array($_POST['rol'])) {

            for ($i = 0; $i < count($_POST['acteur_id']); $i++) {
                $acteur_id = intval($_POST['acteur_id'][$i] ?? 0);
                $rol = trim($_POST['rol'][$i] ?? '');

                // Alleen doorgaan als velden geldig zijn
                if ($acteur_id > 0 && $rol !== '') {
                    // Bestaat koppeling al?
                    $check = $conn->prepare("SELECT COUNT(*) FROM film_acteur WHERE film_id=? AND acteur_id=?");
                    $check->bind_param("ii", $film_id, $acteur_id);
                    $check->execute();
                    $check->bind_result($count);
                    $check->fetch();
                    $check->close();

                    if ($count > 0) {
                        // Alleen rol bijwerken
                        $upd = $conn->prepare("UPDATE film_acteur SET rol=? WHERE film_id=? AND acteur_id=?");
                        $upd->bind_param("sii", $rol, $film_id, $acteur_id);
                        $upd->execute();
                        $upd->close();
                    } else {
                        // Nieuwe koppeling toevoegen
                        $ins = $conn->prepare("INSERT INTO film_acteur (film_id, acteur_id, rol) VALUES (?, ?, ?)");
                        $ins->bind_param("iis", $film_id, $acteur_id, $rol);
                        $ins->execute();
                        $ins->close();
                    }
                }
            }

            $melding = "🎭 Film en rollen succesvol opgeslagen!";
        }
    }
}

// --- FILMS + ACTEURS OPHALEN ---
$sql = "
    SELECT 
        f.film_id, 
        f.filmnaam, 
        f.genre, 
        a.acteur_id, 
        a.acteurnaam, 
        fa.rol
    FROM film f
    LEFT JOIN film_acteur fa ON f.film_id = fa.film_id
    LEFT JOIN acteur a ON fa.acteur_id = a.acteur_id
    ORDER BY f.film_id, a.acteurnaam
";
$result = $conn->query($sql);

$films = [];
while ($row = $result->fetch_assoc()) {
    $fid = $row['film_id'];
    if (!isset($films[$fid])) {
        $films[$fid] = [
            'film_id' => $fid,
            'filmnaam' => $row['filmnaam'],
            'genre' => $row['genre'],
            'acteurs' => []
        ];
    }
    if (!empty($row['acteur_id'])) {
        $films[$fid]['acteurs'][] = [
            'id' => $row['acteur_id'],
            'naam' => $row['acteurnaam'],
            'rol' => $row['rol']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Film Beheer</title>
    <link rel="stylesheet" href="robcss.css">
    <script>
        // Formulier tonen/verbergen
        function toggleForm() {
            const form = document.getElementById('aanmaak-form');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        // Bewerkblok tonen/verbergen
        function toggleBewerk(id) {
            const blok = document.getElementById('bewerk-' + id);
            blok.style.display = (blok.style.display === 'block') ? 'none' : 'block';
        }

        // Dynamisch acteurveld toevoegen
        function voegActeurToe(containerId) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'acteur-selectie';
            div.innerHTML = `
                <select name="acteur_id[]" required>
                    <option value="">-- Kies acteur --</option>
                    <?php foreach ($acteurs as $a): ?>
                        <option value="<?= $a['acteur_id'] ?>"><?= htmlspecialchars($a['acteurnaam']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="rol[]" placeholder="Rol (bijv. Hoofdrol)" required>
                <button type="button" onclick="this.parentElement.remove()">❌</button>
            `;
            container.appendChild(div);
        }
    </script>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>🎬 Filmproject Rob</h2>
        <div class="nav-buttons">
            <a href="filmopdracht project rob.php">Home</a>
            <a href="FilmBeheer.php" class="active">FilmBeheer</a>
            <a href="ActeurBeheer.php">ActeurBeheer</a>
            <a href="MijnInfo.php">Mijn Info</a>
        </div>
    </div>

    <?php if (!empty($melding)): ?>
        <p class="melding" style="text-align:center; font-weight:bold;"><?= htmlspecialchars($melding) ?></p>
    <?php endif; ?>

    <!-- Nieuw filmformulier -->
    <div class="beheer-header">
        <button class="btn" onclick="toggleForm()">➕ Film Aanmaken</button>
    </div>

    <div id="aanmaak-form" class="beheer-container" style="display:none;">
        <h2>Nieuwe Film Aanmaken</h2>
        <form method="POST" class="film-form">
            <label>Filmnaam:</label>
            <input type="text" name="filmnaam" required>

            <label>Genre:</label>
            <input type="text" name="genre" required>

            <h3>Acteurs</h3>
            <div id="acteurs-container">
                <div class="acteur-selectie">
                    <select name="acteur_id[]" required>
                        <option value="">-- Kies acteur --</option>
                        <?php foreach ($acteurs as $a): ?>
                            <option value="<?= $a['acteur_id'] ?>"><?= htmlspecialchars($a['acteurnaam']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="rol[]" placeholder="Rol (bijv. Hoofdrol)" required>
                    <button type="button" onclick="this.parentElement.remove()">❌</button>
                </div>
            </div>
            <button type="button" onclick="voegActeurToe('acteurs-container')">+ Acteur toevoegen</button>
            <button type="submit" class="btn">💾 Opslaan</button>
        </form>
    </div>

    <hr class="scheiding">

    <!-- Bestaande films -->
    <h2 style="text-align:center;">Bestaande Films</h2>

    <div class="films-container">
        <?php foreach ($films as $id => $film): ?>
            <div class="film-card">
                <div class="film-image" style="background-image: url('images/placeholder.jpg');"></div>
                <div class="film-title"><?= htmlspecialchars($film['filmnaam']) ?></div>

                <div class="film-overlay">
                    <h2><?= htmlspecialchars($film['filmnaam']) ?></h2>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($film['genre']) ?></p>
                    <div class="acteur-list">
                        <strong>Acteurs:</strong><br>
                        <?php 
                        if (!empty($film['acteurs'])) {
                            foreach ($film['acteurs'] as $a) {
                                echo htmlspecialchars($a['naam']) . " als " . htmlspecialchars($a['rol']) . "<br>";
                            }
                        } else {
                            echo "<em>Geen acteurs gekoppeld</em>";
                        }
                        ?>
                    </div>
                    <button class="btn" onclick="toggleBewerk(<?= $id ?>)">✏️ Bewerk</button>
                </div>
            </div>

            <!-- Bewerkformulier -->
            <div id="bewerk-<?= $id ?>" class="bewerk-blok" style="display:none;">
                <form method="POST">
                    <input type="hidden" name="film_id" value="<?= $id ?>">

                    <label>Filmnaam:</label>
                    <input type="text" name="filmnaam" value="<?= htmlspecialchars($film['filmnaam']) ?>" required>

                    <label>Genre:</label>
                    <input type="text" name="genre" value="<?= htmlspecialchars($film['genre']) ?>" required>

                    <h3>Acteurs</h3>
                    <div id="acteurs-container-<?= $id ?>">
                        <?php if (!empty($film['acteurs'])): ?>
                            <?php foreach ($film['acteurs'] as $a): ?>
                                <div class="acteur-selectie">
                                    <select name="acteur_id[]" required>
                                        <option value="">-- Kies acteur --</option>
                                        <?php foreach ($acteurs as $opt): ?>
                                            <option value="<?= $opt['acteur_id'] ?>" <?= ($opt['acteur_id'] == $a['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($opt['acteurnaam']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="rol[]" value="<?= htmlspecialchars($a['rol']) ?>" required>
                                    <button type="button" onclick="this.parentElement.remove()">❌</button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="acteur-selectie">
                                <select name="acteur_id[]" required>
                                    <option value="">-- Kies acteur --</option>
                                    <?php foreach ($acteurs as $opt): ?>
                                        <option value="<?= $opt['acteur_id'] ?>"><?= htmlspecialchars($opt['acteurnaam']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="rol[]" placeholder="Rol" required>
                                <button type="button" onclick="this.parentElement.remove()">❌</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="button" onclick="voegActeurToe('acteurs-container-<?= $id ?>')">+ Acteur toevoegen</button>
                    <div style="margin-top:10px;">
                        <button type="submit" class="btn">💾 Opslaan</button>
                        <button type="button" class="btn-secondary" onclick="toggleBewerk(<?= $id ?>)">Annuleren</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
