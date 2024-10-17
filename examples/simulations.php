<?php

declare(strict_types=1);

use Paddle\SDK\Entities\Event\EventTypeName;
use Paddle\SDK\Exceptions\ApiError;
use Paddle\SDK\Exceptions\SdkExceptions\MalformedResponse;
use Paddle\SDK\Notifications\Entities\Address;
use Paddle\SDK\Resources\Shared\Operations\List\Pager;
use Paddle\SDK\Resources\Simulations\Operations\CreateSimulation;
use Paddle\SDK\Resources\Simulations\Operations\ListSimulations;
use Paddle\SDK\Resources\Simulations\Operations\UpdateSimulation;

require __DIR__ . '/../vendor/autoload.php';

$environment = Paddle\SDK\Environment::tryFrom(getenv('PADDLE_ENVIRONMENT') ?: '') ?? Paddle\SDK\Environment::SANDBOX;
$apiKey = getenv('PADDLE_API_KEY') ?: null;
$simulationId = getenv('PADDLE_SIMULATION_ID') ?: null;
$notificationSettingId = getenv('PADDLE_NOTIFICATION_SETTING_ID') ?: null;

if (is_null($apiKey)) {
    echo "You must provide the PADDLE_API_KEY in the environment:\n";
    echo "PADDLE_API_KEY=your-key php examples/basic_usage.php\n";
    exit(1);
}

$paddle = new Paddle\SDK\Client($apiKey, options: new Paddle\SDK\Options($environment));

// ┌───
// │ List Simulations │
// └──────────────────┘
try {
    $simulations = $paddle->simulations->list(new ListSimulations(new Pager(perPage: 10)));
} catch (ApiError|MalformedResponse $e) {
    var_dump($e);
    exit;
}

echo "List Simulations\n";

foreach ($simulations as $simulation) {
    echo sprintf("- %s:\n", $simulation->name);
    echo sprintf("  - ID: %s\n", $simulation->id);
    echo sprintf("  - Type: %s\n", $simulation->type->getValue());
    echo sprintf("  - Notification Setting ID: %s\n", $simulation->notificationSettingId);
}

// ┌───
// │ Create Simulation │
// └───────────────────┘
try {
    $simulation = $paddle->simulations->create(
        new CreateSimulation(
            notificationSettingId: $notificationSettingId,
            type: EventTypeName::AddressCreated(),
            name: 'Simulate Address Creation',
            payload: Address::from([
                'id' => 'add_01hv8gq3318ktkfengj2r75gfx',
                'country_code' => 'US',
                'status' => 'active',
                'created_at' => '2024-04-12T06:42:58.785000Z',
                'updated_at' => '2024-04-12T06:42:58.785000Z',
                'customer_id' => 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4',
                'description' => 'Head Office',
                'first_line' => '4050 Jefferson Plaza, 41st Floor',
                'second_line' => null,
                'city' => 'New York',
                'postal_code' => '10021',
                'region' => 'NY',
            ]),
        ),
    );
} catch (ApiError|MalformedResponse $e) {
    var_dump($e);
    exit;
}

echo sprintf("Created Simulation: %s\n", $simulation->name);
echo sprintf("- ID: %s\n", $simulation->id);
echo sprintf("- Type: %s\n", $simulation->type->getValue());
echo sprintf("- Notification Setting ID: %s\n", $simulation->notificationSettingId);

// ┌───
// │ Get Simulation │
// └────────────────┘
try {
    $simulation = $paddle->simulations->get($simulationId);
} catch (ApiError|MalformedResponse $e) {
    var_dump($e);
    exit;
}

echo sprintf("Get Simulation: %s\n", $simulation->name);
echo sprintf("- ID: %s\n", $simulation->id);
echo sprintf("- Type: %s\n", $simulation->type->getValue());
echo sprintf("- Notification Setting ID: %s\n", $simulation->notificationSettingId);

// ┌───
// │ Update Simulation │
// └───────────────────┘
try {
    $simulation = $paddle->simulations->update(
        $simulationId,
        new UpdateSimulation(
            type: EventTypeName::AddressCreated(),
            payload: Address::from([
                'id' => 'add_01hv8gq3318ktkfengj2r75gfx',
                'country_code' => 'US',
                'status' => 'active',
                'created_at' => '2024-04-12T06:42:58.785000Z',
                'updated_at' => '2024-04-12T06:42:58.785000Z',
                'customer_id' => 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4',
                'description' => 'Head Office',
                'first_line' => '4050 Jefferson Plaza, 41st Floor',
                'second_line' => null,
                'city' => 'New York',
                'postal_code' => '10021',
                'region' => 'NY',
            ]),
        ),
    );
} catch (ApiError|MalformedResponse $e) {
    var_dump($e);
    exit;
}

echo sprintf("Updated Simulation: %s\n", $simulation->name);
echo sprintf("- ID: %s\n", $simulation->id);
echo sprintf("- Type: %s\n", $simulation->type->getValue());
echo sprintf("- Notification Setting ID: %s\n", $simulation->notificationSettingId);