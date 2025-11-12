<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLivrosTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('livros')
            ->addColumn('editora_id', 'integer', ['signed' => false])
            ->addColumn('titulo', 'string', ['null' => false, 'limit' => 150])
            ->addColumn('ano_publicacao', 'integer', ['limit' => 4, 'null' => true])
            ->addColumn('genero', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('disponivel', 'boolean', ['default' => true])
            ->addForeignKey('editora_id', 'editoras', 'id', ['delete' => 'NO ACTION', 'update' => 'NO ACTION'])
            ->create();
    }
}
