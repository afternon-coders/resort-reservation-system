# Helpers (CRUD)

This folder contains reusable PHP helper classes for database access and CRUD operations for the resort reservation system.

Files
- `DB.php` — creates a shared PDO instance using `config/database.php` variables.
- `BaseModel.php` — base class that models extend.
- `GuestModel.php`, `RoomModel.php`, `ReservationModel.php`, `PaymentModel.php`, `ServiceModel.php` — basic CRUD operations for each table.
- `test_crud.php` — example usage script.

Usage example

1. Include the model you need:

```php
require_once __DIR__ . '/GuestModel.php';
$guestModel = new GuestModel();
$id = $guestModel->create(['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '12345']);
$guest = $guestModel->getById($id);
print_r($guest);
```

Notes
- `config/database.php` must define `$db_server`, `$db_user`, `$db_pass`, and `$db_name`. The helpers use PDO and expect that `config/database.php` no longer echoes connection messages. If required, the file can be updated to remove echo statements.
