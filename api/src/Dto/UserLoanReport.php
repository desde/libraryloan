<?php

namespace App\Dto;

class UserLoanReport
{
    public function __construct(
        public string $userName,
        public int $totalLoans
    ) {
    }
}