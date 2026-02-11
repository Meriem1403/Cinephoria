<?php

namespace App\Command;

use App\Service\ShowtimeGeneratorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:showtimes:generate',
    description: 'Génère automatiquement des séances pour tous les films à l\'affiche sur les N prochains jours (modifiables dans le backoffice). À planifier en cron quotidien (ex: 0 6 * * *).',
)]
class GenerateShowtimesCommand extends Command
{
    public function __construct(
        private readonly ShowtimeGeneratorService $showtimeGenerator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Nombre de jours à couvrir à partir d\'aujourd\'hui', 14)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $days = max(1, (int) $input->getOption('days'));

        $io->info(sprintf('Génération des séances pour les %d prochains jours...', $days));

        $created = $this->showtimeGenerator->generate($days);

        $io->success(sprintf('%d séance(s) créée(s). Vous pouvez les modifier dans le backoffice (Showtime).', $created));

        return Command::SUCCESS;
    }
}
