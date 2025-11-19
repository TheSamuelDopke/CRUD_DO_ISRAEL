<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Emprestimo; 
use PDO;

class EmprestimoRepository
{
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM emprestimos");
        return (int)$stmt->fetchColumn();
    }

public function paginate(int $page, int $perPage): array
{
    $offset = ($page - 1) * $perPage;
    $sql = "
        SELECT 
            e.*, 
            u.name AS user_name,  /* Nome do usuário */
            l.titulo AS livro_titulo /* Título do livro */
        FROM 
            emprestimos e
        JOIN 
            users u ON u.id = e.id_user
        JOIN 
            livros l ON l.id = e.id_livro
        ORDER BY 
            e.id DESC 
        LIMIT :limit OFFSET :offset
    ";
    $stmt = Database::getConnection()->prepare($sql);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public function find(int $id): ?Emprestimo
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return new Emprestimo(
            (int)($data['id'] ?? 0),
            (int)($data['id_user'] ?? 0),
            (int)($data['id_livro'] ?? 0),
            $data['data_emprestimo'] ?? '',
            $data['data_devolucao'] ?? null,
            $data['status'] ?? ''
        );
    }

    public function create(Emprestimo $emprestimo): int
    {
        $sql = "INSERT INTO emprestimos (id_user, id_livro, data_emprestimo, data_devolucao, status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        
        $stmt->execute([
            $emprestimo->id_user, 
            $emprestimo->id_livro, 
            $emprestimo->data_emprestimo, 
            $emprestimo->data_devolucao, 
            $emprestimo->status
        ]);
        
        return (int)Database::getConnection()->lastInsertId();
    }

    public function update(Emprestimo $emprestimo): bool
    {
        $sql = "UPDATE emprestimos SET id_user = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ?, status = ? 
                WHERE id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        
        return $stmt->execute([
            $emprestimo->id_user, 
            $emprestimo->id_livro, 
            $emprestimo->data_emprestimo, 
            $emprestimo->data_devolucao, 
            $emprestimo->status,
            $emprestimo->id 
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare("DELETE FROM emprestimos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getArray(): array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos ORDER BY id DESC");
        $stmt->execute();
        $emprestimos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $return = [];
        foreach ($emprestimos as $emprestimo) {
            $return[$emprestimo['id']] = "Livro #{$emprestimo['id_livro']} para User #{$emprestimo['id_user']} ({$emprestimo['status']})";
        }
        return $return;
    }
}