<?php

require_once __DIR__ . '/BaseModel.php';

class RoomModel extends BaseModel
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';

    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (room_number, room_type, price_per_night, status) VALUES (:room_number, :room_type, :price_per_night, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':room_number' => $data['room_number'] ?? null,
            ':room_type' => $data['room_type'] ?? null,
            ':price_per_night' => $data['price_per_night'] ?? 0,
            ':status' => $data['status'] ?? 'available',
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(array $opts = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($opts['status'])) {
            $sql .= " WHERE status = :status";
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
        if (isset($data['room_type'])) { $fields[] = 'room_type = :room_type'; $params[':room_type'] = $data['room_type']; }
        if (isset($data['price_per_night'])) { $fields[] = 'price_per_night = :price_per_night'; $params[':price_per_night'] = $data['price_per_night']; }
        if (isset($data['status'])) { $fields[] = 'status = :status'; $params[':status'] = $data['status']; }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
