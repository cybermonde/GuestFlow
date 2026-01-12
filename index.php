<?php
/* GuestFlow
 * Contrôle des invités à une réception
 * 
 * Le contrôle est basé sur la lecture d'un QR code
 * 
 * Ce QR code qui est aussi utilisé par l'invité pour s'inscrire contient de nombreuses informations.
 * On exploite ici uniquement le qr_unique
 * 
 * L'invité reçoit aussi une confirmation d'inscription par e-mail qui contient un QR code.
 * Ce QR code simplifié contient aussi le qr_unique
 * 
 * Le fichier reception.csv contient la liste des invités
 * qr unique | nom | prénom
 * 
 * Une création cybermonde.org
*/

// Fichier des invités
include 'includes/config.php';

// Fonction pour mettre à jour la présence
function updatePresence($file, $id) {
    $rows = [];
    $found = false;
    $alreadyPresent = false;
    $person = ["nom" => "", "prenom" => ""];
	// Normalisation du qr_unique : uniquement A-Z et 0-9
	$id = strtoupper($id);                 // force en majuscules
	$id = preg_replace('/[^A-Z0-9]/', '', $id);


    if (($handle = fopen($file, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if ($data[0] === $id) {
                $found = true;
                $person["nom"] = $data[1] ?? "";
                $person["prenom"] = $data[2] ?? "";

                if (isset($data[3]) && trim($data[3]) === "présent") {
                    $alreadyPresent = true;
                } else {
                    $data[3] = "présent";
                }
            }
            $rows[] = $data;
        }
        fclose($handle);
    }

    if ($found && !$alreadyPresent) {
        $handle = fopen($file, "w");
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

    if (!$found) return ["status" => "not_found"];
    if ($alreadyPresent) return ["status" => "already_present", "nom" => $person["nom"], "prenom" => $person["prenom"]];
    return ["status" => "success", "nom" => $person["nom"], "prenom" => $person["prenom"]];
}

if (isset($_POST['identifier'])) {
    $url = $_POST['identifier'];
    $parsedUrl = parse_url($url);
    parse_str($parsedUrl['query'] ?? '', $params);
    $id = $params['qr_unique'] ?? '';

   if ($id === '') {
    echo json_encode([
        "status" => "invalid",
        "scanned_url" => $url
    ]);
    exit;
	}


   $result = updatePresence($csvFile, $id);

// Enrichit la réponse avec les infos utiles
$result['scanned_url'] = $url;
$result['qr_unique'] = $id;

echo json_encode($result);
exit;

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuestFlow</title>
    <script src="js/html5-qrcode.js" type="text/javascript"></script>
    <link rel="stylesheet" href="includes/guestflow.css">
</head>
<body>
    <header>GuestFlow</header>

    <div id="reader"></div>
    <div id="message"></div>

    <footer>cybermonde.org - version 0.1 <a href="admin.php" title="Accès administration" class="admin-link">🔒</a></footer>

    <script>
        const messageBox = document.getElementById("message");
        let scanEnabled = true; // empêche le rescannage trop rapide

        function showMessage(text, cssClass, duration = 3000) {
            messageBox.textContent = text;
            messageBox.className = cssClass;
            setTimeout(() => {
                messageBox.textContent = "";
            }, duration);
        }

        function onScanSuccess(decodedText) {
            if (!scanEnabled) return; // ignore si un scan vient d’avoir lieu
            scanEnabled = false;

            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "identifier=" + encodeURIComponent(decodedText)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showMessage("✅ Présence enregistrée : " + data.prenom + " " + data.nom, "success", 3000);
                } else if (data.status === "already_present") {
                    showMessage("⚠️ Déjà scanné : " + data.prenom + " " + data.nom, "already", 3000);
                } else if (data.status === "not_found") {
				showMessage("❌ QR unique introuvable : " + data.qr_unique, "error", 3000);
			} else if (data.status === "invalid") {
				showMessage("❌ QR code invalide : " + data.scanned_url, "error", 3000);
			}

            })
            .catch(() => showMessage("❌ Erreur de communication.", "error"))
            .finally(() => {
                // Réactive le scan après 3 secondes
                setTimeout(() => { scanEnabled = true; }, 3000);
            });
        }

        const html5QrCode = new Html5Qrcode("reader");

        const isMobile = window.innerWidth < 600;
        const config = { fps: 10, qrbox: isMobile ? 200 : 300 };

        html5QrCode.start(
            { facingMode: { exact: "environment" } }, // Caméra arrière
            config,
            onScanSuccess
        ).catch(err => {
            console.error("Erreur caméra :", err);
            showMessage("⚠️ Impossible d’accéder à la caméra.", "error");
        });
    </script>
</body>
</html>
