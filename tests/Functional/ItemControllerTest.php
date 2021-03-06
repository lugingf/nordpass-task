<?php

namespace App\Tests\Functional;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        
        $data = 'very secure new item data';

        $newItemData = ['data' => $data];

        $client->request('POST', '/item', $newItemData);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('very secure new item data', $client->getResponse()->getContent());
    }
    public function testUpdate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $oldData = 'very secure old item data';
        $existedId = 1;
        $item = new Item();
        $item->setId($existedId);
        $item->setUser($user);
        $item->setData($oldData);

        $itemRepository = $this->createMock(ItemRepository::class);
        $itemRepository->expects($this->any())
            ->method('find')
            ->willReturn($item);

        $newData = 'very secure new item data';
        $newItemData = ['data' => $newData];

        $client->request('PATCH', "/item/$existedId", $newItemData);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('very secure new item data', $client->getResponse()->getContent());
    }
}
