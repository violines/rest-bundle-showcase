<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\AuthenticationFailedException;
use App\Infrastructure\Exception\AuthorizationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Exception\NotFoundException;
use App\Infrastructure\Security\Voter\ReviewUniqueVoter;
use App\Infrastructure\View\Ok;
use App\Product\Exception\ProductNotExists;
use App\Product\ProductService;
use App\Product\Value\Language;
use App\Product\Value\ProductId;
use App\Product\View\ProductView;
use App\Review\Command\CreateReview;
use App\Review\ReviewService;
use App\User\Command\CreateProfile;
use App\User\Exception\UserAlreadyExists;
use App\User\Entity\User;
use App\User\UserService;
use App\User\Value\UserId;
use App\User\View\ProfileView;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FrontendController
{
    private ProductService $productService;

    private ReviewService $reviewService;

    private Security $security;

    private UserService $userService;

    public function __construct(
        ProductService $productService,
        ReviewService $reviewService,
        Security $security,
        UserService $userService
    ) {
        $this->productService = $productService;
        $this->reviewService = $reviewService;
        $this->security = $security;
        $this->userService = $userService;
    }

    /**
     * @Route("/{_locale}/frontend/categories", name="frontend_categories", requirements={"_locale": "en|de"})
     */
    public function categories(string $_locale): array
    {
        return $this->productService->categories(Language::fromString($_locale));
    }

    /**
     * @Route("/{_locale}/frontend/products", name="frontend_products")
     */
    public function products(string $_locale): array
    {
        return $this->productService->products(Language::fromString($_locale));
    }

    /**
     * @Route("/{_locale}/frontend/product/{id}", methods={"GET"}, name="frontend_product", requirements={"_locale": "en|de"})
     */
    public function product(int $id, string $_locale): ProductView
    {
        try {
            $product = $this->productService->product(ProductId::fromInt($id), Language::fromString($_locale));
        } catch (ProductNotExists $e) {
            throw NotFoundException::resource();
        }

        return $product;
    }

    /**
     * @param User $user
     * @Route("/{_locale}/frontend/review", methods={"POST"}, name="frontend_create_review", requirements={"_locale": "en|de"})
     */
    public function createReview(CreateReview $createReview, UserInterface $user): Ok
    {
        if (!$this->security->isGranted(ReviewUniqueVoter::NAME, $createReview)) {
            throw AuthorizationFailedException::entryNotAllowed();
        };

        $createReview->userId = $user->getId();

        try {
            $this->reviewService->createReview($createReview);
        } catch (\Throwable $e) {
            throw BadRequestException::reviewExists();
        }

        return Ok::create();
    }

    /**
     * @Route("/{_locale}/frontend/register", methods={"POST"}, name="frontend_register", requirements={"_locale": "en|de"})
     */
    public function register(CreateProfile $createProfile): Ok
    {
        try {
            $this->userService->createProfile($createProfile);
        } catch (UserAlreadyExists $e) {
            throw BadRequestException::userExists();
        }

        return Ok::create();
    }

    /**
     * @Route("/{_locale}/frontend/profile/{userId}", methods={"GET"}, name="frontend_profile", requirements={"_locale": "en|de"})
     */
    public function profile(int $userId, UserInterface $user): ProfileView
    {
        if (!$user instanceof User || $user->getId() !== $userId) {
            throw AuthenticationFailedException::userNotFound();
        }

        return $this->userService->profile(UserId::fromInt($userId));
    }
}
