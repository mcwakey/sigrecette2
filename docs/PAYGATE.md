Demander un paiement - Méthode 1

Pour initier une transaction, faites un simple appel HTTP de type Post vers le service web de PayGateGlobal et passer les paramètres requis.

Services: FLOOZ, TMONEY
URL 	https://paygateglobal.com/api/v1/pay
Methode 	HTTP Post
Format d'échange de données 	JSON

Le service web attend les paramètres suivants.
Nom 	Description 	Requis
auth_token 	Jeton d’authentification de l’e-commerce (Clé API) 	OUI
phone_number 	Numéro de téléphone mobile du Client 	OUI
amount 	Montant de la transaction sans la devise (Devise par défaut: FCFA) 	OUI
description 	Détails de la transaction 	NON
identifier 	Identifiant interne de la transaction de l’e-commerce. Cet identifiant doit etre unique. 	OUI
network 	valeurs possibles: FLOOZ, TMONEY 	OUI

Le service web renvoie la réponse suivante.
Nom 	Description
tx_reference 	Identifiant Unique générée par PayGateGlobal pour la transaction
status 	Code d’état de la transaction.

Les valeurs possible de la transaction sont:
0 : Transaction enregistrée avec succès
2 : Jeton d’authentification invalide
4 : Paramètres Invalides
6 : Doublons détectées. Une transaction avec le même identifiant existe déja.

================================================================================================

Vérifier l'état d'un Paiement

L’e-commerce peut à n’importe quel moment vérifier l'état d’une transaction en faisant appel au service web suivant.

Services: FLOOZ, T-Money
URL 	https://paygateglobal.com/api/v1/status
Methode 	HTTP Post
Format d'échange de données 	JSON

Le service web attend les paramètres suivants.
Nom 	Description 	Requis
auth_token 	Jeton d’authentification de l’e-commerce (Clé API) 	OUI
tx_reference 	Identifiant Unique précédemment généré par PayGateGlobal pour la transaction 	OUI

Le service web renvoie la réponse suivante.
Nom 	Description
tx_reference 	Identifiant Unique généré par PayGateGlobal pour la transaction
identifier 	Identifiant interne de la transaction de l’e-commerce. ex: Numero de commande Cet identifiant doit etre unique.
payment_reference 	Code de référence de paiement généré par Flooz/TMoney. Ce code peut être utilisé en cas de résolution de problèmes ou de plaintes.
status 	Code d’état du paiement.
datetime 	Date et Heure du paiement
payment_method 	Méthode de paiement utilisée par le client. Valeurs possibles: FLOOZ, T-Money

Méthode alternative pour verifier l'etat d'une transaction en utilisant l'identifiant unique de l'ecommerçant (ex: Numero de la commande).
URL 	https://paygateglobal.com/api/v2/status
Methode 	HTTP Post
Format d'échange de données 	JSON

Le service web attend les paramètres suivants.
Nom 	Description 	Requis
auth_token 	Jeton d’authentification de l’e-commerce (Clé API) 	OUI
identifier 	Identifiant Unique précédemment généré par l'Ecommerçant pour la transaction 	OUI

Le service web renvoie la réponse suivante.
Nom 	Description
tx_reference 	Identifiant Unique généré par PayGateGlobal pour la transaction
payment_reference 	Code de référence de paiement généré par Flooz/TMoney. Ce code peut être utilisé en cas de résolution de problèmes ou de plaintes.
status 	Code d’état du paiement.
datetime 	Date et Heure du paiement
payment_method 	Méthode de paiement utilisée par le client. Valeurs possibles: FLOOZ, T-Money

Les valeurs possibles de l’état de paiement sont:
0 : Paiement réussi avec succès 2 : En cours 4 : Expiré 6: Annulé

Les détails de transaction et état de paiement peuvent être visualisés sur le tableau de bord en ligne de l’e-commerce. 