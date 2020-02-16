<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Repository\CandyRepository;
use App\Struct\Frontend\Candy as CandyStruct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    private CandyRepository $candyRepository;

    public function __construct(
        CandyRepository $candyRepository
    ) {
        $this->candyRepository = $candyRepository;
    }

    /**
     * @Route("candy/list", name="candy_list")
     */
    public function candyList(): array
    {
        $_candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $_candies[] = $candy->toFrontendStruct('de');
        }

        return $_candies;
    }

    /**
     * @Route("/candy/{id}", methods={"GET"}, name="candy_detail")
     */
    public function candyDetail(int $id): CandyStruct
    {
        $candy = $this->candyRepository->findOneBy(['id' => $id]);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        return $candy->toFrontendStruct('de');
    }
}
