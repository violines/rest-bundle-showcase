<?php

declare(strict_types=1);

namespace App\Import\Model;

class Review
{
    private int $taste;

    private int $ingredients;

    private int $healthiness;

    private int $packaging;

    private int $availability;

    private string $comment;

    private int $candyId;

    private int $userId;

    private function __construct(
        int $taste,
        int $ingredients,
        int $healthiness,
        int $packaging,
        int $availability,
        string $comment,
        int $candyId,
        int $userId
    ) {
        $this->taste = $taste;
        $this->ingredients = $ingredients;
        $this->healthiness = $healthiness;
        $this->packaging = $packaging;
        $this->availability = $availability;
        $this->comment = $comment;
        $this->candyId = $candyId;
        $this->userId = $userId;
    }

    public static function new(
        int $taste,
        int $ingredients,
        int $healthiness,
        int $packaging,
        int $availability,
        string $comment,
        int $candyId,
        int $userId
    ) {
        return new self($taste, $ingredients, $healthiness, $packaging, $availability, $comment, $candyId, $userId);
    }

    public function toArray()
    {
        return [
            'taste' => $this->taste,
            'ingredients' => $this->ingredients,
            'healthiness' => $this->healthiness,
            'packaging' => $this->packaging,
            'availability' => $this->availability,
            'comment' => $this->comment,
            'candy_id' => $this->candyId,
            'user_id' => $this->userId
        ];
    }
}
