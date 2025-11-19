<?php $this->layout('layouts/admin', ['title' => 'Lista de Empréstimos']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📚 Gerenciamento de Empréstimos</h5>
        <a href="/admin/emprestimos/create" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg"></i> Novo Empréstimo
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($emprestimos)): ?>
            <div class="alert alert-info mb-0" role="alert">
                Nenhum empréstimo cadastrado.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Livro</th>
                            <th>Data Emp.</th>
                            <th>Data Dev.</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emprestimos as $emprestimo): ?>
                            <tr>
                                <td><?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?></td>
                                <td>
                                    <?php 
                                        echo $this->e($emprestimo['user_name'] ?? 'ID: ' . $emprestimo['id_user']); 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        echo $this->e($emprestimo['livro_titulo'] ?? 'ID: ' . $emprestimo['id_livro']);
                                    ?>
                                </td>
                                <td>
                                    <?= $this->e(date('d/m/Y', strtotime($emprestimo['data_emprestimo']))) ?>
                                </td>
                                <td>
                                    <?php if ($emprestimo['data_devolucao']): ?>
                                        <?= $this->e(date('d/m/Y', strtotime($emprestimo['data_devolucao']))) ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = $this->e($emprestimo['status']);
                                        $badge_class = $status == 'devolvido' ? 'bg-success' : 'bg-primary';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/admin/emprestimos/show?id=<?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?>" class="btn btn-info btn-sm" title="Ver Detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/admin/emprestimos/edit?id=<?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" title="Excluir"
                                            onclick="if(confirm('Tem certeza que deseja excluir este empréstimo?')) { document.getElementById('delete-<?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?>').submit(); }">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-<?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?>" action="/admin/emprestimos/delete" method="POST" style="display: none;">
                                            <input type="hidden" name="id_emprestimos" value="<?= $this->e($emprestimo['id_emprestimos'] ?? $emprestimo['id']) ?>">
                                            <?= \App\Core\Csrf::input() ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $this->stop() ?>