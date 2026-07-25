<?php

namespace App\DataFixtures;

use Adambean\Bundle\RandflakeIdBundle\Service\RandflakeIdService;
use App\Entity\ExampleRandflakeIdEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly RandflakeIdService $randflakeIdService,
    ) {}

    public function load(ObjectManager $em): void
    {
        /** @var ArrayCollection<int, ExampleRandflakeIdEntity> $entities */
        $entities = new ArrayCollection();

        for ($i = 1; $i <= 10; $i++) {
            $entity = new ExampleRandflakeIdEntity();
            $entity
                ->setName(sprintf("Example entity %d", $i))
                ->setExampleDefault($this->randflakeIdService->generate())
                ->setExampleExplicitDd($this->randflakeIdService->generate())
                ->setExampleExplicitDe($this->randflakeIdService->generate())
                ->setExampleExplicitEd($this->randflakeIdService->generate())
                ->setExampleExplicitEe($this->randflakeIdService->generate())
            ;

            $em->persist($entity);
            $entities->add($entity);

            if (0 === $i % 3) {
                sleep(random_int(1, 3));
            }
        }

        $em->flush();

        foreach ($entities as $i => $entity) {
            $e = $i + 1;

            $em->refresh($entity);

            $id = $entity->getId();
            if (null === $id) {
                throw new \RuntimeException(sprintf("Entity %d ID is null after flush.", $e));
            }

            $details = $this->randflakeIdService->inspect($id);
            printf(
                "Created entity %d with ID: %s (timestamp: %d, timestampUtc: %d, node: %d, sequence: %d)\n",
                $e,
                $id,
                $details["timestamp"],
                $details["timestampUtc"],
                $details["nodeId"],
                $details["sequence"]
            );
        }

        printf("NB: Sequence will increment by 6 each time due to the 6 IDs generated per entity.\n");
    }
}
