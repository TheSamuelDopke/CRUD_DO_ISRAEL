<?php
namespace App\Services;

use App\Models\Autor;
use \DateTime; 

class AutorService {
    

     
    public function validate(array $data): array {
        $errors = [];
        $nome_autor = trim($data['nome_autor'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');


        if ($nome_autor === '') {
            $errors['nome_autor'] = 'O nome do autor é obrigatório.';
        }


        if ($nacionalidade === '') {
            $errors['nacionalidade'] = 'A nacionalidade é obrigatória.';
        }

        if ($data_nascimento === '') {
            $errors['data_nascimento'] = 'A data de nascimento é obrigatória.';
        } else {

            $dateObject = DateTime::createFromFormat('Y-m-d', $data_nascimento);
            
            if (!$dateObject || $dateObject->format('Y-m-d') !== $data_nascimento) {
                $errors['data_nascimento'] = 'Formato de data inválido. Use YYYY-MM-DD.';
            } else {
                $today = new DateTime();
                if ($dateObject > $today) {
                    $errors['data_nascimento'] = 'A data de nascimento não pode ser futura.';
                }
            }
        }

        return $errors;
    }


    public function make(array $data): Autor {
        $nome_autor = trim($data['nome_autor'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');

        $id_autor = isset($data['id_autor']) ? (int)$data['id_autor'] : null;


        return new Autor($id_autor, $nome_autor, $data_nascimento, $nacionalidade);
    }
}