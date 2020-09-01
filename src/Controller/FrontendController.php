<?php

declare(strict_types=1);

namespace App\Controller;

use App\CommandObject\Profile as ProfileCommandObject;
use App\CommandObject\Review as ReviewCommandObject;
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
use App\ValueObject\HTTPClient;
use App\View\Candy as CandyView;
use App\View\Ok as OkView;
use App\View\Profile as ProfileView;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FrontendController
{
    private CandyRepository $candyRepository;

    private CategoryRepository $categoryRepository;

    private ReviewRepository $reviewRepository;

    private Security $security;

    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        CandyRepository $candyRepository,
        CategoryRepository $categoryRepository,
        ReviewRepository $reviewRepository,
        Security $security,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->candyRepository = $candyRepository;
        $this->categoryRepository = $categoryRepository;
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
        $candies = [];

        foreach ($this->candyRepository->findAll() as $candy) {
            $candies[] = CandyView::fromEntity($candy, $client->getContentLanguage());
        }

        return $candies;
    }

    /**
     * @Route("/frontend/candy/{gtin}", methods={"GET"}, name="frontend_candy_detail")
     */
    public function candyDetail(int $gtin, HTTPClient $client): CandyView
    {
        $candy = $this->candyRepository->findOneBy(['gtin' => $gtin]);

        if (null === $candy) {
            throw NotFoundException::resource();
        }

        $averageRating = $this->reviewRepository->averageByCandy($candy);

        return CandyView::fromEntity($candy, $client->getContentLanguage(), $averageRating);
    }

    /**
     * @Route("/frontend/review", methods={"POST"}, name="frontend_review")
     */
    public function review(ReviewCommandObject $reviewCommand, UserInterface $user): OkView
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $reviewCommand)) {
            throw AuthorizationFailedException::entryNotAllowed();
        };

        $candy = $this->candyRepository->findOneBy(['gtin' => $reviewCommand->gtin]);

        if (null === $candy) {
            throw NotFoundException::resource();
        }

        $this->reviewRepository->save(Review::fromCommandObject($reviewCommand, $candy, $user));

        return OkView::create();
    }

    /**
     * @Route("/frontend/register", methods={"POST"}, name="frontend_register")
     */
    public function register(ProfileCommandObject $profile): OkView
    {
        if (null !== $this->userRepository->findOneBy(['email' => $profile->email])) {
            throw BadRequestException::userExists();
        }

        $this->userRepository->save(User::fromProfile($profile, $this->passwordEncoder));

        return OkView::create();
    }

    /**
     * @Route("/frontend/profile", methods={"GET"}, name="frontend_profile")
     */
    public function profile(?UserInterface $user): ProfileView
    {
        if ($user instanceof User) {
            return ProfileView::fromEntity($user);
        }

        throw AuthenticationFailedException::userNotFound();
    }
}
