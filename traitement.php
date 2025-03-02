<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : NULL;
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $temps_utilisation = htmlspecialchars($_POST['temps_utilisation']);
    $compteur = isset($_POST['compteur']) ? htmlspecialchars($_POST['compteur']) : NULL;
    $arrondissement = htmlspecialchars($_POST['arrondissement']);
    $quartier = htmlspecialchars($_POST['quartier']);
    $date_probleme = $_POST['date'];
    $qualite_electricite = $_POST['qualite_electricite'];
    $coupures = $_POST['coupures'];
    $qualite_eau = $_POST['qualite_eau'];
    $appareils_endommages = isset($_POST['appareils_endommages']) ? implode(',', $_POST['appareils_endommages']) : NULL;
    $quantite_appareils_endommages = $_POST['quantite_appareils_endommages'];
    $commentaires = isset($_POST['commentaires']) ? htmlspecialchars($_POST['commentaires']) : NULL;

    // Gestion des fichiers téléversés
    $images_appareil = [];
    if (!empty($_FILES['images_appareil']['name'][0])) {
        foreach ($_FILES['images_appareil']['tmp_name'] as $index => $tmpName) {
            $fileName = uniqid() . "-" . $_FILES['images_appareil']['name'][$index];
            move_uploaded_file($tmpName, "uploads/" . $fileName);
            $images_appareil[] = $fileName;
        }
    }
    $images_appareil = !empty($images_appareil) ? implode(',', $images_appareil) : NULL;

    $documents = [];
    if (!empty($_FILES['documents']['name'][0])) {
        foreach ($_FILES['documents']['tmp_name'] as $index => $tmpName) {
            $fileName = uniqid() . "-" . $_FILES['documents']['name'][$index];
            move_uploaded_file($tmpName, "uploads/" . $fileName);
            $documents[] = $fileName;
        }
    }
    $documents = !empty($documents) ? implode(',', $documents) : NULL;

    // Insertion dans la base de données
    $sql = "INSERT INTO reponses (nom, email, telephone, temps_utilisation, compteur, arrondissement, quartier, date_probleme, qualite_electricite, coupures, qualite_eau, appareils_endommages, quantite_appareils_endommages, images_appareil, documents, commentaires)
            VALUES (:nom, :email, :telephone, :temps_utilisation, :compteur, :arrondissement, :quartier, :date_probleme, :qualite_electricite, :coupures, :qualite_eau, :appareils_endommages, :quantite_appareils_endommages, :images_appareil, :documents, :commentaires)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':telephone' => $telephone,
        ':temps_utilisation' => $temps_utilisation,
        ':compteur' => $compteur,
        ':arrondissement' => $arrondissement,
        ':quartier' => $quartier,
        ':date_probleme' => $date_probleme,
        ':qualite_electricite' => $qualite_electricite,
        ':coupures' => $coupures,
        ':qualite_eau' => $qualite_eau,
        ':appareils_endommages' => $appareils_endommages,
        ':quantite_appareils_endommages' => $quantite_appareils_endommages,
        ':images_appareil' => $images_appareil,
        ':documents' => $documents,
        ':commentaires' => $commentaires
    ]);

    // Redirection après soumission
    header("Location: merci.html");
    exit();
}
?>
