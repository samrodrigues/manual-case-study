<?php

namespace App\Tests\Controller\Api;

use App\Entity\Option;
use App\Entity\Question;
use App\Entity\Questionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionnaireControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testGetQuestionnaire(): void
    {
        $questionnaire = new Questionnaire();
        $questionnaire->setName('Questionnaire 1');
        $this->entityManager->persist($questionnaire);

        $question1 = new Question();
        $question1->setText('Question 1');
        $question1->setQuestionnaire($questionnaire);
        $this->entityManager->persist($question1);

        $option1 = new Option();
        $option1->setText('Option 1');
        $option1->setQuestion($question1);
        $this->entityManager->persist($option1);

        $this->entityManager->flush();

        $this->client->request('GET', '/api/questionnaire/' . $questionnaire->getId());
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals('Questionnaire 1', $responseData['name']);
        $this->assertArrayHasKey('questions', $responseData);
        $this->assertCount(1, $responseData['questions']);
        $this->assertArrayHasKey('text', $responseData['questions'][0]);
        $this->assertEquals('Question 1', $responseData['questions'][0]['text']);
        $this->assertArrayHasKey('options', $responseData['questions'][0]);
        $this->assertCount(1, $responseData['questions'][0]['options']);
        $this->assertArrayHasKey('text', $responseData['questions'][0]['options'][0]);
        $this->assertEquals('Option 1', $responseData['questions'][0]['options'][0]['text']);
    }
}
