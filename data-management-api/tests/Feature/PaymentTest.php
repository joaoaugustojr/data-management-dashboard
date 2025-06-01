<?php

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;

it("should create a payment for an user with status pending", function () {
    $user = User::factory()->create();

    $data = [
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ];

    $response = $this->postJson(route("payments.store"), $data);

    $response->assertCreated();
    $response->assertJson(["data" => ["user_id" => $user->id, "status" => PaymentStatus::PENDING->value]]);
});

it("shouldn't create a payment for an unknown user", function () {
    $data = [
        'user_id' => "unknown uuid",
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ];

    $response = $this->postJson(route("payments.store"), $data);

    $response->assertUnprocessable()->assertJsonValidationErrors(['user_id']);
});


it("should update a payment", function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $data = [
        'user_id' => $user->id,
        'status' => PaymentStatus::PAID,
        'amount' => 100.51,
    ];

    $response = $this->putJson(route("payments.update", $payment->id), $data);

    $response->assertOk();
    $response->assertJson(['data' => ['status' => PaymentStatus::PAID->value]]);
});

it("should destroy a payment", function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $response = $this->delete(route("payments.destroy", $payment->id));

    $response->assertNoContent();
});

it("should get a payment", function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $response = $this->getJson(route("payments.show", $payment->id));

    $response->assertOk()->assertJson(["data" => ["id" => $payment->id, "user_id" => $user->id, "status" => PaymentStatus::PENDING->value]]);
});

it("should get all payments", function () {
    $users = User::factory()->create();

    $payments = Payment::factory(10)->create([
        'user_id' => $users->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $response = $this->getJson(route("payments.index"));

    $response->assertOk()->assertJsonCount(count($payments), 'data');
});

it("should get all payments with filter", function () {
    $user = User::factory()->create();

    $payments = Payment::factory(10)->create([
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $response = $this->getJson(route("payments.index"), [
        'user_id' => $user->id,
    ]);

    $response->assertOk()->assertJsonCount(count($payments), 'data');
});

it("shouldn't get payments with filter", function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    Payment::factory(10)->create([
        'user_id' => $user->id,
        'status' => PaymentStatus::PENDING,
        'amount' => 100.51,
    ]);

    $response = $this->getJson(route("payments.index", [
        'user_id' => $anotherUser->id,
    ]));

    $response->assertOk()->assertJsonCount(0, 'data');
});
