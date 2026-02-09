<?php

require_once __DIR__ . '/DB.php';

abstract class BaseModel
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->pdo = DB::getPDO();
    }

    protected function fetchAll($stmt)
    {
        $stmt->execute();
        return $stmt->fetchAll();
    }

    protected function fetchOne($stmt)
    {
        $stmt->execute();
        return $stmt->fetch();
    }
}
