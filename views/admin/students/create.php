<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/students" class="text-decoration-none">Alunos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Novo Aluno</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/students" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar para listagem</a>
    <h2 class="text-primary fw-bold mt-3">CADASTRAR NOVO ALUNO</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/admin/students/create" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo do Aluno</label>
                        <input type="text" name="name" class="form-control" placeholder="Digite o nome do aluno" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data de Nascimento</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Escola / Unidade</label>
                            <select name="school_id" class="form-select" required>
                                <option value="">Selecione uma escola</option>
                                <?php foreach ($schools as $school): ?>
                                    <option value="<?php echo $school['id']; ?>"><?php echo $school['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 border-start">
                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold d-block text-start ps-3">Foto do Aluno</label>
                        <div class="mt-2">
                            <i class="fas fa-user-circle fa-8x text-light mb-3"></i>
                            <input type="file" name="photo" class="form-control form-control-sm">
                            <p class="small text-muted mt-2">Formatos aceitos: JPG, PNG. Máx 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light me-2">Limpar</button>
                <button type="submit" class="btn btn-hansen px-5">SALVAR CADASTRO</button>
            </div>
        </form>
    </div>
</div>
