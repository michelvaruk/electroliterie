<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ChangePositionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setAllElmts($repository, ?string $parentEntityName = null, $parentElement = null, $generalFormationType = null, bool $flush = true)
    {
        if ($parentElement) {
            $allElmts = $repository->findBy([$parentEntityName => $parentElement], ['position' => 'ASC']);
        } else if ($generalFormationType) {
            $allElmts = $repository->findBy(['type' => 'Specialité'], ['position' => 'ASC']);
            $allElmtsOtherType = $repository->findBy(['type' => 'Option'], ['position' => 'ASC']);
        } else {
            $allElmts = $repository->findBy([], ['position' => 'ASC']);
        }

        foreach ($allElmts as $key => $elmt) {
            $elmt->setPosition($key);
            // $this->entityManager->persist($elmt);
        }
        if (isset($allElmtsOtherType)) {
            foreach ($allElmtsOtherType as $key => $elmt) {
                $elmt->setPosition($key);
            }
        }

        if ($flush)
            $this->entityManager->flush();

        return $allElmts;
    }

    public function createHandle($elmt, $repository, ?string $parentEntityName = null, $parentElement = null, $generalFormationType = null)
    {

        if ($parentElement) {
            $lastPosition = $repository->findOneBy([$parentEntityName => $parentElement], ['position' => 'DESC']);
        } else if ($generalFormationType) {
            $lastPosition = $repository->findOneBy(['type' => $generalFormationType], ['position' => 'DESC']);
        } else {
            $lastPosition = $repository->findOneBy([], ['position' => 'DESC']);
        }

        if ($lastPosition) {
            $elmt->setPosition($lastPosition->getPosition() + 1);
        } else {
            $elmt->setPosition(0);
        }
    }



    public function moveElement($element, $repository, int $direction, ?string $parentEntityName = null, $parentElement = null, $generalFormationType = null): array
    {
        $currentPosition = $element->getPosition();
        $targetPosition = $currentPosition + $direction;

        if ($parentElement) {
            $replacedElement = $repository->findOneBy([
                $parentEntityName => $parentElement,
                'position' => $targetPosition
            ]);
        } else if ($generalFormationType) {
            $replacedElement = $repository->findOneBy(['type' => $generalFormationType, 'position' => $targetPosition]);
        } else {
            $replacedElement = $repository->findOneBy(['position' => $targetPosition]);
        }

        if ($replacedElement) {
            $replacedElement->setPosition($currentPosition);
            $element->setPosition($targetPosition);

            $this->entityManager->flush();

            return [
                'type' => 'success',
                'message' => 'L\'ordre a été modifié avec succès.'
            ];
        } else {
            return [

                'type' => 'alert',
                'message' => 'Un problème est survenu, l\'ordre n\'a pas pu être modifié. Merci de ré-essayer.'
            ];
        }
    }

    public function createParentHandle($collection)
    {
        foreach ($collection as $key => $item) {
            $item->setPosition($key);
        }
        return $collection;
    }

    public function updateParentHandle($collection, $repository, $parentEntityName, $parentElement)
    {
        $collectionElementsFromForm = $collection;
        $collectionElementsFromDatabase = $repository->findBy([$parentEntityName => $parentElement], ['position' => 'ASC']);

        $collectionElementsFromFormToArray = $collectionElementsFromForm->toArray();

        // supprime les formations en trop de la liste de la base de donnée à partir de la liste du formulaire
        // retourne array formater  
        $formatedCollectionElements = array_filter($collectionElementsFromDatabase, function ($formation) use ($collectionElementsFromFormToArray) {
            return in_array($formation, $collectionElementsFromFormToArray);
        });

        // ajoute les nouveaux éléments (si getPosition null) au array formater
        foreach ($collectionElementsFromForm as $submittedFormation) {
            if ($submittedFormation->getPosition() === null) {
                $formatedCollectionElements[] = $submittedFormation;
            }
        }

        // sort les elements en fonction de leur position et positionne les elements sans position à la fin du tableau
        usort($formatedCollectionElements, function ($a, $b) {
            if ($a->getPosition() === null) return 1;
            if ($b->getPosition() === null) return -1;
            return $a->getPosition() - $b->getPosition();
        });

        // mets à jour la position de tout les éléments
        foreach ($formatedCollectionElements as $index => $formation) {
            $formation->setPosition($index);
        }

        return $formatedCollectionElements;
    }
}
