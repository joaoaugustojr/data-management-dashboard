<?php

use App\Models\User;

it("should get 10 users", function () {
    $users = User::factory(10)->create();

    $response = $this->getJson(route("users.index"));

    $response->assertOk()->assertJsonCount(count($users), 'data');
});

it("should get user", function () {
    $user = User::factory()->create();

    $response = $this->getJson(route("users.show", $user->id));

    $response->assertOk()->assertJson(["id" => $user->id]);
});
