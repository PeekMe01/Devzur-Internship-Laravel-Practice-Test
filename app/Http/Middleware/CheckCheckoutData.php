<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckCheckoutData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if checkout_data exists and is fully populated
        $checkoutData = session('checkout_data');

        if (!$checkoutData || !$this->isCheckoutDataValid($checkoutData)) {
            // Redirect to cart or another page if checkout data is missing or incomplete
            $notification = [
                'message' => 'You must complete the checkout process to access this page.',
                'alert-type' => 'error'
            ];
            return redirect()->route('checkout.index')->with($notification);
        }

        return $next($request);
    }

    /**
     * Check if all required fields in checkout data are filled.
     *
     * @param array $data
     * @return bool
     */
    protected function isCheckoutDataValid($data)
    {
        // Define required fields
        $requiredFields = [
            'first_name', 'last_name', 'email', 'phone', 'address',
            'postal_code', 'city', 'country', 'location_lat', 'location_lng', 'total_amount'
        ];

        // Check if all required fields are present and not empty
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }
}