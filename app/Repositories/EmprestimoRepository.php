<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Emprestimo; // Importa o Model Emprestimo
use PDO;

class EmprestimoRepository
{
    // Conta todos os registros na tabela emprestimos
    public function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM emprestimos");
        return (int)$stmt->fetchColumn();
    }

    // Retorna uma página de empréstimos
    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        // Ordena pela nova chave primária 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Emprestimo::class); // Retorna como objetos Emprestimo
    }

    // Busca um empréstimo pelo seu ID
    public function find(int $id): ?Emprestimo
    {
        // Alterado 'id_emprestimos' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetchObject(Emprestimo::class);
        return $row instanceof Emprestimo ? $row : null;
    }

    // Cria um novo empréstimo no banco de dados
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

    // Atualiza um empréstimo existente
    public function update(Emprestimo $emprestimo): bool
    {
        $sql = "UPDATE emprestimos SET id_user = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ?, status = ? 
                WHERE id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        
        // Agora acessa $emprestimo->id (Assumindo que o Model Emprestimo também foi atualizado)
        return $stmt->execute([
            $emprestimo->id_user, 
            $emprestimo->id_livro, 
            $emprestimo->data_emprestimo, 
            $emprestimo->data_devolucao, 
            $emprestimo->status,
            $emprestimo->id // Chave primária para o WHERE
        ]);
    }

    // Deleta um empréstimo pelo ID
    public function delete(int $id): bool
    {
        // Alterado 'id_emprestimos' para 'id'
        $stmt = Database::getConnection()->prepare("DELETE FROM emprestimos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Retorna todos os empréstimos como objetos Emprestimo
    public function findAll(): array
    {
        // Alterado 'id_emprestimos' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos ORDER BY id DESC");
        $stmt->execute();
        // Retorna um array de objetos Emprestimo
        return $stmt->fetchAll(PDO::FETCH_CLASS, Emprestimo::class);
    }

    // Retorna um array associativo (id => Descrição do Empréstimo)
    public function getArray(): array
    {
        // Alterado 'id_emprestimos' para 'id'
        $stmt = Database::getConnection()->prepare("SELECT * FROM emprestimos ORDER BY id DESC");
        $stmt->execute();
        $emprestimos = $stmt->fetchAll();
        $return = [];
        foreach ($emprestimos as $emprestimo) {
            // Mapeia o ID do empréstimo (agora 'id') para uma descrição relevante
            $return[$emprestimo['id']] = "Livro #{$emprestimo['id_livro']} para User #{$emprestimo['id_user']} ({$emprestimo['status']})";
        }
        return $return;
    }
}