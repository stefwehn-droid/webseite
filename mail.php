<?php
// Fehleranzeige aktivieren
error_reporting(E_ALL); ini_set('display_errors', 1);
// PHPMailer einbinden
use PHPMailer\PHPMailer\PHPMailer; use PHPMailer\PHPMailer\Exception;
// Autoloader von Composer einbinden
require __DIR__ . '/vendor/autoload.php'; if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Eingabewerte aus dem Formular abrufen
    $name = trim($_POST["name"] ?? ''); $email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL); $message = trim($_POST["message"] ?? '');
    // Validierung: Alle Felder müssen ausgefüllt sein
    if (!$name || !$email || !$message) { exit("Bitte alle Felder korrekt ausfüllen.");
    }
    // Erstelle ein neues PHPMailer-Objekt
    $mail = new PHPMailer(true); try {
        //Servereinstellungen
        $mail->isSMTP(); // Verwende SMTP $mail->Host = 'smtp.ionos.de'; // SMTP-Server (für IONOS) $mail->SMTPAuth = true; $mail->Username = 'DEIN_EMAIL@stefan-wehner.de'; // Dein IONOS Email-Account $mail->Password = 'DEIN_PASSWORT'; // Dein 
        Passwort $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Verwende TLS $mail->Port = 587; // Port für SMTP (587 ist der übliche Port für TLS)
        // Absender und Empfänger
        $mail->setFrom('webmaster@stefan-wehner.de', 'Webseite Stefan Wehner'); $mail->addAddress('DEINE_EMAIL@deinedomain.de'); // Ziel-E-Mail-Adresse, an die die Nachricht gesendet wird
        // Betreff und Nachricht
        $mail->Subject = "Neue Nachricht von $name"; $mail->Body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";
        // E-Mail senden
        if ($mail->send()) { echo "✔️ Nachricht wurde erfolgreich gesendet!";
        } else {
            echo "❌ Fehler beim Senden der Nachricht.";
        }
    } catch (Exception $e) {
        // Fehlerbehandlung
        echo "❌ Fehler beim Senden der Nachricht: " . $mail->ErrorInfo;
    }
} else {
    echo "❗ Ungültiger Zugriff.";
}
?>
