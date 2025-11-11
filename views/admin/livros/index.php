<?php $this->layout('layouts/admin', ['title' => 'Livros']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="tableView">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-semibold">Lista de Livros</h5>
        <a href="/admin/livros/create" class="btn btn-primary" id="btnNewUser">
            <i class="bi bi-plus-lg"></i> Novo Livro
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Ano de publicação</th>
                    <th>Gênero</th>
                    <th>Disponível</th>
                    <th>Editora</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= $this->e($livro['id']) ?></td>
                        <td><?= $this->e($livro['titulo']) ?></td>
                        <td><?= $this->e($livro['ano_publicacao']) ?></td>
                        <td><?= $this->e($livro['genero']) ?></td>
                        <td><?= $livro['disponivel'] ? 'Sim' : 'Não' ?></td>
                        <td><?= $this->e($editoras[$livro['editora_id']]) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn btn-sm btn-secondary btn-edit"
                                   href="/admin/livros/show?id=<?= $this->e($livro['id']) ?>">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a class="btn btn-sm btn-primary btn-edit"
                                   href="/admin/livros/edit?id=<?= $this->e($livro['id']) ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form class="inline" action="/admin/livros/delete" method="post"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este livro? (<?= $this->e($livro['titulo']) ?>)');">
                                    <input type="hidden" name="id" value="<?= $this->e($livro['id']) ?>">
                                    <?= \App\Core\Csrf::input() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination" style="margin-top:12px;">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i == $page): ?>
            <strong>[<?= $i ?>]</strong>
        <?php else: ?>
            <a href="/?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php $this->stop() ?>
