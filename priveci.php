<?php
// Privacybeleid voor je website
echo "<h1>Privacybeleid</h1>";

// Introductie
echo "<p>Welkom op onze website. We nemen de bescherming van je privacy serieus en willen je via dit privacybeleid informeren over hoe we je persoonlijke gegevens verzamelen, gebruiken en beschermen.</p>";

// Welke gegevens we verzamelen
echo "<h2>1. Welke gegevens verzamelen wij?</h2>";
echo "<p>We verzamelen de volgende gegevens wanneer je onze website gebruikt:</p>";
echo "<ul>";
echo "<li>Naam en e-mailadres (bijvoorbeeld bij inschrijving voor een nieuwsbrief)</li>";
echo "<li>IP-adres</li>";
echo "<li>Informatie over je gebruik van onze website (zoals via cookies)</li>";
echo "</ul>";

// Hoe we gegevens verzamelen
echo "<h2>2. Hoe verzamelen wij gegevens?</h2>";
echo "<p>We verzamelen gegevens op verschillende manieren, onder andere:</p>";
echo "<ul>";
echo "<li>Via formulieren die je invult op onze website</li>";
echo "<li>Via cookies die we gebruiken om je gebruikerservaring te verbeteren</li>";
echo "<li>Via serverlogs (zoals je IP-adres)</li>";
echo "</ul>";

// Hoe we gegevens gebruiken
echo "<h2>3. Hoe gebruiken wij de verzamelde gegevens?</h2>";
echo "<p>De verzamelde gegevens gebruiken we voor de volgende doeleinden:</p>";
echo "<ul>";
echo "<li>Om je te voorzien van de gevraagde diensten</li>";
echo "<li>Voor het versturen van marketingmateriaal (indien je hiervoor toestemming hebt gegeven)</li>";
echo "<li>Voor het verbeteren van onze website en diensten</li>";
echo "</ul>";

// Hoe we je gegevens beschermen
echo "<h2>4. Hoe beschermen wij je gegevens?</h2>";
echo "<p>We nemen passende maatregelen om je persoonlijke gegevens te beschermen tegen onbevoegde toegang, wijziging, openbaarmaking of vernietiging. Dit omvat onder andere:</p>";
echo "<ul>";
echo "<li>Het gebruik van versleutelingstechnologieën waar mogelijk</li>";
echo "<li>Toegang tot gegevens wordt beperkt tot bevoegde medewerkers</li>";
echo "</ul>";

// Gebruik van cookies
echo "<h2>5. Cookies</h2>";
echo "<p>Onze website maakt gebruik van cookies om je ervaring te verbeteren. Cookies zijn kleine tekstbestanden die op je apparaat worden opgeslagen. Je kunt je browser zo instellen dat je wordt gewaarschuwd wanneer er cookies worden gebruikt, zodat je zelf kunt bepalen of je ze wilt accepteren of niet.</p>";

// Rechten van gebruikers
echo "<h2>6. Je rechten</h2>";
echo "<p>Je hebt het recht om te verzoeken om inzage in je persoonlijke gegevens, correctie van onjuiste gegevens of verwijdering van je gegevens. Neem contact met ons op via de onderstaande contactgegevens als je gebruik wilt maken van deze rechten.</p>";

// Contactgegevens
echo "<h2>7. Contactgegevens</h2>";
echo "<p>Als je vragen hebt over ons privacybeleid of hoe we je gegevens verwerken, neem dan contact met ons op via:</p>";
echo "<ul>";
echo "<li>E-mail: privacy@jouwwebsite.com</li>";
echo "<li>Adres: Jouw Straatnaam 123, 1000 AB, Stad</li>";
echo "</ul>";

echo "<p>We behouden ons het recht voor om dit privacybeleid op elk moment te wijzigen. Eventuele wijzigingen worden op deze pagina gepubliceerd.</p>";
?>
  message {
    background-color: white;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.message p {
    font-size: 1rem;
    margin: 0.5rem 0;
}

.likes {
    margin-top: 1rem;
    font-size: 1rem;
    color: #333;
}

.likes a {
    color: var(--primary-color);
    text-decoration: none;
    padding: 0.5rem;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.likes a:hover {
    background-color: rgba(29, 161, 242, 0.1);
}

/* Verwijder knop */
.message a {
    color: #e0245e;
    text-decoration: none;
    margin-left: 1rem;
    font-weight: bold;
}

.message a:hover {
    text-decoration: underline;
}

/* Footer styling */
footer {
    text-align: center;
    padding: 1rem;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: auto;
}

footer a {
    color: var(--primary-color);
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}
<?php foreach ($messages as $message): ?>
        <div class="message">
            <p><?php echo htmlspecialchars($message['content']); ?></p>
            <p>Geplaatst door: <?php echo htmlspecialchars($message['gebruikersnaam']); ?></p>
            
            <div class="likes">
                <p>Likes: <?php echo $message['likes']; ?></p>
                <a href="berichten.php?like=<?php echo $message['id']; ?>">Like</a>

                
            </div>
             <div class="verwijderen">
                <p>verwijderen: <?php echo $message['verwijderen']; ?></p>
                <a href="berichten.php?verwijderen=<?php echo $message['id']; ?>">verwijderen</a>
    </div>



    <?php
session_start();
require 'database/database.php';
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// CSRF-token genereren
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Bericht plaatsen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Ongeldige CSRF-token.";
    } else {
        $content = trim($_POST['content']);
        
        if (!empty($content)) {
            // Maak de inhoud veilig tegen XSS
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            
            // Plaats het bericht in de database met 0 likes
            $stmt = $conn->prepare("INSERT INTO messages (user_id, content, likes) VALUES (?, ?, 0)");
            $stmt->execute([$user_id, $content]);
            
            $success_message = "Je bericht is geplaatst!";
        } else {
            $error_message = "Bericht mag niet leeg zijn.";
        }
    }
}

// Bericht verwijderen
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $message_id = $_GET['delete'];
    
    $stmt = $conn->prepare("SELECT user_id FROM messages WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch();
    
    if ($message && $message['user_id'] == $user_id) {
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$message_id]);
        
        $success_message = "Je bericht is verwijderd.";
    } else {
        $error_message = "Je mag alleen je eigen berichten verwijderen.";
    }
}

// Bericht liken
if (isset($_GET['like']) && is_numeric($_GET['like'])) {
    $message_id = $_GET['like'];
    
    // Verhoog het aantal likes met 1
    $stmt = $conn->prepare("UPDATE messages SET likes = likes + 1 WHERE id = ?");
    $stmt->execute([$message_id]);
    
    $success_message = "Je hebt het bericht geliked!";
}

// Haal gebruikersnaam op
$stmt = $conn->prepare("SELECT gebruikersnaam FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();


$stmt = $conn->prepare("
    SELECT 
        m.id, 
        m.content, 
        m.user_id, 
        m.likes,
        u.gebruikersnaam
    FROM 
        messages m
    JOIN 
        users u ON m.user_id = u.id
    ORDER BY 
        m.id DESC
");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berichten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Berichten</h1>
    
