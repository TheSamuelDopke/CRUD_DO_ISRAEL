<?php $this->layout('layouts/admin', ['title' => 'Novo Livro']) ?>

<?php $this->start('body') ?>
<div class="card shadow-sm" id="formView">
    <?php $this->insert('partials/admin/form/header', ['title' => 'Novo Livro']) ?>
    <div class="card-body">
        <form method="post" action="/admin/livros/store" enctype="multipart/form-data" class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título"
                        value="<?= $this->e(($old['genero'] ?? '')) ?>" required>
                    <?php if (!empty($errors['genero'])): ?>
                        <div class="text-danger"><?= $this->e($errors['titulo']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ano_publicacao" class="form-label">Ano de publicação</label>
                    <input type="number" step="0.01" class="form-control" id="ano_publicacao" name="ano_publicacao"
                        placeholder="Digite o preço" value="<?= $this->e(($old['ano_publicacao'] ?? '')) ?>" required>
                    <?php if (!empty($errors['ano_publicacao'])): ?>
                        <div class="text-danger"><?= $this->e($errors['ano_publicacao']) ?></div><?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">Gênero</label>
                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o gênero"
                        value="<?= $this->e(($old['genero'] ?? '')) ?>" required>
                    <?php if (!empty($errors['genero'])): ?>
                        <div class="text-danger"><?= $this->e($errors['genero']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="disponivel" name="disponivel"
                            value="1" <?= !empty($old['disponivel']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="disponivel">
                            Disponível
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editora_id" class="form-label">Editora</label>
                    <select class="form-select" id="editora_id" name="editora_id" required>
                        <option value="">Selecione uma editora</option>
                        <?php foreach ($editoras as $editora): ?>
                            <option value="<?= $editora['id'] ?>" <?= $this->e(($old['editora_id'] ?? '') == $editora['id'] ? 'selected' : '') ?>>
                                <?= $this->e($editora['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['editora_id'])): ?>
                        <div class="error"><?= $this->e($errors['editora_id']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">

                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg"></i> Salvar
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </button>
                <a href="/admin/products" class="btn align-self-end">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
            <?= \App\Core\Csrf::input() ?>
        </form>
    </div>
</div>
<?php $this->stop() ?>