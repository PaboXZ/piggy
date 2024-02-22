<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService {

    public function __construct(private Database $db){

    }

    public function create(array $formData){
        $this->db->query(
            "INSERT INTO transactions (user_id, description, amount, date) VALUES (:userID, :description, :amount, :date)",
            [
                'userID' => $_SESSION['user'],
                'description' => $formData['description'],
                'amount' => $formData['amount'],
                'date' => $formData['date']
                ]
        );
    }

    public function edit(array $formData, int $transactionID){
        $this->db->query(
            "UPDATE transactions SET 
            description = :description,
            amount = :amount,
            date = :date,
            updated_at = CURRENT_TIMESTAMP()
            WHERE user_id = :userID AND id = :transactionID", [
                'description' => $formData['description'],
                'amount' => $formData['amount'],
                'date' => $formData['date'],
                'userID' => $_SESSION['user'],
                'transactionID' => $transactionID
            ]);
    }

    public function delete(int $transactionID){
        $this->db->query("DELETE FROM transactions WHERE user_id = :userID AND id = :transactionID", [
            'userID' => $_SESSION['user'],
            'transactionID' => $transactionID
        ]);
    }

    public function getUserTransactions(int $length, int $offset){
        $searchParam = addcslashes($_GET['s'] ?? '', '%_');

        $transactions = $this->db->query(
            "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM transactions WHERE user_id = :userID AND description LIKE :searchParam
            LIMIT {$length} OFFSET {$offset}",
            [
                'userID' => $_SESSION['user'],
                'searchParam' => "%{$searchParam}%"
            ]
        )->findAll();

        $transactions = array_map(function(array $transaction){
            $transaction['receipts'] = $this->db->query("SELECT * FROM receipts WHERE transaction_id = :transactionID",
            ['transactionID' => $transaction['id']])->findAll();

            return $transaction;
        },
        $transactions);

        return $transactions;
    }

    public function countUserTransactions(){
        $searchParam = addcslashes($_GET['s'] ?? '', '%_');

        return $this->db->query(
            "SELECT COUNT(user_id) FROM transactions WHERE user_id = :userID AND description LIKE :searchParam",
            [
                'userID' => $_SESSION['user'],
                'searchParam' => "%{$searchParam}%"
            ]
        )->count();
    }

    public function getUserTransaction(string $transactionID){
        return $this->db->query(
            "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') as formatted_date FROM transactions WHERE user_id = :userID AND id = :transactionID", [
                'userID' => $_SESSION['user'],
                'transactionID' => $transactionID
            ]
        )->find();
    }
}