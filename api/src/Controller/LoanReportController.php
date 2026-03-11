<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Dto\UserLoanReport;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class LoanReportController extends AbstractController
{

    public function __invoke(Request $request, LoanRepository $loanRepository): array
    {
        $start = $request->query->get('startDate');
        $end = $request->query->get('endDate');

        if (!$start || !$end) {
            throw $this->createNotFoundException('start and end query params are required (YYYY-MM-DD)');
        }

        $startDt = new \DateTime($start);
        $endDt   = new \DateTime($end);

        $rows = $loanRepository->getLoansByUserBetweenDates($startDt, $endDt);

        // map results to DTOs
        return array_map(fn($r) => new UserLoanReport($r['userName'], (int)$r['totalLoans']), $rows);
    }

    /*#[Route('/loans/report', name: 'report_loans',  methods: ['GET'])]
    public function getReport(Request $request, LoanRepository $loanRepository): array
    {
        $start = $request->query->get('startDate') ?? "2023-01-01";
        $end = $request->query->get('endDate') ?? "now";

        if (!$start || !$end) {
            throw $this->createNotFoundException('start and end query params are required (YYYY-MM-DD)');
        }

        $startDt = new \DateTime($start);
        $endDt   = new \DateTime($end);

        $rows = $loanRepository->getLoansByUserBetweenDates($startDt, $endDt);

        // map results to DTOs
        return array_map(fn($r) => new UserLoanReport($r['userName'], (int)$r['totalLoans']), $rows);
    }*/

    
}