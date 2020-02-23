<?php

namespace App\Command;

use App\Repository\ReviewRepository;
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

    private ReviewRepository $reviewRepository;

    public function __construct(
        ReviewRepository $reviewRepository
    ) {
        parent::__construct();
        $this->reviewRepository = $reviewRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate fake reviews for testing.')
            ->addArgument('candyId', InputArgument::REQUIRED, 'candy fk');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $candyId = (int) $input->getArgument('candyId');

        if ($candyId < 1) {
            $io->error('Invalid Argument: CandyId');
            return 1;
        }

        for ($i = 1; $i <= 200000; $i++) {
            $review = [
                'taste' => random_int(3, 5),
                'ingredients' => random_int(4, 5),
                'healthiness' => random_int(3, 5),
                'packaging' => random_int(4, 5),
                'availability' => random_int(3, 5),
                'comment' => self::COMMENTS[random_int(0, 9)],
                'candy_id' => $candyId
            ];

            if ($i % 1000 === 0) {
                $this->reviewRepository->insert($review, true);
            } else {
                $this->reviewRepository->insert($review, false);
            }
        }

        $io->success('Success.');
        return 0;
    }
}
