<?php

namespace App\Tests\Entity;

use App\Entity\Review;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $review = new Review();

        $review->setCompanyName('Acme Corp');
        $this->assertSame('Acme Corp', $review->getCompanyName());

        $review->setRating(4);
        $this->assertSame(4, $review->getRating());

        $review->setReviewText('Great company to work with.');
        $this->assertSame('Great company to work with.', $review->getReviewText());

        $review->setAuthorEmail('test@example.com');
        $this->assertSame('test@example.com', $review->getAuthorEmail());
    }

    public function testLifecycleCallbacks(): void
    {
        $review = new Review();
        $review->setCompanyName('Test');
        $review->setRating(5);
        $review->setReviewText('Excellent');
        $review->setAuthorEmail('test@test.com');

        $review->setCreatedAtValue();
        $this->assertInstanceOf(\DateTimeImmutable::class, $review->getCreatedAt());

        $review->setUpdatedAtValue();
        $this->assertInstanceOf(\DateTimeImmutable::class, $review->getUpdatedAt());
    }

    public function testRatingBoundaryValues(): void
    {
        $review = new Review();

        $review->setRating(1);
        $this->assertSame(1, $review->getRating());

        $review->setRating(5);
        $this->assertSame(5, $review->getRating());
    }

    public function testAverageRatingCalculation(): void
    {
        $ratings = [3, 4, 5, 2, 4];
        $avg = round(array_sum($ratings) / count($ratings), 1);
        $this->assertEqualsWithDelta(3.6, $avg, 0.1);
    }

    public function testAverageRatingSingleValue(): void
    {
        $ratings = [4];
        $avg = round(array_sum($ratings) / count($ratings), 1);
        $this->assertEqualsWithDelta(4.0, $avg, 0.1);
    }

    public function testSortingByRatingDesc(): void
    {
        $reviews = [
            $this->createReviewWithRating(3),
            $this->createReviewWithRating(5),
            $this->createReviewWithRating(1),
            $this->createReviewWithRating(4),
        ];

        usort($reviews, fn($a, $b) => $b->getRating() <=> $a->getRating());

        $this->assertSame(5, $reviews[0]->getRating());
        $this->assertSame(4, $reviews[1]->getRating());
        $this->assertSame(3, $reviews[2]->getRating());
        $this->assertSame(1, $reviews[3]->getRating());
    }

    public function testSortingByDateDesc(): void
    {
        $reviews = [
            $this->createReviewWithDate(new \DateTimeImmutable('2026-01-01')),
            $this->createReviewWithDate(new \DateTimeImmutable('2026-06-15')),
            $this->createReviewWithDate(new \DateTimeImmutable('2026-03-10')),
        ];

        usort($reviews, fn($a, $b) => $b->getCreatedAt() <=> $a->getCreatedAt());

        $this->assertSame('2026-06-15', $reviews[0]->getCreatedAt()->format('Y-m-d'));
        $this->assertSame('2026-03-10', $reviews[1]->getCreatedAt()->format('Y-m-d'));
        $this->assertSame('2026-01-01', $reviews[2]->getCreatedAt()->format('Y-m-d'));
    }

    private function createReviewWithRating(int $rating): Review
    {
        $review = new Review();
        $review->setRating($rating);
        return $review;
    }

    private function createReviewWithDate(\DateTimeImmutable $date): Review
    {
        $review = new Review();
        $reflection = new \ReflectionClass($review);
        $property = $reflection->getProperty('created_at');
        $property->setValue($review, $date);
        return $review;
    }
}
