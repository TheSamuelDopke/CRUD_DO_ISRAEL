<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAutoresTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('autores')
            ->addColumn('nome_autor', 'string', ['limit' => 255])
            ->addColumn('data_nascimento', 'date')->addColumn('nacionalidade', 'string', ['limit' => 50])
            ->create();
    }
}
