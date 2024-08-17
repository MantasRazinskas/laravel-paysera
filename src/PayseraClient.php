<?php

namespace Rpagency\LaravelPaysera;

use WebToPay;
use Illuminate\Support\Facades\Log;

class PayseraClient
{
    protected $projectId;
    protected $signPassword;
    protected $testMode;

    public function __construct()
    {
        $this->projectId = config('paysera.project_id');
        $this->signPassword = config('paysera.sign_password');
        $this->testMode = config('paysera.test_mode');
    }

    /**
     * Generate a payment request URL.
     */
    public function generatePaymentUrl(array $parameters)
    {
        try {
            return WebToPay::redirectToPayment(array_merge($parameters, [
                'projectid'     => $this->projectId,
                'sign_password' => $this->signPassword,
                'test'          => $this->testMode ? 1 : 0,
            ]));
        } catch (\Exception $e) {
            Log::error('Paysera generatePaymentUrl Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the payment callback and validate the response.
     */
    public function handlePaymentCallback($request)
    {
        try {
            return WebToPay::checkResponse($request->all(), [
                'projectid'     => $this->projectId,
                'sign_password' => $this->signPassword,
            ]);
        } catch (\Exception $e) {
            Log::error('Paysera handlePaymentCallback Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate the signature of a request or callback.
     */
    public function validateSignature($data, $signature)
    {
        try {
            return WebToPay::validateSignature($data, $signature, $this->signPassword);
        } catch (\Exception $e) {
            Log::error('Paysera validateSignature Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check the status of a payment.
     */
    public function checkPaymentStatus($response)
    {
        try {
            $status = $response['status'];
            
            if ($status == 1) {
                return 'Completed';
            } elseif ($status == 2) {
                return 'Pending';
            } else {
                return 'Failed';
            }
        } catch (\Exception $e) {
            Log::error('Paysera checkPaymentStatus Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle different payment responses.
     */
    public function handlePaymentResponse($response)
    {
        try {
            switch ($response['status']) {
                case 1:
                    return 'Payment completed';
                case 2:
                    return 'Payment pending';
                case 3:
                    return 'Payment failed';
                default:
                    return 'Unknown status';
            }
        } catch (\Exception $e) {
            Log::error('Paysera handlePaymentResponse Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate callback data.
     */
    public function validateCallbackData($requestData)
    {
        try {
            if (isset($requestData['orderid']) && isset($requestData['status'])) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Paysera validateCallbackData Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a signed URL for custom payment requests.
     */
    public function generateSignedUrl($parameters)
    {
        try {
            $parameters['sign_password'] = $this->signPassword;
            return WebToPay::buildRequest($parameters);
        } catch (\Exception $e) {
            Log::error('Paysera generateSignedUrl Error: ' . $e->getMessage());
            throw $e;
        }
    }

}
