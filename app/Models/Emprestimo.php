<?php

namespace App\Models;

use \InvalidArgumentException;

class Emprestimo
{

    public const STATUS_EMPRESTADO = 'emprestado';

    public const STATUS_DEVOLVIDO = 'devolvido';

    public ?int $id_emprestimo;

    public ?int $id_user;

    public ?int $id_editora;

    public string $data_emprestimo;

    public string $data_devolucao;

    public string $status = self::STATUS_EMPRESTADO;



    public function __construct(?int $id_emprestimo, int $id_user, int $id_editora, string $data_emprestimo, ?string $data_devolucao = null, string $status = self::STATUS_EMPRESTADO)
    {
        $this->id_emprestimo = $id_emprestimo;
        $this->id_user = $id_user;
        $this->id_editora = $id_editora;
        $this->data_emprestimo = $data_emprestimo;

        $this->data_devolucao = $data_devolucao;

        $this->setStatus($status);
    }


        public function setStatus(string $novoStatus): void{
        if(!in_array($novoStatus, [self::STATUS_EMPRESTADO, self::STATUS_DEVOLVIDO])){
            throw new InvalidArgumentException("Status Inválido: {$novoStatus}");
        }
        $this->status = $novoStatus;
    }
}
