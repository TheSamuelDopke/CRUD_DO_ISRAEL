<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEmprestimosTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('emprestimos')
            ->addColumn('id_user', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('id_livro', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('data_emprestimo', 'date', ['null' => false])
            ->addColumn('data_devolucao', 'date', ['null' => true])
            ->addColumn('status', 'enum', [
                'values' => ['emprestado', 'devolvido'],
                'default' => 'emprestado',
                'null' => false
            ])
            ->addForeignKey('id_user', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addForeignKey('id_livro', 'livros', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();
    }
}