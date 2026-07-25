<?php

declare(strict_types=1);

namespace App\Entity;

use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitDdType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitDeType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitEdType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdExplicitEeType;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdGenerator;
use Adambean\Bundle\RandflakeIdBundle\Doctrine\RandflakeIdType;
use Adambean\Bundle\RandflakeIdBundle\Validator\Constraints\RandflakeIdConstraint;
use App\Repository\ExampleRandflakeIdRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExampleRandflakeIdRepository::class)]
#[ORM\Table(name: "example_randflake_id_entity", indexes: [
    new ORM\Index(name: "example_default_idx", columns: [
        "example_default",
    ]),
    new ORM\Index(name: "example_explicit_fields_idx", columns: [
        "example_explicit_dd",
        "example_explicit_de",
        "example_explicit_ed",
        "example_explicit_ee",
    ])
])]
#[UniqueEntity(fields: ["id"])]
class ExampleRandflakeIdEntity
{
    /*
     * -------------------------------------------------------------------------
     * Local functions.
     * -------------------------------------------------------------------------
     */

    /**
     * Identifier, will use the encryption and encoding options from service configuration.
     * @var numeric-string|null $id
     */
    #[ORM\Id]
    #[ORM\Column(name: "id", type: RandflakeIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: RandflakeIdGenerator::class)]
    #[RandflakeIdConstraint]
    protected ?string $id = null;

    /**
     * Name field, just to have something else here.
     */
    #[ORM\Column(name: "name", type: "string", length: 200, nullable: true)]
    #[Assert\Type("string")]
    protected ?string $name = null;

    /**
     * Example of default options, will use the encryption and encoding options from service configuration.
     * @var numeric-string|null $exampleDefault
     */
    #[ORM\Column(name: "example_default", type: RandflakeIdType::NAME, nullable: true)]
    #[RandflakeIdConstraint]
    protected ?string $exampleDefault = null;

    /**
     * Example of explicit options: decrypted, decoded.
     * @var numeric-string|null $exampleExplicitDd
     */
    #[ORM\Column(
        name: "example_explicit_dd",
        type: RandflakeIdExplicitDdType::NAME,
        nullable: true,
    )]
    #[RandflakeIdConstraint(RandflakeIdConstraint::FORMAT_UNENCODED)]
    protected ?string $exampleExplicitDd = null;

    /**
     * Example of explicit options: decrypted, encoded.
     * @var numeric-string|null $exampleExplicitDe
     */
    #[ORM\Column(
        name: "example_explicit_de",
        type: RandflakeIdExplicitDeType::NAME,
        nullable: true,
    )]
    #[RandflakeIdConstraint(RandflakeIdConstraint::FORMAT_ENCODED)]
    protected ?string $exampleExplicitDe = null;

    /**
     * Example of explicit options: encrypted, decoded.
     * @var numeric-string|null $exampleExplicitEd
     */
    #[ORM\Column(
        name: "example_explicit_ed",
        type: RandflakeIdExplicitEdType::NAME,
        nullable: true,
    )]
    #[RandflakeIdConstraint(RandflakeIdConstraint::FORMAT_UNENCODED)]
    protected ?string $exampleExplicitEd = null;

    /**
     * Example of explicit options: encrypted, encoded.
     * @var numeric-string|null $exampleExplicitEe
     */
    #[ORM\Column(
        name: "example_explicit_ee",
        type: RandflakeIdExplicitEeType::NAME,
        nullable: true,
    )]
    #[RandflakeIdConstraint(RandflakeIdConstraint::FORMAT_ENCODED)]
    protected ?string $exampleExplicitEe = null;



    /*
     * -------------------------------------------------------------------------
     * Data functions.
     * -------------------------------------------------------------------------
     */

    /**
     * @return numeric-string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return numeric-string|null
     */
    public function getExampleDefault(): ?string
    {
        return $this->exampleDefault;
    }

    /**
     * @param numeric-string|null $exampleDefault
     */
    public function setExampleDefault(?string $exampleDefault): self
    {
        $this->exampleDefault = $exampleDefault;

        return $this;
    }

    /**
     * @return numeric-string|null
     */
    public function getExampleExplicitDd(): ?string
    {
        return $this->exampleExplicitDd;
    }

    /**
     * @param numeric-string|null $exampleExplicitDd
     */
    public function setExampleExplicitDd(?string $exampleExplicitDd): self
    {
        $this->exampleExplicitDd = $exampleExplicitDd;

        return $this;
    }

    /**
     * @return numeric-string|null
     */
    public function getExampleExplicitDe(): ?string
    {
        return $this->exampleExplicitDe;
    }

    /**
     * @param numeric-string|null $exampleExplicitDe
     */
    public function setExampleExplicitDe(?string $exampleExplicitDe): self
    {
        $this->exampleExplicitDe = $exampleExplicitDe;

        return $this;
    }

    /**
     * @return numeric-string|null
     */
    public function getExampleExplicitEd(): ?string
    {
        return $this->exampleExplicitEd;
    }

    /**
     * @param numeric-string|null $exampleExplicitEd
     */
    public function setExampleExplicitEd(?string $exampleExplicitEd): self
    {
        $this->exampleExplicitEd = $exampleExplicitEd;

        return $this;
    }

    /**
     * @return numeric-string|null
     */
    public function getExampleExplicitEe(): ?string
    {
        return $this->exampleExplicitEe;
    }

    /**
     * @param numeric-string|null $exampleExplicitEe
     */
    public function setExampleExplicitEe(?string $exampleExplicitEe): self
    {
        $this->exampleExplicitEe = $exampleExplicitEe;

        return $this;
    }
}
