<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEditorasTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('editoras')
            ->addColumn('nome', 'string', ['limit' => 100])
            ->addColumn('cidade', 'string', ['limit' => 100])
            ->addColumn('telefone', 'string', ['limit' => 20])
            ->create();
    }
}
