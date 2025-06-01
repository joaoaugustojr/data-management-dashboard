<?php

namespace App\Http\Controllers;

use App\Actions\Payment\CreatePayment;
use App\Actions\Payment\UpdatePayment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(): PaymentCollection
    {
        return new PaymentCollection(Payment::filter()->orderBy('created_at', 'desc')->with('user')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $payment = CreatePayment::handle($data);
        $payment->load('user');

        return $this->response(Response::HTTP_CREATED, new PaymentResource($payment));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): JsonResponse
    {
        return $this->response(Response::HTTP_OK, new PaymentResource($payment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $data = $request->validated();
        $payment = UpdatePayment::handle($payment, $data);
        $payment->load('user');

        return $this->response(Response::HTTP_OK, new PaymentResource($payment));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return $this->response(Response::HTTP_NO_CONTENT);
    }
}
