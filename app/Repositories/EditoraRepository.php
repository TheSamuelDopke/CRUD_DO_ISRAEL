<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Editora;
use PDO;

class EditoraRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM editoras");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM editoras ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM editoras WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(Editora $editora): int
    {
        $stmt = Database::getConnection()->prepare("INSERT INTO editoras (nome, cidade, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$editora->nome, $editora->cidade, $editora->telefone]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Editora $editora): bool
    {
        $stmt = Database::getConnection()->prepare("UPDATE editoras SET nome = ?, cidade = ?, telefone = ? WHERE id = ?");
        return $stmt->execute([$editora->nome, $editora->cidade, $editora->telefone, $editora->id]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM editoras WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM editoras ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM editoras ORDER BY id DESC");
        $stmt->execute();
        $editoras = $stmt->fetchAll();
        $return = [];
        foreach ($editoras as $editora) {
            $return[$editora['id']] = $editora['nome'];
        }
        return $return;
    }
}
