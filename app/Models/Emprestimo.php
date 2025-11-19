<?php

namespace App\Models;

class Emprestimo
{
    public ?int $id; 
    
    public int $id_user;
    
    public int $id_livro;
    
    public string $data_emprestimo;
    
    public ?string $data_devolucao;
    
    public string $status; 

    public function __construct(
        ?int $id, 
        int $id_user, 
        int $id_livro, 
        string $data_emprestimo, 
        ?string $data_devolucao, 
        string $status
    ) {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_livro = $id_livro;
        $this->data_emprestimo = $data_emprestimo;
        
        $this->data_devolucao = $data_devolucao; 
        
        $this->status = $status;
    }
}
