<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class CreateEmprestimosTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Define a tabela 'emprestimos'
        $table = $this->table('emprestimos');

        // Adiciona as chaves estrangeiras (INT NOT NULL)
        // No Phinx, 'integer' é o tipo padrão, e o 'signed' => false ajuda a refletir o INT UNSIGNED
        $table->addColumn('id_user', 'integer', [
            'signed' => false,
            'null' => false
        ])
        ->addColumn('id_livro', 'integer', [
            'signed' => false,
            'null' => false
        ]);

        // Adiciona as colunas de data
        $table->addColumn('data_emprestimo', 'date', [
            'null' => false
        ])
        ->addColumn('data_devolucao', 'date', [
            'null' => true // DATE (pode ser NULL)
        ]);

        // Adiciona a coluna status como ENUM
        // O Phinx não tem um tipo 'enum' nativo em todos os adaptadores,
        // mas você pode usar o tipo 'enum' e passar as opções.
        // Se o seu adaptador não suportar 'enum', pode-se usar 'string' com uma verificação
        // ou o método 'addColumn' com o tipo 'enum'. Aqui usamos 'enum' que é comum
        // em adaptadores MySQL/PostgreSQL.
        $table->addColumn('status', 'enum', [
            'values' => ['emprestado', 'devolvido'],
            'default' => 'emprestado',
            'null' => false
        ]);
        
        // Adiciona as definições de Chave Estrangeira (FOREIGN KEY)
        // Assume-se que 'user' tem uma coluna 'id'
        $table->addForeignKey('id_user', 'user', 'id', [
            'delete' => 'RESTRICT', 
            'update' => 'CASCADE'
        ]);

        // Assume-se que 'livro' tem uma coluna 'id_livro'
        $table->addForeignKey('id_livro', 'livro', 'id', [
            'delete' => 'RESTRICT', 
            'update' => 'CASCADE'
        ]);

        // Cria a tabela no banco de dados
        $table->create();
    }
}