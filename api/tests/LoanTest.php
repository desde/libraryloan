<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoanTest extends ApiTestCase
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
        // borrar préstamos
        foreach ($this->createdLoans as $loan) {
            $this->client->request('DELETE', $loan);
        }

        // borrar libros
        foreach ($this->createdBooks as $book) {
            $this->client->request('DELETE', $book);
        }

        // borrar usuarios
        foreach ($this->createdUsers as $user) {
            $this->client->request('DELETE', $user);
        }
    }

    public function testCreateLoan(): void
    {
        // crear usuario
        $user = $this->client->request('POST', '/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'Test User',
                'email' => 'test'.uniqid().'@test.com'
            ]
        ])->toArray();

        $this->createdUsers[] = $user['@id'];

        // crear libro
        $book = $this->client->request('POST', '/api/books', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'title' => 'Test Book',
                'author' => 'Author'
            ]
        ])->toArray();

        $this->createdBooks[] = $book['@id'];

        // crear préstamo
        $loan = $this->client->request('POST', '/api/loans', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'loanDate' => '2025-01-01',
                'client' => $user['@id'],
                'book' => $book['@id']
            ]
        ])->toArray();

        $this->createdLoans[] = $loan['@id'];

        $this->assertResponseStatusCodeSame(201);
    }
}