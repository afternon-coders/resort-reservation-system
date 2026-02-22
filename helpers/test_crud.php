<?php

require_once __DIR__ . '/GuestModel.php';
require_once __DIR__ . '/RoomModel.php';

try {
    $g = new GuestModel();
    $r = new RoomModel();

    // Create a guest
    $guestId = $g->create(['name' => 'Alice Test', 'email' => 'alice@example.com', 'phone' => '555-0100']);
    echo "Created guest with ID: $guestId\n";

    // Create a room
    $roomId = $r->create(['room_number' => '101', 'room_type' => 'Deluxe', 'price_per_night' => '120.00', 'number_of_beds' => 1, 'quantity' => 1, 'status' => 'available']);
    echo "Created room with ID: $roomId\n";

    // Fetch them back
    $guest = $g->getById($guestId);
    $room = $r->getById($roomId);

    echo "Guest:\n"; print_r($guest);
    echo "Room:\n"; print_r($room);

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}

