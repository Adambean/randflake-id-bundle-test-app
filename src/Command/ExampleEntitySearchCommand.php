<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\ExampleRandflakeIdEntity;
use App\Repository\ExampleRandflakeIdRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "app:example-entity:search",
    description: "Example command to demonstrate usage of the RandflakeIdBundle: Search by one ID.",
)]
class ExampleEntitySearchCommand extends Command
{
    public function __construct(
        private readonly ExampleRandflakeIdRepository $exampleRandflakeIdRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument("id", InputArgument::REQUIRED, "Find entity by ID.")
            ->addOption(
                "property",
                null,
                InputArgument::OPTIONAL,
                "Optionally specify the property to search by (default is \"id\")."
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("This is an example command to demonstrate usage of the RandflakeIdBundle: Search by one ID.");

        $idArgument = $input->getArgument("id");
        if (!is_string($idArgument) || "" === ($id = strtolower(trim($idArgument)))) {
            $output->writeln("Please provide a valid ID.");
            return self::FAILURE;
        }

        $propertyOption = $input->hasOption("property") ? $input->getOption("property") : null;
        if (!is_string($propertyOption) || "" === ($property = trim($propertyOption))) {
            $property = "id";
        }

        $entity = match ($property) {
            "id" => $this->exampleRandflakeIdRepository->find($id),
            default => $this->exampleRandflakeIdRepository->findOneByProperty($property, $id),
        };

        if (!($entity instanceof ExampleRandflakeIdEntity)) {
            $output->writeln("No entity found with the provided ID.");
            return self::FAILURE;
        }

        $output->writeln(sprintf("Entity found with ID: %s [%s]", $entity->getId(), $entity->getName()));
        $output->writeln(sprintf("- Default value: %s", $entity->getExampleDefault()));
        $output->writeln(sprintf("- Explicit decrypted & decoded value: %s", $entity->getExampleExplicitDd()));
        $output->writeln(sprintf("- Explicit decrypted & encoded value: %s", $entity->getExampleExplicitDe()));
        $output->writeln(sprintf("- Explicit encrypted & decoded value: %s", $entity->getExampleExplicitEd()));
        $output->writeln(sprintf("- Explicit encrypted & encoded value: %s", $entity->getExampleExplicitEe()));

        return self::SUCCESS;
    }
}
