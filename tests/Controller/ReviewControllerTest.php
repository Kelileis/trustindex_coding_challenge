<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testIndexPageIsSuccessful(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Cég vélemények');
    }

    public function testNewReviewPageIsSuccessful(): void
    {
        $this->client->request('GET', '/new-review');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Új vélemény írása');
    }

    public function testCompaniesPageIsSuccessful(): void
    {
        $this->client->request('GET', '/companies');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Cégstatisztikák');
    }

    public function testSubmitReviewAndRedirect(): void
    {
        $crawler = $this->client->request('GET', '/new-review');

        $form = $crawler->selectButton('Küldés')->form([
            'review[company_name]' => 'Test Company',
            'review[rating]' => 4,
            'review[review_text]' => 'This is a test review text.',
            'review[author_email]' => 'test@example.com',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.flash-success', 'Köszönjük a véleményed!');
    }

    public function testCompaniesSortedByRating(): void
    {
        $this->createReview('Low Corp', 2);
        $this->createReview('High Corp', 5);
        $this->createReview('Mid Corp', 3);

        $this->client->request('GET', '/companies');
        $this->assertResponseIsSuccessful();

        $table = $this->client->getCrawler()->filter('table tbody tr');
        $this->assertGreaterThanOrEqual(2, $table->count());

        $firstCompanyName = $table->first()->filter('td')->eq(1)->text();
        $this->assertStringContainsString('High Corp', $firstCompanyName);
    }

    private function createReview(string $companyName, int $rating): void
    {
        $review = new \App\Entity\Review();
        $review->setCompanyName($companyName);
        $review->setRating($rating);
        $review->setReviewText('Test review for ' . $companyName);
        $review->setAuthorEmail('test@example.com');
        $this->em->persist($review);
        $this->em->flush();
    }
}
