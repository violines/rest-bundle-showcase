<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Exception\AuthenticationFailedException;
use App\Infrastructure\Exception\AuthorizationFailedException;
use App\Infrastructure\Exception\BadRequestException;
use App\Infrastructure\Exception\NotFoundException;
use App\Infrastructure\HTTPClient;
use App\Infrastructure\Security\Voter\ReviewUniqueVoter;
use App\Infrastructure\View\Ok;
use App\Product\Exception\ProductNotExists;
use App\Product\ProductService;
use App\Product\Value\Language;
use App\Product\View\ProductView;
use App\Review\Command\CreateReview;
use App\Review\ReviewService;
use App\User\Command\CreateProfile;
use App\User\Exception\UserAlreadyExists;
use App\User\Entity\User;
use App\User\UserService;
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
     * @Route("/frontend/categories", name="frontend_categories")
     */
    public function categories(): array
    {
        return $this->productService->categories();
    }

    /**
     * @Route("/frontend/product/list", name="frontend_product_list")
     */
    public function productList(HTTPClient $client): array
    {
        return $this->productService->products(Language::fromString($client->getContentLanguage()));
    }

    /**
     * @Route("/frontend/product/{id}", methods={"GET"}, name="frontend_product_detail")
     */
    public function productDetail(int $id, HTTPClient $client): ProductView
    {
        try {
            $product = $this->productService->product($id, Language::fromString($client->getContentLanguage()));
        } catch (ProductNotExists $e) {
            throw NotFoundException::resource();
        }

        return $product;
    }

    /**
     * @param User $user
     * @Route("/frontend/review", methods={"POST"}, name="frontend_review")
     */
    public function review(CreateReview $createReview, UserInterface $user): Ok
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
     * @Route("/frontend/register", methods={"POST"}, name="frontend_register")
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
     * @Route("/frontend/profile", methods={"GET"}, name="frontend_profile")
     */
    public function profile(?UserInterface $user): ProfileView
    {
        if ($user instanceof User) {
            return $this->userService->getProfile($user);
        }

        throw AuthenticationFailedException::userNotFound();
    }
}
