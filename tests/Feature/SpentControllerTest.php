<?php

use App\Models\Spent;
use App\Models\User;
use App\Notifications\SpentCreatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{postJson,actingAs,getJson};

uses()->group('feature')
    ->group('SpentController')
    ->group('SpentControllerFeature')
    ->in('SpentControllerFeature');

beforeEach(function () {
    $this->userStub = User::factory()->create([
        'name' => fake()->name(),
        'email' =>  fake()->email(),
        'password' => Hash::make('any_pass'),
    ]);
});

describe('SpentControllerFeature@store feature', function (){

    beforeEach(function () {
        Notification::fake();

        $this->requestStub =([
            'description' => 'any description',
            'value' => 1000
        ]);
    });

    it('returns 401 if token is not provided', function() {
        $response = postJson('/api/spents', []);
        $response->assertStatus(401);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('Unauthenticated.');
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 422 if data is not provied', function() {
        actingAs($this->userStub);
        $response = postJson('/api/spents', []);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('A descrição deve ser informada. (and 1 more error)');
        expect(sizeof($responseBody['errors']))->toBe(2);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 422 if description has more than 191 characters', function() {
        actingAs($this->userStub);
        $response = postJson('/api/spents', [...$this->requestStub, 'description' => fake()->password(200, 201)]);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('A descrição pode ter no máximo 191 caracteres.');
        expect(sizeof($responseBody['errors']))->toBe(1);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 422 if value provided is less than 1', function() {
        actingAs($this->userStub);
        $response = postJson('/api/spents', [...$this->requestStub, 'value' => 0]);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('O valor deve ser maior que 0 (zero).');
        expect(sizeof($responseBody['errors']))->toBe(1);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 422 if spentAt is greather than now', function() {
        $currentDateTime = Carbon::now();
        $oneMinuteLater = $currentDateTime->addMinute(1);
        $formattedOneMinuteLater= $oneMinuteLater->format('Y-m-d H:i');
        actingAs($this->userStub);
        $response = postJson('/api/spents', [...$this->requestStub, 'spentAt' => $formattedOneMinuteLater]);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('A data da despesa deve ser menor ou igual a data atual.');
        expect(sizeof($responseBody['errors']))->toBe(1);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 201 when succeds with spentAt as now date', function() {
        actingAs($this->userStub);
        $response = postJson('/api/spents', $this->requestStub);
        $response->assertStatus(201);
        $responseBody = $response->json();
        Notification::assertSentTo(
            $this->userStub,
            SpentCreatedNotification::class
        );
        expect($responseBody['data']['description'])->toBe('any description');
        expect($responseBody['data']['value'])->toBe(1000);
        $this->assertIsString($responseBody['data']['spentAt']);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');

    it('returns 201 when succeds with spentAt provided by user', function() {
        $currentDateTime = Carbon::now();
        $tenDaysAgo = $currentDateTime->subDays(10);
        $formattedTenDaysAgo = $tenDaysAgo->format('Y-m-d H:i');
        actingAs($this->userStub);
        $response = postJson('/api/spents', [...$this->requestStub, 'spentAt' => $formattedTenDaysAgo, 'value' => 23025 ]);
        $response->assertStatus(201);
        $responseBody = $response->json();
        Notification::assertSentTo(
            $this->userStub,
            SpentCreatedNotification::class
        );
        expect($responseBody['data']['description'])->toBe('any description');
        expect($responseBody['data']['value'])->toBe(23025);
        expect($responseBody['data']['spentAt'])->toBe($formattedTenDaysAgo);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@store');
});

describe('SpentControllerFeature@index feature', function (){

    it('returns 401 if token is not provided', function() {
        $response = getJson('/api/spents');
        $response->assertStatus(401);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('Unauthenticated.');
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@index');

    it('returns 200 when succeds without any spent registered', function() {
        actingAs($this->userStub);
        $response = getJson('/api/spents');
        $response->assertStatus(200);
        $responseBody = $response->json();
        expect($responseBody['meta']['current_page'])->toBe(1);
        expect($responseBody['meta']['per_page'])->toBe(10);
        expect(sizeof($responseBody['data']))->toBe(0);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@index');

    it('returns 200 when succeds with 2 spent', function() {
        Spent::factory()->create([
            'user_id' => $this->userStub->id,
            'description' => 'any description',
            'value' => 5000,
            'spent_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        Spent::factory()->create([
            'user_id' => $this->userStub->id,
            'description' => 'any description',
            'value' => 10000,
            'spent_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        actingAs($this->userStub);
        $response = getJson('/api/spents');
        $response->assertStatus(200);
        $responseBody = $response->json();
        expect($responseBody['meta']['current_page'])->toBe(1);
        expect($responseBody['meta']['per_page'])->toBe(10);
        expect(sizeof($responseBody['data']))->toBe(2);
    })->group('unit')->group('SpentController')->group('SpentControllerFeature')->group('SpentController@index');
});
