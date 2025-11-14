<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Autor;
use PDO;

class AutorRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM autores");
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM autores ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM autores WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(Autor $autor): int
    {
        $stmt = Database::getConnection()->prepare("INSERT INTO autores (nome_autor, data_nascimento, nacionalidade) VALUES (?, ?, ?)");
        $stmt->execute([$autor->nome_autor, $autor->data_nascimento, $autor->nacionalidade]);
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Autor $autor): bool
    {
        $stmt = Database::getConnection()->prepare("UPDATE autores SET nome_autor = ?, data_nascimento = ?, nacionalidade = ? WHERE id = ?");
        return $stmt->execute([$autor->nome_autor, $autor->data_nascimento, $autor->nacionalidade, $autor->id]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM autores WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM autores ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM autores ORDER BY id DESC");
        $stmt->execute();
        $autores = $stmt->fetchAll();
        $return = [];
        foreach ($autores as $autor) {
            $return[$autor['id']] = $autor['nome_autor'];
        }
        return $return;
    }
}