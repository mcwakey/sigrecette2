I. Présentation de l’API
L’API SMSVAS est une api permettant l’envoie d’un sms à une ou plusieurs personnes par les
opérateurs MOOV et TOGOCOM. Elle a été développée par ITplexconsult et utilisé par l’application
web SMSVAS. Pour avoir accès à l’API, il vous faut créer un compte sur l’application web SMSVAS et
ensuite procéder à l’achat d’un token qui donne l’accès à l’api, ou simplement contacter la direction
de SMSVAS au numéro 228 90 99 21 77 ou via l’adresse itplex.consult@yahoo.fr.
II. Structure de l’API
L’url (EndPoint) de l’api se présente comme suit :
http://smsvas.fr/api/sms
Les paramètres (PayLoad)
• token : (obligatoire) Il s’agit du token acheté sur la plateforme SMSVAS ou obtenu via un
administrateur
• to : (obligatoire) Il s’agit du(des) numéros du(des) destinataire(s). En cas de plusieurs
destinataires, il faut séparer les numéros d’un espace ou des caractères « %20 ». Tous les
numéros doivent êtres précédés de leurs indicatifs (228 ou 00228)
• text : (obligatoire) Il s’agit du contenu de votre sms. Chaque sms prend en compte 160
caractère. Lorsque le texte dépasse les 160 caractères, l’api la découpe automatique
automatiquement en plusieurs sms.
• from : (facultatif) Il s’agit de l’entête de votre sms. Par défaut, il prend la valeur indiquée lors
de l’achat du token.
• dlr_url : (facultatif) Lien par lequel vous obtenez un rapport de livraison de votre sms
• validity : (facultatif) C’est la durée (en minute) pendant laquelle votre sms reste valide. Une
fois ce délai passé, le sms devient obsolète et ne sera plus livré.
• deffered : (facultatif) C’est le temps (en minute) qui dois s’écouler avant l’envoi du sms.
III. Retour de l’exécution de l’api
Après l’exécution de l’api, les retours possibles sont sous la forme :
{
‘’Status’’ :
‘’Description’’ :
‘’Incorect_number’’:
‘’Sent_sms’’:
“Remaining_sms’’ :
}
ITplexconsult
2
LYCEE AGOE 01 Lomé 1275
Lomé 01 - Togo
Explication du retour de l’api
• Status : il indique l’état de l’exécution de l’api. Ces valeurs possibles sont :
o 0 : Token invalide ;
o 111 : Succès ;
o 112 : Succès, mais quelques numéros incorrects ;
o 222 : tous les numéros entrés sont incorrects.
o 333: Un paramètre est manquant;
o 555 : Nombre de sms restant insuffisant ;
o 888 : Le message envoyé est trop long.
• Descriptin: Il décrit le statut
• Incorect_number : il indique les numéros incorrects. S’il n’y a pas de numéro incorrect, ce
champ est caché.
• Remaining_sms : Indique le nombre de sms restant
IV. Obtention du DLR
Pour obtenir un dlr, il faut renseigner une url au niveau du paramètre dlr_url. Le rapport de livraison
vous permet d’avoir des informations sur le statut de livraison des sms. L’url fournit doit contenir :
• St: qui sera remplacé par 1 si le sms a bien été livré ou remplacé par 2 si l’envoi a échoué.
• To : qui sera remplacé par le numéro de réception du sms
• SmsID : qui sera remplacé par l’identifiant du sms envoyé
• T : qui sera remplacé par la date et l’heure d’envoi du sms.
Tout autre paramètre inséré dans l'url par l'utilisateur lui sera retourné tel quel. L'utilisateur peut
donc insérer un paramètre "msgID" propre à son système pour se retrouver lors du rapport de
livraison.
Si le message doit être envoyé à mille personnes, le dlr-url sera exécuté mille fois indiquant le
rapport de livraison par rapport à ces mille personnes.
Exemple de lien du dlr
http://votresite/retourapi?status=St&to=To&ID=SmsID&time=T
V. Exemple d’envoi de sms
Exemple 1
Nous envoyons un sms en procédant comme suit :
http://smsvas.fr/api/sms?token=jbbgGvGVCGYygUhuuyGtdrXyt4919uyuygyUG&to=22891087733&te
xt=Bonjour&from=Toto
Le numéro 22891087733 recevra un sms dont le contenu sera ‘’Bonjour’’ et l’entête du sms sera
‘’Toto’’
L’api retournera le message suivant :
{
‘’Status’’ : 111
‘’Description’’ : Success
ITplexconsult
3
LYCEE AGOE 01 Lomé 1275
Lomé 01 - Togo
‘’Sent_sms’’: 1
“Remaining_sms’’ : XXX
}
Avec XXX le nombre d’sms restant
Exemple 2
Nous envoyons un sms en procédant comme suit :
http://smsvas.fr/api/sms?token=jbbgGvGVCGYygUhuuyGtdrXyt4919uyuygyUG&to=22893789654%2
096116197&text=Bonjour&from=Toto
Deux numéros sont indiqués dans l’url mais seulement le numéro 22893789654 recevra le sms car le
second numéro est incorrect (il n’est pas précédé de l’indicatif)
L’api retournera le message suivant :
{
‘’Status’’ : 112
‘’Description’’ : Success
‘’Incorect_number’’: 96116197’’
‘’Sent_sms’’: 1
“Remaining_sms’’ : XXX
}
Avec XXX le nombre d’sms restant et Incorect_number étant la liste des numéros incorrects.