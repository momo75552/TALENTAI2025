<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    function clean_input($data) {
        return htmlspecialchars(trim($data));
    }

    $firstName = clean_input($_POST['firstName'] ?? '');
    $lastName = clean_input($_POST['lastName'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $subject = clean_input($_POST['subject'] ?? 'Sans sujet');
    $company = clean_input($_POST['company'] ?? '');
    $message = clean_input($_POST['message'] ?? '');
    $honeypot = trim($_POST['website'] ?? ''); // champ invisible (anti-bot)

    // Anti-spam (honeypot)
    if (!empty($honeypot)) {
        exit("<p style='color:red;'>Spam détecté. Message non envoyé.</p>");
    }

    // Validation des champs
    $errors = [];

    if (empty($firstName)) $errors[] = "Le prénom est requis.";
    if (empty($lastName)) $errors[] = "Le nom est requis.";
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($message)) {
        $errors[] = "Le message est requis.";
    } elseif (strlen($message) > 1000) {
        $errors[] = "Le message est trop long (1000 caractères max).";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit;
    }

    // Construction du mail
    $to = "talentai2025@gmail.com";
    $fullSubject = "Message de contact - PlurineurAI: " . $subject;

    $body = "
        <h2>Message de Contact</h2>
        <p><strong>Prénom:</strong> $firstName</p>
        <p><strong>Nom:</strong> $lastName</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Entreprise:</strong> $company</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    ";

    // En-têtes du mail
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: contact@tondomaine.com\r\n"; // change à une adresse valide liée à ton nom de domaine
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Envoi
    if (mail($to, $fullSubject, $body, $headers)) {
        echo "<p style='color:green;'>Votre message a été envoyé avec succès !</p>";
    } else {
        echo "<p style='color:red;'>Erreur lors de l'envoi du message. Veuillez réessayer plus tard.</p>";
    }

    exit;
}
?>
