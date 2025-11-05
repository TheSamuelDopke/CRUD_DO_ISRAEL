<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('categories')
            ->addColumn('nome_autor', 'string', ['limit' => 50])
            ->addColumn('tetx', 'string', ['limit' => 200])
            ->create();
    }
}
