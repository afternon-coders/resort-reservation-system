<?php
/**
 * Seed Script: Add default rooms to database
 * Run: php scripts/seed_rooms.php
 */

require_once __DIR__ . '/../helpers/RoomModel.php';

// Rooms to add
$rooms = [
    [
        'room_number' => '101',
        'room_type' => 'A Frame',
        'price_per_night' => 6000,
        'number_of_beds' => 2,
        'quantity' => 2,
        'status' => 'available'
    ],
    [
        'room_number' => '102',
        'room_type' => 'Cottage',
        'price_per_night' => 2000,
        'number_of_beds' => 1,
        'quantity' => 4,
        'status' => 'available'
    ],
    [
        'room_number' => '103',
        'room_type' => 'Bird House',
        'price_per_night' => 1500,
        'number_of_beds' => 1,
        'quantity' => 3,
        'status' => 'available'
    ],
    [
        'room_number' => '104',
        'room_type' => 'Tree House',
        'price_per_night' => 1500,
        'number_of_beds' => 1,
        'quantity' => 2,
        'status' => 'available'
    ]
];

try {
    $roomModel = new RoomModel();
    $added = 0;

    foreach ($rooms as $room) {
        try {
            $roomModel->create($room);
            echo "✓ Added: {$room['room_type']} (Room #{$room['room_number']}) - ₱{$room['price_per_night']}/night\n";
            $added++;
        } catch (Exception $e) {
            echo "✗ Error adding {$room['room_type']}: " . $e->getMessage() . "\n";
        }
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Seeding complete! Added $added room(s).\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
