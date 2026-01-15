<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\WalletTransType;
use App\Models\WalletTransaction;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function init(Request $request, PaystackService $paystack)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $data = $paystack->initializeWalletFunding(
            $request->amount,
            auth()->id()
        );

        return redirect($data['authorization_url']);
    }


    public function callback(Request $request, PaystackService $paystack)
    {
        $reference = $request->query('reference');

        $data = $paystack->verify($reference);

        if ($data['status'] !== 'success') {
            // abort(403, 'Payment failed');
            return redirect()->back()->with('error', 'Payment failed');
        }
        $transaction = WalletTransaction::where('reference', $reference)->firstOrFail();
        $wallet = $transaction->wallet;
        // Prevent double-credit
        if ($transaction->description === 'Wallet funded via Paystack (confirmed)') {
            return redirect()->route(
                'filament.guest.resources.wallets.edit',
                ['record' => $wallet->id]
            );
        }
        // increment wallet
        $wallet->increment('balance', $transaction->amount);

        $transaction->update([
            'description' => 'Wallet funded via Paystack (confirmed)',
        ]);

        return redirect()->route(
            'filament.guest.resources.wallets.edit',
            ['record' => $wallet->id]
        )->with('success', 'Wallet funded successfully');

        // Old

        // if ($data['metadata']['type'] === 'wallet') {
        //     $transaction = WalletTransaction::where('reference', $reference)->firstOrFail();

        //     // Prevent double-credit
        //     if ($transaction->wallet->transactions()
        //         ->where('reference', $reference)
        //         ->where('type', 'credit')
        //         ->exists()
        //     ) {
        //         return redirect()->route('filament.guest.resources.wallets.edit', [
        //             'record' => $transaction->wallet_id,
        //         ]);
        //     }

        //     $transaction->wallet->credit(
        //         $transaction->amount,
        //         'Wallet funded via Paystack'
        //     );
        // }

        // return redirect()->route('filament.guest.resources.wallets.edit', [
        //     'record' => $transaction->wallet_id,
        // ]);
    }
}
