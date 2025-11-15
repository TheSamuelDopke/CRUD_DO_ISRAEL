<?php

namespace App\Models;

class Emprestimo
{
    // Corresponde a id_emprestimos INT AUTO_INCREMENT PRIMARY KEY
    public ?int $id; 
    
    // Corresponde a id_user INT NOT NULL (Chave Estrangeira)
    public int $id_user;
    
    // Corresponde a id_livro INT NOT NULL (Chave Estrangeira)
    public int $id_livro;
    
    // Corresponde a data_emprestimo DATE NOT NULL
    public string $data_emprestimo;
    
    // Corresponde a data_devolucao DATE
    public ?string $data_devolucao;
    
    // Corresponde a status ENUM('emprestado', 'devolvido') DEFAULT 'emprestado'
    // Embora seja um ENUM no banco, no PHP é geralmente representada como uma string
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
        
        // data_devolucao é nullable no banco, por isso o tipo ?string no construtor e na propriedade
        $this->data_devolucao = $data_devolucao; 
        
        $this->status = $status;
    }
}
