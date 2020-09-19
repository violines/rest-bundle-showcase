<?php

namespace App\Infrastructure\Command;

use App\Import\Import;
use App\Import\Model\Review;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReviewGeneratorCommand extends Command
{
    private const COMMENTS = [
        0 => "'I really liked it.'",
        1 => "'Nice. Want more.'",
        2 => "'Solid.'",
        3 => "'Everything was fine.'",
        4 => "'This was the best ever.'",
        5 => "'Really really goood.'",
        6 => "'The best!'",
        7 => "'I love it.'",
        8 => "'Thanks a lot'",
        9 => "'Classics, nice!'"
    ];

    protected static $defaultName = 'app:review-generator';

    private Import $import;

    public function __construct(
        Import $import
    ) {
        parent::__construct();
        $this->import = $import;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate fake reviews for testing.')
            ->addArgument('productId', InputArgument::REQUIRED, 'product fk');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $productId = (int) $input->getArgument('productId');

        if ($productId < 1) {
            $io->error('Invalid Argument: ProductId');
            return 1;
        }

        $reviews = [];

        for ($i = 1; $i <= 200000; $i++) {
            $reviews[] = Review::new(
                random_int(3, 5),
                random_int(3, 5),
                random_int(3, 5),
                random_int(3, 5),
                random_int(3, 5),
                self::COMMENTS[random_int(0, 9)],
                $productId,
                1
            );

            if ($i % 1000 === 0) {
                $this->import->reviews($reviews);
                $reviews = [];
            }
        }

        $this->import->reviews($reviews);

        $io->success('Success.');
        return 0;
    }
}
