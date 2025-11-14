<?php
namespace App\Services;

use App\Models\Autor;
use \DateTime; // Importa a classe DateTime do PHP

class AutorService {
    /**
     * Valida os dados de entrada para a criação ou atualização de um Autor.
     * @param array $data Os dados a serem validados (nome, data de nascimento, nacionalidade).
     * @return array Um array contendo erros de validação, se houver.
     */
    public function validate(array $data): array {
        $errors = [];
        $nome_autor = trim($data['nome_autor'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');

        // 1. Validação do Nome do Autor
        if ($nome_autor === '') {
            $errors['nome_autor'] = 'O nome do autor é obrigatório.';
        }

        // 2. Validação da Nacionalidade
        if ($nacionalidade === '') {
            $errors['nacionalidade'] = 'A nacionalidade é obrigatória.';
        }

        // 3. Validação da Data de Nascimento
        if ($data_nascimento === '') {
            $errors['data_nascimento'] = 'A data de nascimento é obrigatória.';
        } else {
            // Tenta criar um objeto DateTime usando o formato esperado (YYYY-MM-DD)
            $dateObject = DateTime::createFromFormat('Y-m-d', $data_nascimento);
            
            // Verifica se a data é válida E se a string original corresponde exatamente ao formato
            // O operador '!' antes de $dateObject verifica se a criação falhou (data inválida)
            if (!$dateObject || $dateObject->format('Y-m-d') !== $data_nascimento) {
                $errors['data_nascimento'] = 'Formato de data inválido. Use YYYY-MM-DD.';
            } else {
                // Verifica se a data de nascimento não é futura
                $today = new DateTime();
                if ($dateObject > $today) {
                    $errors['data_nascimento'] = 'A data de nascimento não pode ser futura.';
                }
            }
        }

        return $errors;
    }

    /**
     * Cria (ou constrói) uma nova instância do modelo Autor.
     * @param array $data Os dados validados do autor.
     * @return Autor A instância do Autor.
     */
    public function make(array $data): Autor {
        $nome_autor = trim($data['nome_autor'] ?? '');
        $data_nascimento = trim($data['data_nascimento'] ?? '');
        $nacionalidade = trim($data['nacionalidade'] ?? '');
        
        // Assume que 'id_autor' é usado para atualização, e 'id' na sua versão anterior.
        // Mantenho 'id' para compatibilidade com sua estrutura anterior.
        $id_autor = isset($data['id_autor']) ? (int)$data['id_autor'] : null;

        // Se o seu construtor espera o ID, Nome, Data de Nascimento e Nacionalidade:
        return new Autor($id_autor, $nome_autor, $data_nascimento, $nacionalidade);
    }
}