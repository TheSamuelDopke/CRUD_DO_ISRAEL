<?php

namespace App\Services;

use App\Models\Emprestimo;
use \DateTime; // Importa a classe DateTime do PHP

class EmprestimoService
{
    /**
     * Valida os dados de entrada para a criação ou atualização de um Empréstimo.
     * @param array $data Os dados a serem validados.
     * @return array Um array contendo erros de validação, se houver.
     */
    public function validate(array $data): array
    {
        $errors = [];
        // Converte para inteiros e strings para validação
        $id_user = filter_var($data['id_user'] ?? '', FILTER_VALIDATE_INT);
        $id_livro = filter_var($data['id_livro'] ?? '', FILTER_VALIDATE_INT);
        
        $data_emprestimo = trim($data['data_emprestimo'] ?? '');
        $data_devolucao = trim($data['data_devolucao'] ?? ''); // Pode ser vazio
        $status = trim($data['status'] ?? '');
        
        $valid_statuses = ['emprestado', 'devolvido'];

        // 1. Validação do ID do Usuário (id_user)
        if ($id_user === false || $id_user <= 0) {
            $errors['id_user'] = 'ID do Usuário é obrigatório e deve ser um número inteiro válido.';
        }

        // 2. Validação do ID do Livro (id_livro)
        if ($id_livro === false || $id_livro <= 0) {
            $errors['id_livro'] = 'ID do Livro é obrigatório e deve ser um número inteiro válido.';
        }

        // 3. Validação do Status
        if (!in_array($status, $valid_statuses, true)) {
            $errors['status'] = 'Status inválido. Deve ser "emprestado" ou "devolvido".';
        }

        // 4. Validação da Data de Empréstimo (Obrigatória)
        if ($data_emprestimo === '') {
            $errors['data_emprestimo'] = 'A Data de Empréstimo é obrigatória.';
        } else {
            $dateEmprestimo = DateTime::createFromFormat('Y-m-d', $data_emprestimo);
            
            if (!$dateEmprestimo || $dateEmprestimo->format('Y-m-d') !== $data_emprestimo) {
                $errors['data_emprestimo'] = 'Formato de data de empréstimo inválido. Use YYYY-MM-DD.';
            } else {
                // Se a data de empréstimo for futura, geralmente é um erro
                $today = new DateTime();
                if ($dateEmprestimo > $today) {
                    $errors['data_emprestimo'] = 'A data de empréstimo não pode ser futura.';
                }
            }
        }

        // 5. Validação da Data de Devolução (Opcional)
        if ($data_devolucao !== '') {
            $dateDevolucao = DateTime::createFromFormat('Y-m-d', $data_devolucao);
            
            if (!$dateDevolucao || $dateDevolucao->format('Y-m-d') !== $data_devolucao) {
                $errors['data_devolucao'] = 'Formato de data de devolução inválido. Use YYYY-MM-DD.';
            } 
            
            // Se ambas as datas são válidas, verifica se a devolução é depois do empréstimo
            if (!isset($errors['data_emprestimo']) && !isset($errors['data_devolucao'])) {
                if ($dateDevolucao < $dateEmprestimo) {
                    $errors['data_devolucao'] = 'A data de devolução não pode ser anterior à data de empréstimo.';
                }
            }
        }


        return $errors;
    }

    /**
     * Cria (ou constrói) uma nova instância do modelo Emprestimo.
     * @param array $data Os dados validados.
     * @return Emprestimo A instância do Emprestimo.
     */
    public function make(array $data): Emprestimo
    {
        // Limpeza e conversão dos dados
        $id = isset($data['id']) ? (int)$data['id'] : null; // Chave primária
        $id_user = (int)($data['id_user'] ?? 0);
        $id_livro = (int)($data['id_livro'] ?? 0);
        $data_emprestimo = trim($data['data_emprestimo'] ?? '');
        $data_devolucao = trim($data['data_devolucao'] ?? '');
        $status = trim($data['status'] ?? 'emprestado'); // Default para 'emprestado'
        
        // Se a data de devolução estiver vazia, passamos null (conforme o Model Emprestimo)
        $data_devolucao_model = $data_devolucao !== '' ? $data_devolucao : null;

        // Note que o construtor do Emprestimo que definimos é:
        // __construct(?int $id, int $id_user, int $id_livro, string $data_emprestimo, ?string $data_devolucao, string $status)
        return new Emprestimo(
            $id,
            $id_user,
            $id_livro,
            $data_emprestimo,
            $data_devolucao_model,
            $status
        );
    }
}