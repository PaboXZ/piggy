<?php

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;
use App\Services\TransactionService;

class HomeController{

    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService
        ){
    }

    public function home(){
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 10;
        $offset = ($page - 1) * $length;
        $searchTerm = isset($_GET['s']) ? '&s=' . e($_GET['s']) : null;

        $transactions = $this->transactionService->getUserTransactions($length, $offset);
        $pageCount = ceil($this->transactionService->countUserTransactions() / $length);

        $previousPageLink = $page > 1 ? '/?p=' . $page - 1 . $searchTerm : null;
        $nextPageLink = $page < $pageCount ? '/?p=' . $page + 1 . $searchTerm : null;

        $pageLink = fn(int $pageNumber) => '/?p=' . $pageNumber . $searchTerm;


        echo $this->view->render("/index.php", [
            'transactions' => $transactions,
            'previousPage' => $previousPageLink,
            'nextPage' => $nextPageLink,
            'selectPage' => $pageLink,
            'searchTerm' => $searchTerm,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ]);
    }
}