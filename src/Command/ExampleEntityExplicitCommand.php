<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ExampleRandflakeIdRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "app:example-entity:explicit",
    description: "Example command to demonstrate usage of the RandflakeIdBundle: Explicit column searching.",
)]
class ExampleEntityExplicitCommand extends Command
{
    public function __construct(
        private readonly ExampleRandflakeIdRepository $exampleRandflakeIdRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument("dd", InputArgument::REQUIRED, "Find value for explicit decrypted & decoded.")
            ->addArgument("de", InputArgument::REQUIRED, "Find value for explicit decrypted & encoded.")
            ->addArgument("ed", InputArgument::REQUIRED, "Find value for explicit encrypted & decoded.")
            ->addArgument("ee", InputArgument::REQUIRED, "Find value for explicit encrypted & encoded.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("This is an example command to demonstrate usage of the RandflakeIdBundle: Explicit column searching.");

        $arguments = [
            "dd" => $input->getArgument("dd"),
            "de" => $input->getArgument("de"),
            "ed" => $input->getArgument("ed"),
            "ee" => $input->getArgument("ee"),
        ];

        $entity = $this->exampleRandflakeIdRepository->findOneByExampleExplicitProperties(
            ...array_map(function ($a): string {

                if (!is_string($a) || "" === ($a = trim($a))) {
                    throw new \InvalidArgumentException("All arguments must be non-empty strings.");
                }
                return $a;

            }, $arguments)
        );

        if (null === $entity) {
            $output->writeln("No entity found with the provided values.");
            return self::FAILURE;
        }

        $output->writeln(sprintf("Entity found with ID: %s [%s]", $entity->getId(), $entity->getName()));

        return self::SUCCESS;
    }
}
