<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Option;
use App\Entity\OptionProduct;
use App\Entity\Product;
use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Repository\OptionProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OptionProductRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private OptionProductRepository $optionProductRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->optionProductRepository = $this->entityManager->getRepository(OptionProduct::class);
    }

    /**
     * @dataProvider findAllAvailableProductsDataProvider
     */
    public function testFindAllAvailableProducts(bool $isAvailable, bool $expectedResult): void
    {
        $product = new Product();
        $product->setName('Product 1');
        $this->entityManager->persist($product);

        $questionnaire = new Questionnaire();
        $questionnaire->setName('Questionnaire');
        $this->entityManager->persist($questionnaire);
        $question = new Question();
        $question->setText("Question 1");
        $question->setQuestionnaire($questionnaire);
        $this->entityManager->persist($question);

        $option = new Option();
        $option->setText("O1");
        $option->setQuestion($question);
        $this->entityManager->persist($option);

        $optionProduct = new OptionProduct();
        $optionProduct->setOption($option);
        $optionProduct->setProduct($product);
        $optionProduct->setAvailable($isAvailable);
        $this->entityManager->persist($optionProduct);

        $this->entityManager->flush();

        $availableProducts = $this->optionProductRepository->findAllAvailableProducts([$option->getId()]);

        if ($expectedResult) {
            $this->assertContains($product, $availableProducts);
        } else {
            $this->assertNotContains($product, $availableProducts);
        }
    }

    public function findAllAvailableProductsDataProvider(): array
    {
        return [
            'available product' => [true, true],
            'unavailable product' => [false, false],
        ];
    }
}
