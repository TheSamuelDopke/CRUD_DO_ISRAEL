<?php

namespace App\Models;

class Editora
{
    public ?int $id_editora;
    public string $nome;
    public string $cidade;
    public string $telefone;

    public function __construct(?int $id_editora, string $nome, string $cidade, string $telefone)
    {
        $this->id_editora = $id_editora;
        $this->nome = $nome;
        $this->cidade = $cidade;
        $this->telefone = $telefone;
    }
}
