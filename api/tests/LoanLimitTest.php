<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoanLimitTest extends ApiTestCase
{
    private $client;
    private array $createdUsers = [];
    private array $createdBooks = [];
    private array $createdLoans = [];

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdLoans as $loan) {
            $this->client->request('DELETE', $loan);
        }

        foreach ($this->createdBooks as $book) {
            $this->client->request('DELETE', $book);
        }

        foreach ($this->createdUsers as $user) {
            $this->client->request('DELETE', $user);
        }
    }

    public function testUserCannotHaveMoreThanThreeLoans(): void
    {
        // crear usuario
        $user = $this->client->request('POST', '/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'Limit User',
                'email' => 'limit'.uniqid().'@test.com'
            ]
        ])->toArray();

        $this->createdUsers[] = $user['@id'];

        // crear 4 libros
        $books = [];

        for ($i = 0; $i < ( $_ENV['LIMIT_LOAN'] + 1 ); $i++) {
            $book = $this->client->request('POST', '/api/books', [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                    'Accept' => 'application/ld+json'
                ],
                'json' => [
                    'title' => "Book $i",
                    'author' => 'Author'
                ]
            ])->toArray();

            $books[] = $book;
            $this->createdBooks[] = $book['@id'];
        }

        // crear 3 préstamos válidos
        for ($i = 0; $i < $_ENV['LIMIT_LOAN']; $i++) {

            $loan = $this->client->request('POST', '/api/loans', [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                    'Accept' => 'application/ld+json'
                ],
                'json' => [
                    'loanDate' => '2025-01-01',
                    'client' => $user['@id'],
                    'book' => $books[$i]['@id']
                ]
            ])->toArray();

            $this->createdLoans[] = $loan['@id'];

            $this->assertResponseStatusCodeSame(201);
        }

        // intentar cuarto préstamo
        $this->client->request('POST', '/api/loans', [
            'headers' => [
                    'Content-Type' => 'application/ld+json',
                    'Accept' => 'application/ld+json'
                ],
            'json' => [
                'loanDate' => '2025-01-01',
                'client' => $user['@id'],
                'book' => $books[3]['@id']
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}