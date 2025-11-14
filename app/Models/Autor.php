<?php

namespace App\Models;

class Autor
{
    public ?int $id;
    public string $nome_autor;
    public string $data_nascimento;

    public string $nacionalidade;

    public function __construct(?int $id, string $nome_autor, string $data_nascimento, string $nacionalidade)
    {
        $this->id = $id;
        $this->nome_autor = $nome_autor;
        $this->data_nascimento = $data_nascimento;
        $this->nacionalidade = $nacionalidade;
    }
}