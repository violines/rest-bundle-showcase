<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Exception\AuthenticationFailedException;
use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use App\Repository\CandyRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Struct\Frontend\Candy as CandyStruct;
use App\Struct\Frontend\ProfileRead;
use App\Struct\Frontend\ProfileWrite;
use App\Struct\Frontend\Review as ReviewStruct;
use App\Struct\Ok;
use App\ValueObject\HTTPClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FrontendController
{
    private CandyRepository $candyRepository;

    private EntityManagerInterface $entityManager;

    private ReviewRepository $reviewRepository;

    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        CandyRepository $candyRepository,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->candyRepository = $candyRepository;
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/frontend/candy/list", name="frontend_candy_list")
     */
    public function candyList(HTTPClient $client): array
    {
        $_candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $_candies[] = $candy->toFrontendStruct($client->getContentLanguage());
        }

        return $_candies;
    }

    /**
     * @Route("/frontend/candy/{gtin}", methods={"GET"}, name="frontend_candy_detail")
     */
    public function candyDetail(int $gtin, HTTPClient $client): CandyStruct
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $gtin]);

        if (null === $candy) {
            throw NotFoundException::create();
        }

        $averageRating = $this->reviewRepository->averageByCandy($candy);

        return $candy->toFrontendStruct($client->getContentLanguage(), $averageRating);
    }

    /**
     * @Route("/frontend/review", methods={"POST"}, name="frontend_review")
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

    /**
     * @Route("/frontend/register", methods={"POST"}, name="frontend_register")
     */
    public function register(ProfileWrite $profile): Ok
    {
        if (null !== $this->userRepository->findOneBy(['email' => $profile->email])) {
            throw BadRequestException::userExists();
        }

        $user = User::fromProfile($profile);
        $user->resetPassword($this->passwordEncoder->encodePassword($user, $profile->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return OK::create();
    }

    /**
     * @Route("/frontend/profile", methods={"GET"}, name="frontend_profile")
     */
    public function profile(?UserInterface $user): ProfileRead
    {
        if ($user instanceof User) {
            return $user->toProfile();
        }

        throw AuthenticationFailedException::create();
    }
}
