<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\LoanRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoanService
{
    private LoanRepository $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function checkLoanLimit(User $user): void
    {
        $loans = $this->loanRepository->count([
            'client' => $user
        ]);

        if ($loans >= $_ENV['LIMIT_LOAN']) {
            throw new BadRequestHttpException('User cannot have more than 3 loans');
        }
    }
}