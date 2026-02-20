<?php
// Recuperar valores antigos se houver erro de validação
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

// Valores padrão
$name = $oldInput['name'] ?? ($user['name'] ?? '');
$email = $oldInput['email'] ?? ($user['email'] ?? '');
$role = $oldInput['role'] ?? ($user['role'] ?? '');
$isEdit = isset($user);
?>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">
            Nome Completo <span class="text-danger">*</span>
        </label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               value="<?php echo htmlspecialchars($name); ?>"
               required
               placeholder="Digite o nome completo">
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">
            Email <span class="text-danger">*</span>
        </label>
        <input type="email"
               class="form-control"
               id="email"
               name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               required
               placeholder="usuario@exemplo.com">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">
            Senha <?php echo $isEdit ? '' : '<span class="text-danger">*</span>'; ?>
        </label>
        <input type="password"
               class="form-control"
               id="password"
               name="password"
               <?php echo $isEdit ? '' : 'required'; ?>
               minlength="6"
               placeholder="<?php echo $isEdit ? 'Deixe em branco para manter a senha atual' : 'Mínimo 6 caracteres'; ?>">
        <?php if ($isEdit): ?>
            <small class="text-muted">Deixe em branco para não alterar a senha</small>
        <?php endif; ?>
    </div>

    <div class="col-md-6 mb-3">
        <label for="role" class="form-label">
            Perfil de Acesso <span class="text-danger">*</span>
        </label>
        <select class="form-select" id="role" name="role" required>
            <option value="">Selecione um perfil</option>
            <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>
                Administrador - Acesso total ao sistema
            </option>
            <option value="professor" <?php echo $role === 'professor' ? 'selected' : ''; ?>>
                Professor - Gerenciar alunos e observações
            </option>
            <option value="coordenador" <?php echo $role === 'coordenador' ? 'selected' : ''; ?>>
                Coordenador - Visualizar relatórios e dados
            </option>
            <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>
                Aluno - Acesso à área do aluno e cursos
            </option>
            <option value="parent" <?php echo $role === 'parent' ? 'selected' : ''; ?>>
                Responsável - Acesso ao portal dos pais
            </option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Perfis de acesso:</strong>
            <ul class="mb-0 mt-2">
                <li><strong>Administrador:</strong> Acesso total ao sistema, incluindo gerenciar usuários, escolas e configurações</li>
                <li><strong>Professor:</strong> Pode gerenciar alunos e registrar observações</li>
                <li><strong>Coordenador:</strong> Pode visualizar relatórios e dados dos alunos</li>
                <li><strong>Aluno:</strong> Acesso à área do aluno e aos cursos matriculados</li>
                <li><strong>Responsável:</strong> Acesso ao portal dos pais para acompanhar os filhos</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-hansen">
            <i class="fas fa-save me-2"></i> Salvar
        </button>
        <a href="/admin/users" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i> Cancelar
        </a>
    </div>
</div>
