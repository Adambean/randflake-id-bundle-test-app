<?php

declare(strict_types=1);

namespace App\Repository;

use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitDdType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitDeType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitEdType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitEeType;
use Adambean\Bundle\RandflakeIdBundle\Repository\RandflakeIdServiceEntityRepository;
use Adambean\Bundle\RandflakeIdBundle\Service\RandflakeIdService;
use App\Entity\ExampleRandflakeIdEntity;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Example Randflake ID entity repository.
 *
 * @extends RandflakeIdServiceEntityRepository<ExampleRandflakeIdEntity>
 */
class ExampleRandflakeIdRepository extends RandflakeIdServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExampleRandflakeIdEntity::class);
    }

    /**
     * Find one entity by a specific Randflake ID property.
     *
     * @param non-empty-string $property The property name to search by.
     * @param non-empty-string $value The value to search for.
     *
     * @throws \InvalidArgumentException
     * If the property name is empty or does not exist on the entity, or if the value is empty or invalid.
     */
    public function findOneByProperty(string $property, string $value): ?ExampleRandflakeIdEntity
    {
        if ("" === ($property = trim($property))) {
            throw new \InvalidArgumentException("Property name cannot be empty.");
        }

        if (!property_exists(ExampleRandflakeIdEntity::class, $property)) {
            throw new \InvalidArgumentException(sprintf(
                "Property \"%s\" does not exist on entity \"%s\".", $property,
                ExampleRandflakeIdEntity::class
            ));
        }

        if ("" === ($value = trim($value))) {
            throw new \InvalidArgumentException("Value cannot be empty.");
        }

        RandflakeIdService::assertValidId($value);

        $qb = $this->createQueryBuilder("e")
            ->andWhere(sprintf("e.%s = :value", $property))
        ;

        $this->setTypedParameter($qb, "value", $value, $property);

        return $this->normalizeEntityResult($qb->getQuery()->getOneOrNullResult());
    }

    /**
     * Find one entity by the four explicit Randflake ID properties.
     * All four property values must match to find the entity.
     *
     * @param non-empty-string $dd Decrypted and decoded value.
     * @param non-empty-string $de Decrypted and encoded value.
     * @param non-empty-string $ed Encrypted and decoded value.
     * @param non-empty-string $ee Encrypted and encoded value.
     *
     * @throws \InvalidArgumentException
     * If any of the property values are empty or invalid.
     */
    public function findOneByExampleExplicitProperties(
        string $dd,
        string $de,
        string $ed,
        string $ee,
    ): ?ExampleRandflakeIdEntity {
        foreach ([
            "dd" => $dd,
            "de" => $de,
            "ed" => $ed,
            "ee" => $ee,
        ] as $field => &$value) {
            if ("" === ($value = trim($value))) {
                throw new \InvalidArgumentException(sprintf("Value for field \"%s\" cannot be empty.", $field));
            }

            RandflakeIdService::assertValidId($value);
        }

        $result = $this->createQueryBuilder("ee")
            ->andWhere("ee.exampleExplicitDd = :exampleExplicitDd")
            ->setParameter("exampleExplicitDd", $dd, RandflakeIdExplicitDdType::NAME)
            ->andWhere("ee.exampleExplicitDe = :exampleExplicitDe")
            ->setParameter("exampleExplicitDe", $de, RandflakeIdExplicitDeType::NAME)
            ->andWhere("ee.exampleExplicitEd = :exampleExplicitEd")
            ->setParameter("exampleExplicitEd", $ed, RandflakeIdExplicitEdType::NAME)
            ->andWhere("ee.exampleExplicitEe = :exampleExplicitEe")
            ->setParameter("exampleExplicitEe", $ee, RandflakeIdExplicitEeType::NAME)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $this->normalizeEntityResult($result);
    }
}
