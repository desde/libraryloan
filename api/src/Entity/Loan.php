<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use App\State\LoanProcessor;
use App\Controller\LoanReportController;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use App\Dto\UserLoanReport;


#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new GetCollection(
            name: 'report',
            uriTemplate: '/report',
            controller: LoanReportController::class,
            read: false,
            openapi: new Operation(
                summary: 'List users + total loans between dates',
                parameters: [
                    new Parameter(
                        name: 'startDate',
                        in: 'query',
                        required: true,
                        schema: ['type' => 'string', 'format' => 'date']
                    ),
                    new Parameter(
                        name: 'endDate',
                        in: 'query',
                        required: true,
                        schema: ['type' => 'string', 'format' => 'date']
                    )
                ]
            )
        ),
        new Post(processor: LoanProcessor::class),
        new Delete()
    ]
)]
#[ORM\Table(name: 'loan')]
#[ORM\Entity(repositoryClass: LoanRepository::class)]  
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $loanDate = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoanDate(): ?\DateTime
    {
        return $this->loanDate;
    }

    public function setLoanDate(\DateTime $loanDate): static
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }
}
