#Exo Jules

- dans votre base de template pour l'admin mettez en place une sideBar sur la gauche de l'écran qui va contenir des liens vers vos futurs CRUD


***


- Rappel d'un CRUD : 
    - Un CRUD est basé sur une entitée.
    - Une page liste qui va contenir un tableau qui contient tous vos éléments (avec pagination svp !)
        - La page va contenir : 
          - un boutn d'ajout.
          - chaque élément dans votre tableau va avoir un lien pour éditer ou supprimer votre entitée.
          - (-------A faire plus tard------ : une recherche)
    - Une page ajout qui va contenir :
      - Un formulaire
      - Un bouton pour revenir à la liste
    - Une page d'édition qui va contenir :
      - Un formulaire (le meme FormType que celui d'ajout)
      - Un bouton pour revenir à la liste
    - Une page de suppression qui est en réalité une page tampon, une fois l'entitée ou les entitées supprimées, faîtes une redirection vers la liste.


***


- Mettez en place les CRUDS pour votre appli (toutes les routes des CRUD commencent par la route /admin)
  - Commencez par celui des Genres ! (Vous ne pouvez pas ajouter de jeux dans cet interface)
  - Ensuite celui des Library
  - Terminer celui des Publisher
  - Et le plus dur pour la fin celui des jeux !
    - Sur le jeu Nous allons pouvoir :
      - choisir dans une liste déroulante l'éditeur
      - choisir dans des checkboxes le ou les pays
      - choisir dans des checkboxes le ou les genres

      
***

De retour sur le front : 
- Premierement, n'importe ou sur votre site trouvez comment en Twig affichez "coucou" si l'utilisateur est connecté
- Ensuite toujours en Twig dans votre navBar ajouter un lien vers l'interface d'admin si l'utilisateur a le role "ROLE_ADMIN"
- Ajouter à votre entitée Comment une propriété note ! qui va nous permettre de noter un jeu
- Dans la page d'un jeu, SI un utilisateur est connecté ET SI un utilisateur n'a pas deja mis un commentaire + note sur un jeu affichez un formulaire qui va permettre d'ajouter un Comment sur le jeu !
  - Le Comment va donc etre devoir relié au jeu en cours ET à l'utilisateur connecté !
- Ajoutez le lien se connecter à votre NavBar si l'utilisateur n'est pas connecté, sinon affichez le lien vers la route /logout
- Utilisez la commande symfony console make:registration-form (cette commande va vous générer un formulaire pour créer un utilisateur)
  - Examinez le code qui a été généré pour vous dans le "RegistrationController"
  - Pour l'instant le formulaire généré est inutilisable ! 
    - Donc rajouter les champs obligatoire au formulaire afin de pouvoir ajouter votre compte (regardez ce qu'il faut dans l'entitée Account)


***


- Ajouter tout un système de forum sur votre appli !
  - Créer 3 etntitées : 
    - Forum (seul l'admin à le droit de créer des forums => dans l'interface admin)
      - createdAt: datetime 
      - title: string
      - topics: relation vers Topic
    - Topic
      - createdAt: datetime
      - createdBy: relation vers Account
      - title: string
      - forum: relation vers Forum
      - messages : relation vers Message
    - Message
      - createdAt: datetime
      - createdBy: relation vers Account
      - content: text
      - topic: relation vers Topic
  - Prenez en exemple le forum de jeuxvideo.com et essayer de faire la même chose (au niveau de la navigation)
  - Penser à mettre en place pour un utilisateur de pouvoir modifier ou supprimer son message.