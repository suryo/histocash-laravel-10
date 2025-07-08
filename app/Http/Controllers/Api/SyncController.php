<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;

class SyncController extends Controller
{
    // GET /api/sync/download
    public function download(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'books' => \App\Models\Book::where('user_id', $user->id)
                ->where('is_deleted', false)
                ->get(),

            'accounts' => \App\Models\Account::where('user_id', $user->id)
                ->where('is_deleted', false)
                ->get(),

            'categories' => \App\Models\Category::where('user_id', $user->id)
                ->where('is_deleted', false)
                ->get(),

            'transactions' => \App\Models\Transaction::where('user_id', $user->id)
                ->where('is_deleted', false)
                ->get(),
        ]);
    }


    public function upload(Request $request)
    {
        $user = auth()->user();

        // 1. BOOKS
        foreach ($request->books ?? [] as $item) {
            $existing = \App\Models\Book::find($item['id']);
            if (!$existing || strtotime($item['updated_at']) > strtotime($existing->updated_at)) {
                \App\Models\Book::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'user_id'    => $user->id,
                        'name'       => $item['name'],
                        'updated_at' => $item['updated_at'],
                        'is_deleted' => $item['is_deleted'] ?? false,
                    ]
                );
            }
        }

        // 2. ACCOUNTS
        foreach ($request->accounts ?? [] as $item) {
            $existing = \App\Models\Account::find($item['id']);
            if (!$existing || strtotime($item['updated_at']) > strtotime($existing->updated_at)) {
                \App\Models\Account::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'user_id'    => $user->id,
                        'book_id'    => $item['book_id'],
                        'name'       => $item['name'],
                        'icon'       => $item['icon'] ?? '',
                        'updated_at' => $item['updated_at'],
                        'is_deleted' => $item['is_deleted'] ?? false,
                    ]
                );
            }
        }

        // 3. CATEGORIES
        foreach ($request->categories ?? [] as $item) {
            $existing = \App\Models\Category::find($item['id']);
            if (!$existing || strtotime($item['updated_at']) > strtotime($existing->updated_at)) {
                \App\Models\Category::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'user_id'    => $user->id,
                        'book_id'    => $item['book_id'],
                        'name'       => $item['name'],
                        'type'       => $item['type'],
                        'icon'       => $item['icon'] ?? '',
                        'updated_at' => $item['updated_at'],
                        'is_deleted' => $item['is_deleted'] ?? false,
                    ]
                );
            }
        }

        // 4. TRANSACTIONS
        foreach ($request->transactions ?? [] as $item) {
            $existing = \App\Models\Transaction::find($item['id']);
            if (!$existing || strtotime($item['updated_at']) > strtotime($existing->updated_at)) {
                \App\Models\Transaction::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'user_id'           => $user->id,
                        'book_id'           => $item['book_id'],
                        'account_id'        => $item['account_id'],
                        'category_id'       => $item['category_id'] ?? null,
                        'target_account_id' => $item['target_account_id'] ?? null,
                        'type'              => $item['type'],
                        'amount'            => $item['amount'],
                        'date'              => $item['date'],
                        'note'              => $item['note'] ?? null,
                        'updated_at'        => $item['updated_at'],
                        'is_deleted'        => $item['is_deleted'] ?? false,
                    ]
                );
            }
        }

        return response()->json(['message' => 'Data synced safely']);
    }


    public function summary()
    {
        $user = auth()->user();

        $bookCount = \App\Models\Book::where('user_id', $user->id)->count();
        $accountCount = \App\Models\Account::where('user_id', $user->id)->count();
        $categoryCount = \App\Models\Category::where('user_id', $user->id)->count();
        $transactionCount = \App\Models\Transaction::where('user_id', $user->id)->count();

        $lastBook = \App\Models\Book::where('user_id', $user->id)->latest('updated_at')->first();
        $lastAccount = \App\Models\Account::where('user_id', $user->id)->latest('updated_at')->first();
        $lastCategory = \App\Models\Category::where('user_id', $user->id)->latest('updated_at')->first();
        $lastTransaction = \App\Models\Transaction::where('user_id', $user->id)->latest('updated_at')->first();

        return response()->json([
            'total' => [
                'books' => $bookCount,
                'accounts' => $accountCount,
                'categories' => $categoryCount,
                'transactions' => $transactionCount,
            ],
            'last_updated' => [
                'books' => optional($lastBook)->updated_at,
                'accounts' => optional($lastAccount)->updated_at,
                'categories' => optional($lastCategory)->updated_at,
                'transactions' => optional($lastTransaction)->updated_at,
            ]
        ]);
    }
}
