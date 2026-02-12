<div class="row">
    <!-- Informações Básicas -->
    <div class="col-md-8">
        <div class="card card-stat mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle text-primary me-2"></i> Informações Básicas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nome da Escola <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               value="<?php echo isset($school) ? htmlspecialchars($school['name']) : ''; ?>"
                               required>
                        <small class="text-muted">Nome completo da instituição de ensino</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text"
                               class="form-control"
                               id="city"
                               name="city"
                               value="<?php echo isset($school) ? htmlspecialchars($school['city']) : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="state" class="form-label">Estado</label>
                        <select class="form-select" id="state" name="state">
                            <option value="">Selecione...</option>
                            <?php
                            $states = [
                                'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima',
                                'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                            ];
                            $currentState = isset($school) ? $school['state'] : '';
                            foreach ($states as $uf => $name) {
                                $selected = ($currentState === $uf) ? 'selected' : '';
                                echo "<option value=\"{$uf}\" {$selected}>{$uf} - {$name}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Endereço Completo</label>
                        <textarea class="form-control"
                                  id="address"
                                  name="address"
                                  rows="2"><?php echo isset($school) ? htmlspecialchars($school['address']) : ''; ?></textarea>
                        <small class="text-muted">Rua, número, bairro, CEP</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-stat mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-address-book text-primary me-2"></i> Informações de Contato
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="contact_person" class="form-label">Pessoa de Contato</label>
                        <input type="text"
                               class="form-control"
                               id="contact_person"
                               name="contact_person"
                               value="<?php echo isset($school) ? htmlspecialchars($school['contact_person']) : ''; ?>">
                        <small class="text-muted">Nome do diretor, coordenador ou responsável</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text"
                               class="form-control"
                               id="phone"
                               name="phone"
                               value="<?php echo isset($school) ? htmlspecialchars($school['phone']) : ''; ?>"
                               placeholder="(00) 0000-0000">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?php echo isset($school) ? htmlspecialchars($school['email']) : ''; ?>"
                               placeholder="contato@escola.com.br">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-stat">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-file-contract text-primary me-2"></i> Informações do Contrato
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contract_start_date" class="form-label">Data de Início</label>
                        <input type="date"
                               class="form-control"
                               id="contract_start_date"
                               name="contract_start_date"
                               value="<?php echo isset($school) ? $school['contract_start_date'] : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contract_end_date" class="form-label">Data de Término</label>
                        <input type="date"
                               class="form-control"
                               id="contract_end_date"
                               name="contract_end_date"
                               value="<?php echo isset($school) ? $school['contract_end_date'] : ''; ?>">
                    </div>

                    <div class="col-md-12">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?php echo (isset($school) && $school['status'] === 'active') ? 'selected' : ''; ?>>
                                Ativo
                            </option>
                            <option value="inactive" <?php echo (isset($school) && $school['status'] === 'inactive') ? 'selected' : ''; ?>>
                                Inativo
                            </option>
                        </select>
                        <small class="text-muted">Status do contrato da escola</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo e Ações -->
    <div class="col-md-4">
        <div class="card card-stat mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-image text-primary me-2"></i> Logo da Escola
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($school) && !empty($school['logo_url'])): ?>
                    <div class="text-center mb-3">
                        <img src="<?php echo htmlspecialchars($school['logo_url']); ?>"
                             alt="Logo Atual"
                             class="img-fluid rounded"
                             style="max-height: 150px; object-fit: contain;">
                        <p class="text-muted small mt-2">Logo atual</p>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="logo" class="form-label">
                        <?php echo (isset($school) && !empty($school['logo_url'])) ? 'Alterar Logo' : 'Upload de Logo'; ?>
                    </label>
                    <input type="file"
                           class="form-control"
                           id="logo"
                           name="logo"
                           accept="image/jpeg,image/png,image/gif">
                    <small class="text-muted d-block mt-1">
                        Formatos: JPG, PNG, GIF<br>
                        Tamanho máximo: 2MB
                    </small>
                </div>

                <?php if (isset($school) && !empty($school['logo_url'])): ?>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Deixe em branco para manter o logo atual
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card card-stat">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-hansen btn-lg">
                        <i class="fas fa-save me-2"></i>
                        <?php echo isset($school) ? 'Atualizar Escola' : 'Cadastrar Escola'; ?>
                    </button>
                    <a href="/admin/schools" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                </div>

                <?php if (!isset($school)): ?>
                    <div class="alert alert-warning small mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Campos obrigatórios:</strong> Nome da Escola
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #333;
}
.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 126, 102, 0.25);
}
</style>
