<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\CommandObject\CreateReview;
use App\CommandObject\Profile as ProfileCommandObject;
use App\Entity\Review;
use App\Entity\User;
use App\Infrastructure\Exception\AuthenticationFailedException;
use App\Infrastructure\Exception\AuthorizationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Exception\NotFoundException;
use App\Infrastructure\Repository\CategoryRepository;
use App\Infrastructure\Repository\ProductDoctrineRepository;
use App\Infrastructure\Repository\ReviewRepository;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Security\Voter\ReviewUniqueVoter;
use App\ValueObject\HTTPClient;
use App\ValueObject\ReviewId;
use App\View\Product as ProductView;
use App\View\Ok as OkView;
use App\View\Profile as ProfileView;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FrontendController
{
    private ProductDoctrineRepository $productRepository;

    private CategoryRepository $categoryRepository;

    private ReviewRepository $reviewRepository;

    private Security $security;

    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        ProductDoctrineRepository $productRepository,
        CategoryRepository $categoryRepository,
        ReviewRepository $reviewRepository,
        Security $security,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->productRepository = $productRepository;
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
     * @Route("/frontend/product/list", name="frontend_product_list")
     */
    public function productList(HTTPClient $client): array
    {
        $products = [];

        foreach ($this->productRepository->findAll() as $product) {
            $products[] = ProductView::fromEntity($product, $client->getContentLanguage());
        }

        return $products;
    }

    /**
     * @Route("/frontend/product/{gtin}", methods={"GET"}, name="frontend_product_detail")
     */
    public function productDetail(int $gtin, HTTPClient $client): ProductView
    {
        $product = $this->productRepository->findOneBy(['gtin' => $gtin]);

        if (null === $product) {
            throw NotFoundException::resource();
        }

        $averageRating = $this->reviewRepository->averageByProduct($product);

        return ProductView::fromEntity($product, $client->getContentLanguage(), $averageRating);
    }

    /**
     * @Route("/frontend/review", methods={"POST"}, name="frontend_review")
     */
    public function review(CreateReview $createReview, UserInterface $user): OkView
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $createReview)) {
            throw AuthorizationFailedException::entryNotAllowed();
        };

        $product = $this->productRepository->findOneBy(['gtin' => $createReview->gtin]);

        if (null === $product) {
            throw NotFoundException::resource();
        }

        $nextId = ReviewId::new($this->reviewRepository->nextId());

        $this->reviewRepository->save(Review::fromCreate($nextId, $createReview, $product, $user));

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
