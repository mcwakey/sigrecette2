Pré-requis
Ce API supporte les languages de programmes qui envoi les données par méthode POST.
Route	https://bestcom.tg/api/send-sms
Méthode	POST
Type de données	Brute dans un formulaire
Type de reponse	JSON


Les paramètres des variables
Libelle variable	Description	Type	Option
Api_Key	Clé de l'api généré	text	Obligatoire
Api_secret	Clé secret de l'api généré	text	Obligatoire
Contact	Numero de téléphone	text	Obligatoire
Titre	Titre de votre message accepté sur le tableau de bord	text	Obligatoire
Message	Titre de votre message	text	Obligatoire
Matricule	matricule pour vérification après envoi	text	Optionnel

Retours valeurs après envoi de messages de la variable info avec ces paramètres value et status
Valeur de value	Valeur de status
Message Envoye avec succes	1
Echec d'envoi de message	0
Titre non accepte	2
Solde insuffisant pour envoyer message	3
Vos clés ne sont pas acceptes pour envoi de message	4
N.B: Vous pouvez utiliser le système de curl pour faire l'intégration qui doit nécéssairement passé par la methode POST.
+ Cliquez sur ce lien pour en savoir plus pour PHP

                                    <?php

                                    // Données à envoyer
                                    $data = [
                                        'Api_Key'    => 'VOTRE_CLE_API',
                                        'Api_secret' => 'VOTRE_CLE_SECRET',
                                        'Contact'    => '22890000000', // Numéro au bon format
                                        'Message'    => 'Bonjour, ceci est un test depuis cURL en PHP.'
                                    ];

                                    // Initialisation de cURL
                                    $ch = curl_init();

                                    // URL de l'API (à remplacer par la bonne URL)
                                    $url = 'https://bestcom.tg/api/send-sms'; (Lien pour SMS)
                                    $url = 'https://bestcom.tg/api/send-sms-whatsapp'; (Lien pour whatsapp)
                                    Veuillez sélectionner l’un des liens ci-dessous en fonction du type d’API que vous souhaitez utiliser.

                                    // Configuration de la requête
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                    // Exécution
                                    $response = curl_exec($ch);

                                    // Gestion des erreurs
                                    if (curl_errno($ch)) {
                                        echo 'Erreur cURL : ' . curl_error($ch);
                                    } else {
                                        echo 'Réponse de l\'API : ' . $response;
                                    }

                                    // Fermeture
                                    curl_close($ch);
                                    ?>
                                    