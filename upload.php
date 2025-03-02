<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charger la configuration
require 'config.php';

// Charger PHPMailer
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Récupération et sécurisation des données du formulaire
        $nom = !empty($_POST['nom']) ? htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8') : 'Non précisé';
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $temps_utilisation = htmlspecialchars($_POST['temps_utilisation'], ENT_QUOTES, 'UTF-8');
        $arrondissement = htmlspecialchars($_POST['arrondissement'], ENT_QUOTES, 'UTF-8');
        $quartier = htmlspecialchars($_POST['quartier'], ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
        $qualite_electricite = htmlspecialchars($_POST['qualite_electricite'], ENT_QUOTES, 'UTF-8');
        $coupures = htmlspecialchars($_POST['coupures'], ENT_QUOTES, 'UTF-8');
        $qualite_eau = htmlspecialchars($_POST['qualite_eau'], ENT_QUOTES, 'UTF-8');
        $appareils_endommages = isset($_POST['appareils_endommages']) ? implode(', ', array_map('htmlspecialchars', $_POST['appareils_endommages'])) : 'Aucun';
        $quantite_appareils_endommages = intval($_POST['quantite_appareils_endommages']);
        $commentaires = htmlspecialchars($_POST['commentaires'], ENT_QUOTES, 'UTF-8');

        // Dossier de stockage des fichiers
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Gestion des fichiers images
        $images = [];
        if (!empty($_FILES['images_appareil']['name'][0])) {
            foreach ($_FILES['images_appareil']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images_appareil']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . basename($_FILES['images_appareil']['name'][$key]);
                    $filePath = $uploadDir . $fileName;
                    move_uploaded_file($tmp_name, $filePath);
                    $images[] = $filePath;
                }
            }
        }
        $images_appareil = !empty($images) ? implode(',', $images) : NULL;

        // Gestion des fichiers documents
        $docs = [];
        if (!empty($_FILES['documents']['name'][0])) {
            foreach ($_FILES['documents']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . basename($_FILES['documents']['name'][$key]);
                    $filePath = $uploadDir . $fileName;
                    move_uploaded_file($tmp_name, $filePath);
                    $docs[] = $filePath;
                }
            }
        }
        $documents = !empty($docs) ? implode(',', $docs) : NULL;

        // Insérer les données dans la base de données
        $stmt = $pdo->prepare("INSERT INTO reponses (nom, email, telephone, temps_utilisation, arrondissement, quartier, date_probleme, qualite_electricite, coupures, qualite_eau, appareils_endommages, quantite_appareils_endommages, images_appareil, documents, commentaires) 
                                VALUES (:nom, :email, :telephone, :temps_utilisation, :arrondissement, :quartier, :date, :qualite_electricite, :coupures, :qualite_eau, :appareils_endommages, :quantite_appareils_endommages, :images_appareil, :documents, :commentaires)");

        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':temps_utilisation' => $temps_utilisation,
            ':arrondissement' => $arrondissement,
            ':quartier' => $quartier,
            ':date' => $date,
            ':qualite_electricite' => $qualite_electricite,
            ':coupures' => $coupures,
            ':qualite_eau' => $qualite_eau,
            ':appareils_endommages' => $appareils_endommages,
            ':quantite_appareils_endommages' => $quantite_appareils_endommages,
            ':images_appareil' => $images_appareil,
            ':documents' => $documents,
            ':commentaires' => $commentaires
        ]);

        // Envoi de l'email avec PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom(MAIL_USERNAME, 'ComNkongô'); 
        $mail->setFrom('lionelconcept1@gmail.com', 'Communication de Nkongô');


        // Sujet et contenu du mail
        $mail->isHTML(true);
        $mail->Subject = 'Sondage SEEG Port-Gentil - Réponse';
        $mail->Body = "<p>Un nouveau sondage a été soumis.</p>";

        // Ajout des pièces jointes
        foreach ($images as $image) {
            $mail->addAttachment($image);
        }
        foreach ($docs as $doc) {
            $mail->addAttachment($doc);
        }

        // Envoi du mail
        $mail->send();
        echo 'Message envoyé et données enregistrées avec succès !';
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
