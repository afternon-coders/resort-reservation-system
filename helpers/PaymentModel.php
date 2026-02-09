<?php

require_once __DIR__ . '/BaseModel.php';

class PaymentModel extends BaseModel
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';

    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (reservation_id, amount, payment_method, payment_date) VALUES (:reservation_id, :amount, :payment_method, :payment_date)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':reservation_id' => $data['reservation_id'],
            ':amount' => $data['amount'],
            ':payment_method' => $data['payment_method'],
            ':payment_date' => $data['payment_date'] ?? date('Y-m-d H:i:s'),
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

        $clauses = [];
        if (!empty($opts['reservation_id'])) { $clauses[] = 'reservation_id = :reservation_id'; $params[':reservation_id'] = $opts['reservation_id']; }

        if (!empty($clauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }

        if (!empty($opts['limit'])) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
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

        if (isset($data['reservation_id'])) { $fields[] = 'reservation_id = :reservation_id'; $params[':reservation_id'] = $data['reservation_id']; }
        if (isset($data['amount'])) { $fields[] = 'amount = :amount'; $params[':amount'] = $data['amount']; }
        if (isset($data['payment_method'])) { $fields[] = 'payment_method = :payment_method'; $params[':payment_method'] = $data['payment_method']; }
        if (isset($data['payment_date'])) { $fields[] = 'payment_date = :payment_date'; $params[':payment_date'] = $data['payment_date']; }

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
