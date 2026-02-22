<?php

require_once __DIR__ . '/BaseModel.php';

class RoomModel extends BaseModel
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';

    public function create(array $data)
    {
        $roomTypeId = $this->ensureRoomType($data['room_type'] ?? ($data['room_type_id'] ?? null));

        $sql = "INSERT INTO {$this->table} (room_number, room_type, price_per_night, number_of_beds, quantity, status) VALUES (:room_number, :room_type, :price_per_night, :number_of_beds, :quantity, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':room_number' => $data['room_number'] ?? null,
            ':room_type' => $roomTypeId,
            ':price_per_night' => $data['price_per_night'] ?? 0,
            ':number_of_beds' => $data['number_of_beds'] ?? 1,
            ':quantity' => $data['quantity'] ?? 1,
            ':status' => $data['status'] ?? 'available',
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id)
    {
        $sql = "SELECT r.*, rt.name AS room_type FROM {$this->table} r LEFT JOIN room_types rt ON r.room_type = rt.room_type_id WHERE r.{$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(array $opts = [])
    {
        $sql = "SELECT r.*, rt.name AS room_type FROM {$this->table} r LEFT JOIN room_types rt ON r.room_type = rt.room_type_id";
        $params = [];

        if (!empty($opts['status'])) {
            $sql .= " WHERE r.status = :status";
            $params[':status'] = $opts['status'];
        }

        if (!empty($opts['limit'])) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->pdo->prepare($sql);

        if (isset($params[':status'])) {
            $stmt->bindValue(':status', $params[':status']);
        }
        if (!empty($opts['limit'])) {
            $stmt->bindValue(':limit', (int)$opts['limit'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data)
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['room_number'])) { $fields[] = 'room_number = :room_number'; $params[':room_number'] = $data['room_number']; }
        if (isset($data['room_type'])) {
            $roomTypeId = $this->ensureRoomType($data['room_type']);
            $fields[] = 'room_type = :room_type';
            $params[':room_type'] = $roomTypeId;
        }
        if (isset($data['price_per_night'])) { $fields[] = 'price_per_night = :price_per_night'; $params[':price_per_night'] = $data['price_per_night']; }
        if (isset($data['status'])) { $fields[] = 'status = :status'; $params[':status'] = $data['status']; }
        if (isset($data['number_of_beds'])) { $fields[] = 'number_of_beds = :number_of_beds'; $params[':number_of_beds'] = $data['number_of_beds']; }
        if (isset($data['quantity'])) { $fields[] = 'quantity = :quantity'; $params[':quantity'] = $data['quantity']; }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Ensure we have a room_type id. Accepts an id or name; creates a default 'General' type when null.
     */
    private function ensureRoomType($value)
    {
        if (is_numeric($value) && (int)$value > 0) {
            return (int)$value;
        }

        if (is_string($value) && trim($value) !== '') {
            $name = trim($value);
            $id = $this->getRoomTypeIdByName($name);
            if ($id) return $id;
            return $this->createRoomType($name);
        }

        // fallback default
        $default = 'General';
        $id = $this->getRoomTypeIdByName($default);
        if ($id) return $id;
        return $this->createRoomType($default);
    }

    private function getRoomTypeIdByName(string $name)
    {
        $sql = "SELECT room_type_id FROM room_types WHERE name = :name LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $name]);
        $row = $stmt->fetch();
        return $row ? (int)$row['room_type_id'] : false;
    }

    private function createRoomType(string $name, string $description = null)
    {
        $sql = "INSERT INTO room_types (name, description) VALUES (:name, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $name, ':description' => $description]);
        return (int)$this->pdo->lastInsertId();
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
