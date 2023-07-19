<?php

namespace Project\UserInterface\Helpers;

use Illuminate\Support\Collection;

class CollectionHelper
{
    /**
     * @param Collection $entityCollection
     * @param string $propertyEntity
     * @return array
     */
    public static function convEntitiesToPropertyArray(Collection $entityCollection, string $camelProperty): array
    {
        $propertyValues = [];
        foreach ($entityCollection as $entity) {
            array_push($propertyValues, $entity->{'get' . ucfirst($camelProperty)}());
        }

        return $propertyValues;
    }

    public static function convEntitiesToEntityArrayByPropertyKey(Collection $entityCollection, string $camelProperty): array
    {
        $result = [];
        foreach ($entityCollection as $entity) {
            $result[$entity->{'get' . ucfirst($camelProperty)}()] = $entity;
        }

        return $result;
    }
}
