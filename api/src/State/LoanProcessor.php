<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Loan;
use App\Service\LoanService;

class LoanProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;
    private LoanService $loanService;

    public function __construct(
        ProcessorInterface $persistProcessor,
        LoanService $loanService
    ) {
        $this->persistProcessor = $persistProcessor;
        $this->loanService = $loanService;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof Loan) {
            $this->loanService->checkLoanLimit($data->getClient());
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}