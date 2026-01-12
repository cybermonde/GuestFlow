# GuestFlow
Accueil des invités par QR code

🎟️ Fluidifier l’accueil et sécuriser nos événements officiels

Lors de nos événements officiels, nous avions 3 objectifs :

➡️ faciliter le processus d’inscription,

➡️ renforcer la sécurité,

➡️ fluidifier l’accueil des invités.


Nous utilisons deux canaux d’invitation :

📬 le courrier postal, via un carton d’invitation,

📧 et la voie électronique, via l’e-mail.

Chaque invitation est désormais personnalisée et contient un code unique.
Sur le carton, un QR code permet aux invités de confirmer simplement leur présence : toutes les données sont déjà préremplies.
Dans l’e-mail, un lien unique renvoie vers le même formulaire.
Sur le plan technique, cette étape repose sur un formulaire (chez nous via le plugin WordPress Formidable, mais une solution comme Google Forms ou Framaforms fonctionnerait tout aussi bien). Une fois la demande envoyée, un e-mail automatique confirme la bonne réception.

Après validation de l’inscription, une étape importante pour nous, nous envoyons une confirmation manuelle, accompagnée d’un QR code généré à partir du code unique.

📅 Le jour J, les invités présentent leur carton ou leur e-mail… et c’est là que ➡️ GuestFlow ⬅️ entre en scène.

En reprenant mon ancien métier de développeur, j’ai conçu une application web légère qui permet de :

✔️ scanner les QR codes,

✔️ vérifier l’inscription et la confirmation dans la liste des invités,

✔️ suivre les statistiques de présence en temps réel.

Les doublons, codes inexistants ou erreurs sont immédiatement signalés, ce qui rend l’accueil plus fluide et plus fiable.

Bien entendu, un accueil spécifique reste prévu pour les situations non anticipées. 
