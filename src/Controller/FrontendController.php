<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Rating as EntityRating;
use App\Exception\NotFoundException;
use App\Repository\CandyRepository;
use App\Repository\RatingRepository;
use App\Struct\Frontend\Candy as CandyStruct;
use App\Struct\Frontend\Rating;
use App\Struct\Ok;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    private CandyRepository $candyRepository;

    private EntityManagerInterface $entityManager;

    private RatingRepository $ratingRepository;

    public function __construct(
        CandyRepository $candyRepository,
        EntityManagerInterface $entityManager,
        RatingRepository $ratingRepository
    ) {
        $this->candyRepository = $candyRepository;
        $this->entityManager = $entityManager;
        $this->ratingRepository = $ratingRepository;
    }

    /**
     * @Route("candy/list", name="candy_list")
     */
    public function candyList(): array
    {
        $_candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $_candies[] = $candy->toFrontendStruct('de', null);
        }

        return $_candies;
    }

    /**
     * @Route("/candy/{gtin}", methods={"GET"}, name="candy_detail")
     */
    public function candyDetail(int $gtin): CandyStruct
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $gtin]);

        $averageRating = $this->ratingRepository->averageByCandy($candy);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        return $candy->toFrontendStruct('de', $averageRating);
    }

    /**
     * @Route("/rate", methods={"POST"}, name="rate")
     */
    public function rate(Rating $struct): Ok
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $struct->gtin]);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        $this->entityManager->persist(EntityRating::fromStruct($struct, $candy));
        $this->entityManager->flush();

        return OK::create();
    }
}
