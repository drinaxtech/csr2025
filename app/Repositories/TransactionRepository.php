<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    /**
     * Creates a new transaction record.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    /**
     * Updates an existing transaction record.
     *
     * @param int $id
     * @param array $data
     * @return Transaction
     */
    public function update(int $id, array $data): Transaction
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Finds a transaction by its ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function find(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    // Add other methods as needed (e.g., fetching transactions by donation ID)
}