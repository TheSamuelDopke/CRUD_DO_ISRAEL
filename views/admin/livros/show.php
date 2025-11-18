<?php $this->layout('layouts/admin', ['title' => 'Detalhes do Livro']) ?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes do Livro</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label"><strong>ID:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['id']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Título:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['titulo']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Ano de publicação:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['ano_publicacao']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Gênero:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['genero']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Disponível:</strong></label>
                    <input type="text" class="form-control"
                        value="<?= $livro['disponivel'] ? 'Sim' : 'Não' ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Editora:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['editora_nome']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Autor:</strong></label>
                    <input type="text" class="form-control" value="<?= $this->e($livro['autor_nome']) ?>" readonly>
                </div>
                <div class="text-end">
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>