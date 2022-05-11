#Exo Jules (Vous devez avoir fini l'autre feuille d'exo avant d'attaquer celui ci)

- Créer une commande qui permet de donner le ROLE_ADMIN à un utilisateur par son email : 
  - Exemple : symfony console app:set-admin machin@gmail.com

- Créer une commande qui va supprimer les messages du forum de la veille si ils contiennent des mots bannis ! 
  - Mot à tester : ['Pokemon', 'Digimon', 'Barbie', 'FromSoftSuck', 'UbisoftTheBest', 'BethesdaUnderatted']
  - Ajouter une propriété nbBanWord (int) à votre entitée Account qui de base est à 0
    - Des qu'un message est supprimé pour cause de mot bannis, augmenté la valeure de nbBanWord de 1
    - Sur la Home de votre site, si l'utilisateur est connecté, checker si il a + de 5 nbBanWord, si c'est la cas Affichez lui un gros Message dans un bandeau rouge qui dit : "PAS BIEN"


***


- Ajouter des filtres de recherches sur toutes vos liste de crud (venez me voir si vous voyez vraiment pas comment va fonctionner l'exemple ce dessous) : 
       
       $qb = $this->testRepository->getQbAll();

        $form = $this->createForm(MonFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $qb = $this->testRepo->updateQbByData($qb, $form->getData());
        }
        
        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );
- Fates des filtres sur tous les champs possible !


***

- Sur la page qui liste tous vos Forum :
  - ajoutez un lien vers le forum top tendance ! (Le forum qui a le plus message depuis une semaine jusqu'a ajd)
  - ajoutez un lien vers le forum gold ! (le forum qui a le plus de message overall)
  - ajoutez un lien vers le forum needAttention ! (le forum qui a le moin de message)
  - ajoutez en information l'utilisateur le plus actif (l'utilisateur qui a posté le plus de message depuis une semaine jusqu'a ajd)


***


- Ajouter un système de messagerie entre les utilisateur : 
  - Créer une entitée DirectMessage : 
    - createdBy: relation vers Account
    - receiver: relation vers Account
    - content: text
    - createdAt: datetime
    - hasBeenSeen: boolean (default false)
  - Ajouter une interface Messagerie à votre appli uniquement accessible si l'utilisateur est connecté
    - Des que vous arrivez dessus vous allez retrouver tous les messages que vous envoyé ou que vous avez recu.
    - Vous allez retrouver (avant tous message recu et envoyé) un forumaire qui va :
      - contenir deux champ : 
        - A qui voulez vous envoyé ce message ? (l'email de l'utilisateur a qui envoyé le message)
        - Contenu du message.
      - Etre un formType PAS basé sur une entitée
        - A vous avec votre algo de créer une entitée DirecteMessage lors de la soumission de votre forulaire (Attention bien sur ne pas envoyé le message si l'email renseigné n'existe pas !)
  - Dans votre navBar si l'utilisateur est connecté, créer un filtre Twig qui va affiché le nombre de message non vu : X Nouveau messages !
        


      
***


***

POUR LES ACHARNé si trop en avance ! 
- Regardez cette doc : https://symfony.com/doc/current/controller/upload_file.html
- Le but va etre de rajouter un avatar à notre User
- L'avatar devra etre une vrai image stocké sur notre serveur
- Refléchissez au système de comment mettre un upload d'avatar en place !