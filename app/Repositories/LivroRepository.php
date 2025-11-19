<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Livro;
use PDO;

class LivroRepository {
    public function countAll(): int {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM livros");
        return (int)$stmt->fetchColumn();
    }
    public function paginate(int $page, int $perPage): array {
        $offset = ($page - 1) * $perPage;
        $stmt = Database::getConnection()->prepare("SELECT * FROM livros ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM livros WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(Livro $p): int {
        // CORREÇÃO: Adicionando 'autor_id' no INSERT e nos valores.
        $stmt = Database::getConnection()->prepare("INSERT INTO livros (editora_id, autor_id, titulo, ano_publicacao, genero, disponivel) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $p->editora_id, 
            $p->autor_id, // Campo 'autor_id' adicionado
            $p->titulo, 
            $p->ano_publicacao, 
            $p->genero, 
            $p->disponivel
        ]);
        return (int)Database::getConnection()->lastInsertId();
    }
    
    public function update(Livro $p): bool {
        // CORREÇÃO: Adicionando 'autor_id' no UPDATE e nos valores.
        $stmt = Database::getConnection()->prepare("UPDATE livros SET editora_id = ?, autor_id = ?, titulo = ?, ano_publicacao = ?, genero = ?, disponivel = ? WHERE id = ?");
        return $stmt->execute([
            $p->editora_id, 
            $p->autor_id, // Campo 'autor_id' adicionado
            $p->titulo, 
            $p->ano_publicacao, 
            $p->genero, 
            $p->disponivel, 
            $p->id
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = Database::getConnection()->prepare("DELETE FROM livros WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function findByEditoraId(int $id): ?array {
        $stmt = Database::getConnection()->prepare("SELECT * FROM livros WHERE editora_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: [];
    }

    public function findAll(): array {
    $stmt = Database::getConnection()->prepare("SELECT id AS id_livro, titulo FROM livros ORDER BY titulo ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}
}