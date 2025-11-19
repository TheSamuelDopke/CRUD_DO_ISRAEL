<?php 
$this->layout('layouts/admin', ['title' => 'Detalhes do Empréstimo']) 
?>

<?php $this->start('body') ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detalhes do Empréstimo #<?= $this->e($emprestimo->id) ?></h5>
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Usuário (ID: <?= $this->e($emprestimo->id_user) ?>):</strong></label>
                        <input type="text" class="form-control" 
                            value="<?= $this->e($user['name'] ?? 'Usuário Não Encontrado/Inexistente') ?>" readonly>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Livro (ID: <?= $this->e($emprestimo->id_livro) ?>):</strong></label>
                        <input type="text" class="form-control" 
                            value="<?= $this->e($livro['titulo'] ?? 'Livro Não Encontrado/Inexistente') ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Autor:</strong></label>
                        <input type="text" class="form-control" 
                            value="<?= $this->e($autor['nome_autor'] ?? 'Autor Não Encontrado/Inexistente') ?>" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Data de Empréstimo:</strong></label>
                        <input type="text" class="form-control"
                            value="<?= $this->e(date('d/m/Y', strtotime($emprestimo->data_emprestimo))) ?>" readonly>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Data de Devolução:</strong></label>
                        <input type="text" class="form-control"
                            value="<?= $this->e($emprestimo->data_devolucao ? date('d/m/Y', strtotime($emprestimo->data_devolucao)) : 'Ainda não devolvido') ?>" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Status:</strong></label>
                        <input type="text" class="form-control" value="<?= $this->e($emprestimo->status) ?>" readonly>
                    </div>
                </div>

                <div class="text-end">
                    <a href="/admin/emprestimos/edit?id=<?= $this->e($emprestimo->id) ?>" class="btn btn-warning me-2">Editar</a>
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar à Lista</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->stop() ?>