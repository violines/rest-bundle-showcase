<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Exception\AuthenticationFailedException;
use App\Exception\AuthorizationFailedException;
use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use App\Repository\CandyRepository;
use App\Repository\CategoryRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Security\Voter\ReviewUniqueVoter;
use App\DTO\Frontend\Candy as FrontendCandy;
use App\DTO\Frontend\ProfileRead as FrontendProfileRead;
use App\DTO\Frontend\ProfileWrite as FrontendProfileWrite;
use App\DTO\Frontend\Review as FrontendReview;
use App\DTO\Ok;
use App\ValueObject\HTTPClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FrontendController
{
    private CandyRepository $candyRepository;

    private CategoryRepository $categoryRepository;

    private EntityManagerInterface $entityManager;

    private ReviewRepository $reviewRepository;

    private Security $security;

    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        CandyRepository $candyRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository,
        Security $security,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->candyRepository = $candyRepository;
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/frontend/categories", name="frontend_categories")
     */
    public function categories(): array
    {
        return $this->categoryRepository->selectAll();
    }

    /**
     * @Route("/frontend/candy/list", name="frontend_candy_list")
     */
    public function candyList(HTTPClient $client): array
    {
        $_candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $_candies[] = $candy->toFrontendDTO($client->getContentLanguage());
        }

        return $_candies;
    }

    /**
     * @Route("/frontend/candy/{gtin}", methods={"GET"}, name="frontend_candy_detail")
     */
    public function candyDetail(int $gtin, HTTPClient $client): FrontendCandy
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $gtin]);

        if (null === $candy) {
            throw NotFoundException::resource();
        }

        $averageRating = $this->reviewRepository->averageByCandy($candy);

        return $candy->toFrontendDTO($client->getContentLanguage(), $averageRating);
    }

    /**
     * @Route("/frontend/review", methods={"POST"}, name="frontend_review")
     */
    public function review(FrontendReview $frontendReview, UserInterface $user): Ok
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $frontendReview)) {
            throw AuthorizationFailedException::entryNotAllowed();
        };

        $candy = $this->candyRepository->findOneBy(['gtin' => $frontendReview->gtin]);

        if (null === $candy) {
            throw NotFoundException::resource();
        }

        $this->entityManager->persist(Review::fromFrontendDTO($frontendReview, $candy, $user));
        $this->entityManager->flush();

        return OK::create();
    }

    /**
     * @Route("/frontend/register", methods={"POST"}, name="frontend_register")
     */
    public function register(FrontendProfileWrite $profile): Ok
    {
        if (null !== $this->userRepository->findOneBy(['email' => $profile->email])) {
            throw BadRequestException::userExists();
        }

        $user = User::fromProfile($profile);
        $user->resetPassword($this->passwordEncoder, $profile);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return OK::create();
    }

    /**
     * @Route("/frontend/profile", methods={"GET"}, name="frontend_profile")
     */
    public function profile(?UserInterface $user): FrontendProfileRead
    {
        if ($user instanceof User) {
            return $user->toFrontendDTO();
        }

        throw AuthenticationFailedException::userNotFound();
    }
}
