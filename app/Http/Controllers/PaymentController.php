<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    protected $paymentModel;

    // Constructor Dependency Injection
    public function __construct(
        Payment $payment,
    ) {
        $this->paymentModel = $payment;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $payments = $this->paymentModel::query()
            ->when($search, function ($query, $search) {
                $query->where('transaction_id', 'like', '%' . $search . '%')
                    ->orWhereHas('order', function ($query) use ($search) {
                        $query->where('invoice', 'like', '%' . $search . '%');
                    });
            })
            ->paginate(10);

        return view('admin.payments.payments', compact('payments'));
    }
}
