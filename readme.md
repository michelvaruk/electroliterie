# Conception de Must Ménager

## La nav
Il y aura une partie produits qui ne présente pour le moment que la partie "literie", mais qui accueillera tout l'électroménager par la suite

Dans la partie literie, on aura :
- les matelas
- les sommiers
- ensembles literie
- le linge de lit
- couettes / oreillers

En dehors de cette nav, on retrouvera les pages suivantes :
- Qui sommes-nous (mettre dans la page d'accueil dans un premier temps)
- Contact
- panier (cf https://www.hypnia.fr)

## Les éléments en dur
On met un max d'éléments en dur pour simplifier l'utilisation du site (nav en dur par ex)

## La table produit - fait
- Titre (string)
- EAN (string)
- Référence (string)
- Marque (collection)
- Photos (collection)
- Description (text)

## La table statut produit - fait
- Prix de vente HT (int en cts)
- Prix d'achat HT (int en cts)
- Éco-participation (int en cts)
- Remise HT (int en cts)
<!-- - TVA (int en cts) -->
- Stock (int)
- Actif (bool)

## La table Promo - fait
- Titre (string)
- Valeur (int)
- Relative ? (bool)
- Produit (collection)
- Date de début (Datetime)
- Date de fin (Datetime)
- Image

## Photo de Produit - fait
- produit (collection)
- alt (string)
- type [
      0 => 'thumbnail'
      1 => 'gallerie'
  ]

## Marque
- titre (string)
- logo

## Matelas - fait
- produit (collection)
- type [
      0 => Mousse
      1 => latex
      2 => ressorts
      3 => ressorts ensachés
  ]
- fermeté [
      0 => très ferme
      1 => ferme
      2 => équilibré
      3 => moelleux
      4 => individualisé
  ]
- largeur (int en mm)
- longueur (int en mm)
- épaisseur (int en mm)

## Sommier - fait
- produit (collection)
- type [
      0 => lattes
      1 => tapissier
      2 => lit-coffre
  ]
- largeur (int en mm)
- longueur (int en mm)
- hauteur (int en mm)
- couleur (collection)
- en 1 partie ? (bool)

## Customer 
- Nom
- Prénom
- email
- Adresse de facturation (adr, comp. adr, code postal, ville, téléphone) -
- Adresse de livraison (adr, comp. adr, code postal, ville, téléphone) -
- mot de passe

## Invoice
- customer
- invoiceLine - 
- date
- delivery - 
- commantaire
- avoir ? (bool)

## InvoiceLine
- product
- qty
- price HT
- discount
- VAT

## Delivery
- invoice
- status [
      0 => commandé
      1 => livré
      2 => avarie
  ]
- shippingDate
- commentaire

## Inspiration
Le client adore Ubaldi => se référer au style du site
On peut voir égalempent comment est construite la nav de meubles.fr
autres sites de literie
- https://www.leroidumatelas.fr -> voir les étiquettes produits de ce site

