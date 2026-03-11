<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LoanReportTest extends ApiTestCase
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

    public function testLoanReportEndpoint(): void
    {
        // crear usuario
        $user = $this->client->request('POST', '/api/users', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'Report User',
                'email' => 'report'.uniqid().'@test.com'
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
                'title' => 'Report Book',
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

        // llamar al report
        $response = $this->client->request(
            'GET',
            '/api/report?startDate=2024-01-01&endDate=2026-12-31'
        );

        $this->assertResponseIsSuccessful();

        $data = $response->toArray();

        $this->assertIsArray($data);
    }
}