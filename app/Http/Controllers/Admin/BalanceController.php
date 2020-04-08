<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('transactions.room');
        $intent = $user->createSetupIntent();

        return view('admin.balance', compact('user', 'intent'));
    }

    public function add(Request $request)
    {
        $paymentMethod = $request->input('payment_method');
        $user          = $request->user();

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $stripeCharge = $user->charge($request->input('amount'), $paymentMethod);

            Transaction::create([
                'user_id'     => $user->id,
                'paid_amount' => $stripeCharge->amount,
            ]);

            $user->credits += $stripeCharge->amount;
            $user->save();
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors([$ex->getMessage()]);
        }

        return redirect()->back()->withMessage('Transaction completed');
    }
}
