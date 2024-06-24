<?php

namespace App\Tests\Integration;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class GenreIntregrationTest extends KernelTestCase

{
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void 
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    public function testGenreCreation()
    {
        $genre = new Genre();
        $genre->setName('Integration Test Genre');

        $this->entityManager->persist($genre);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Genre::class);
        $savedGenre = $repository->findOneBy(['name' => 'Integration Test Genre']);

        $this->assertNotNull($savedGenre);
        $this->assertEquals('Integration Test Genre', $savedGenre->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}