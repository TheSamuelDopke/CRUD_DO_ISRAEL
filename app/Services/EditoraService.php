<?php
namespace App\Services;

use App\Models\Editora;

class EditoraService {
    public function validate(array $data): array {
        $errors = [];
        $nome = trim($data['nome'] ?? '');

        if ($name === '') $errors['name'] = 'Nome é obrigatório';

        if ($name === '') $errors['name'] = 'Nome é obrigatório';

        if ($name === '') $errors['name'] = 'Nome é obrigatório';

        return $errors;
    }

    public function make(array $data): Editora {
        $name = trim($data['name'] ?? '');
        $text = trim($data['text'] ?? '');
        $id = isset($data['id']) ? (int)$data['id'] : null;
        return new Editora($id, $name, $text);
    }
}

//Loucura