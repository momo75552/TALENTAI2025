<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $company = isset($_POST['company']) ? trim($_POST['company']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';

    // Anti-spam (honeypot)
    if (!empty($honeypot)) {
        exit("<p style='color:red;'>Spam détecté. Message non envoyé.</p>");
    }

    // Validation
    $errors = [];

    if (empty($firstName)) {
        $errors[] = "Le prénom est requis.";
    }
    if (empty($lastName)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($message)) {
        $errors[] = "Le message est requis.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit;
    }

    // Envoi de l'email
    $to = "talentai2025@gmail.com";
    $fullSubject = "Message de contact - PlurineurAI: " . $subject;
    $body = "
        <h2>Message de Contact</h2>
        <p><strong>Prénom:</strong> $firstName</p>
        <p><strong>Nom:</strong> $lastName</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Entreprise:</strong> $company</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $fullSubject, $body, $headers)) {
        echo "<p style='color:green;'>Votre message a été envoyé avec succès !</p>";
    } else {
        echo "<p style='color:red;'>Erreur lors de l'envoi du message.</p>";
    }

    exit;
}
?>
