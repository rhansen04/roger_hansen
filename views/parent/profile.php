<h2 class="fw-bold mb-4" style="color:var(--dark-teal)"><i class="fas fa-user me-2"></i>Meu Perfil</h2>
<div class="card border-0 shadow-sm" style="max-width:500px">
    <div class="card-body p-4">
        <form method="POST" action="/minha-area/perfil">
            <div class="mb-3">
                <label class="form-label fw-bold">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Telefone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
            <button type="submit" class="btn" style="background:var(--dark-teal);color:white"><i class="fas fa-save me-2"></i>Salvar</button>
        </form>
    </div>
</div>
