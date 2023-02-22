<?php

class UserService
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM users
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO users (name, age)
                VALUES (:name, :age)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":age", $data["age"], PDO::PARAM_INT);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update(array $current, array $new): int
    {
        $sql = "UPDATE users
                SET name = :name, age= :age
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":age", $new["age"] ?? $current["age"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM users
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
