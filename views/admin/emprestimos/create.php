<?php $this->layout('layouts/admin', ['title' => 'Novo Empréstimo']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Novo Empréstimo']) ?>
    <div class="card-body">
        <form method="post" action="/admin/emprestimos/store" class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_user" class="form-label">Usuário</label>
                    <select class="form-control" id="id_user" name="id_user" required>
                        <option value="">Selecione o Usuário</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $this->e($user['id']) ?>"
                                <?= ($old['id_user'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                <?= $this->e($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_user'])): ?>
                        <div class="text-danger"><?= $this->e($errors['id_user']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="id_livro" class="form-label">Livro</label>
                    <select class="form-control" id="id_livro" name="id_livro" required>
                        <option value="">Selecione o Livro</option>
                        <?php foreach ($livros as $livro):?>
                            <option value="<?= $this->e($livro['id_livro']) ?>"
                                <?= ($old['id_livro'] ?? '') == $livro['id_livro'] ? 'selected' : '' ?>>
                                <?= $this->e($livro['titulo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['id_livro'])): ?>
                        <div class="text-danger"><?= $this->e($errors['id_livro']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_emprestimo" class="form-label">Data de Empréstimo</label>
                    <input type="date" class="form-control" id="data_emprestimo" name="data_emprestimo"
                           value="<?= $this->e(($old['data_emprestimo'] ?? date('Y-m-d'))) ?>" required>
                    <?php if (!empty($errors['data_emprestimo'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_emprestimo']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data_devolucao" class="form-label">Data de Devolução (Opcional)</label>
                    <input type="date" class="form-control" id="data_devolucao" name="data_devolucao"
                           value="<?= $this->e(($old['data_devolucao'] ?? '')) ?>">
                    <?php if (!empty($errors['data_devolucao'])): ?>
                        <div class="text-danger"><?= $this->e($errors['data_devolucao']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <?php $current_status = $old['status'] ?? 'emprestado'; ?>
                        <option value="emprestado" <?= $current_status == 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
                        <option value="devolvido" <?= $current_status == 'devolvido' ? 'selected' : '' ?>>Devolvido</option>
                    </select>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="text-danger"><?= $this->e($errors['status']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Salvar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/emprestimos" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>
