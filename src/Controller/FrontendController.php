<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Exception\NotFoundException;
use App\Repository\CandyRepository;
use App\Repository\ReviewRepository;
use App\Struct\Frontend\Candy as CandyStruct;
use App\Struct\Frontend\Review as ReviewStruct;
use App\Struct\Ok;
use App\ValueObject\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    private CandyRepository $candyRepository;

    private EntityManagerInterface $entityManager;

    private ReviewRepository $reviewRepository;

    public function __construct(
        CandyRepository $candyRepository,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository
    ) {
        $this->candyRepository = $candyRepository;
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @Route("candy/list", name="candy_list")
     */
    public function candyList(Client $client): array
    {
        $_candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $_candies[] = $candy->toFrontendStruct($client->getContentLanguage(), null);
        }

        return $_candies;
    }

    /**
     * @Route("/candy/{gtin}", methods={"GET"}, name="candy_detail")
     */
    public function candyDetail(int $gtin, Client $client): CandyStruct
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $gtin]);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        $averageRating = $this->reviewRepository->averageByCandy($candy);

        return $candy->toFrontendStruct($client->getContentLanguage(), $averageRating);
    }

    /**
     * @Route("/review", methods={"POST"}, name="review")
     */
    public function review(ReviewStruct $struct): Ok
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $struct->gtin]);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        $this->entityManager->persist(Review::fromStruct($struct, $candy));
        $this->entityManager->flush();

        return OK::create();
    }
}
