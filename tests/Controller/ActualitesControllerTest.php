<?php

namespace App\Test\Controller;

use App\Entity\Actualites;
use App\Repository\ActualitesRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ActualitesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ActualitesRepository $repository;
    private string $path = '/actualites/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Actualites::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Actualite index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'actualite[contenu]' => 'Testing',
            'actualite[date]' => 'Testing',
            'actualite[auteur]' => 'Testing',
            'actualite[image]' => 'Testing',
        ]);

        self::assertResponseRedirects('/actualites/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Actualites();
        $fixture->setContenu('My Title');
        $fixture->setDate('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Actualite');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Actualites();
        $fixture->setContenu('My Title');
        $fixture->setDate('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'actualite[contenu]' => 'Something New',
            'actualite[date]' => 'Something New',
            'actualite[auteur]' => 'Something New',
            'actualite[image]' => 'Something New',
        ]);

        self::assertResponseRedirects('/actualites/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getContenu());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getAuteur());
        self::assertSame('Something New', $fixture[0]->getImage());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Actualites();
        $fixture->setContenu('My Title');
        $fixture->setDate('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setImage('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/actualites/');
    }
}
