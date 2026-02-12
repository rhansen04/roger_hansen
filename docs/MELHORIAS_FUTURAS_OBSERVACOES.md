# Melhorias Futuras - Sistema de Observações Pedagógicas

## Roadmap de Funcionalidades Adicionais

---

## 1. Sistema de Anexos

### Objetivo
Permitir upload de fotos, documentos e outros arquivos relacionados às observações.

### Implementação Sugerida

#### 1.1 Atualizar Banco de Dados
```sql
-- Adicionar coluna de anexos na tabela observations
ALTER TABLE observations ADD COLUMN attachments TEXT;

-- Formato: JSON array com informações dos arquivos
-- Exemplo: [{"name":"foto.jpg","path":"/uploads/obs/foto.jpg","type":"image/jpeg"}]
```

#### 1.2 Controller - Método store() modificado
```php
public function store()
{
    // ... código existente ...

    // Processar uploads
    $attachments = [];
    if (!empty($_FILES['attachments']['name'][0])) {
        $uploadDir = __DIR__ . '/../../../public/uploads/observations/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['attachments']['error'][$key] === 0) {
                $fileName = time() . '_' . basename($_FILES['attachments']['name'][$key]);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $attachments[] = [
                        'name' => $_FILES['attachments']['name'][$key],
                        'path' => '/uploads/observations/' . $fileName,
                        'type' => $_FILES['attachments']['type'][$key],
                        'size' => $_FILES['attachments']['size'][$key]
                    ];
                }
            }
        }
    }

    $data['attachments'] = json_encode($attachments);

    // ... resto do código ...
}
```

#### 1.3 View - Formulário create.php
```html
<div class="mb-3">
    <label class="form-label fw-bold">Anexos (Fotos, Documentos)</label>
    <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,.pdf,.doc,.docx">
    <small class="form-text text-muted">
        Formatos aceitos: JPG, PNG, PDF, DOC. Máximo 5MB por arquivo.
    </small>
</div>
```

#### 1.4 View - Exibição em show.php
```php
<?php if (!empty($observation['attachments'])): ?>
    <?php $attachments = json_decode($observation['attachments'], true); ?>
    <div class="mb-4">
        <h5 class="text-secondary fw-bold mb-3">
            <i class="fas fa-paperclip me-2"></i> Anexos
        </h5>
        <div class="row">
            <?php foreach ($attachments as $file): ?>
                <div class="col-md-3 mb-3">
                    <?php if (strpos($file['type'], 'image') !== false): ?>
                        <a href="<?php echo $file['path']; ?>" target="_blank">
                            <img src="<?php echo $file['path']; ?>" class="img-fluid rounded" alt="<?php echo $file['name']; ?>">
                        </a>
                    <?php else: ?>
                        <a href="<?php echo $file['path']; ?>" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file me-2"></i>
                            <?php echo $file['name']; ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
```

---

## 2. Sistema de Notificações

### Objetivo
Notificar pais e responsáveis sobre novas observações importantes.

### Implementação Sugerida

#### 2.1 Criar Tabela de Notificações
```sql
CREATE TABLE notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    observation_id INTEGER,
    type VARCHAR(50),
    title VARCHAR(255),
    message TEXT,
    read_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (observation_id) REFERENCES observations(id)
);
```

#### 2.2 Model Notification.php
```php
<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;

class Notification
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function create($data)
    {
        $sql = "INSERT INTO notifications (user_id, observation_id, type, title, message, created_at)
                VALUES (:user_id, :observation_id, :type, :title, :message, :created_at)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':observation_id' => $data['observation_id'],
            ':type' => $data['type'],
            ':title' => $data['title'],
            ':message' => $data['message'],
            ':created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getUnreadByUser($userId)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = ? AND read_at IS NULL ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($id)
    {
        $sql = "UPDATE notifications SET read_at = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([date('Y-m-d H:i:s'), $id]);
    }
}
```

#### 2.3 Enviar Notificação ao Criar Observação
```php
// No ObservationController.php, método store():

if ($obsModel->create($data)) {
    // Criar notificação para os pais (se tipo for importante)
    if (in_array($data['type'], ['Saúde', 'Comportamento'])) {
        $student = $studentModel->find($data['student_id']);

        // Assumindo que existe campo parent_user_id em students
        if ($student['parent_user_id']) {
            $notificationModel = new \App\Models\Notification();
            $notificationModel->create([
                'user_id' => $student['parent_user_id'],
                'observation_id' => $obsModel->lastInsertId(),
                'type' => 'observation',
                'title' => 'Nova Observação: ' . $data['type'],
                'message' => 'Foi registrada uma nova observação sobre ' . $student['name']
            ]);
        }
    }

    $_SESSION['success_message'] = 'Observação criada com sucesso!';
    // ...
}
```

---

## 3. Exportação para PDF

### Objetivo
Gerar relatórios PDF das observações de um aluno.

### Implementação Sugerida

#### 3.1 Instalar Biblioteca TCPDF
```bash
composer require tecnickcom/tcpdf
```

#### 3.2 Controller - Método exportPDF()
```php
public function exportPDF($studentId)
{
    require_once(__DIR__ . '/../../../vendor/autoload.php');

    $studentModel = new Student();
    $obsModel = new Observation();

    $student = $studentModel->find($studentId);
    $observations = $obsModel->findByStudent($studentId);

    // Criar PDF
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

    $pdf->SetCreator('Hansen Educacional');
    $pdf->SetAuthor('Sistema de Observações');
    $pdf->SetTitle('Relatório de Observações - ' . $student['name']);

    $pdf->SetHeaderData('', 0, 'Hansen Educacional', 'Relatório de Observações Pedagógicas');
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);

    $pdf->AddPage();

    $html = '<h1>Relatório de Observações</h1>';
    $html .= '<h2>Aluno: ' . htmlspecialchars($student['name']) . '</h2>';
    $html .= '<p>Data de Nascimento: ' . date('d/m/Y', strtotime($student['birth_date'])) . '</p>';
    $html .= '<p>Escola: ' . htmlspecialchars($student['school_name']) . '</p>';
    $html .= '<hr>';

    foreach ($observations as $obs) {
        $html .= '<h3>' . htmlspecialchars($obs['type']) . '</h3>';
        $html .= '<p><strong>Data:</strong> ' . date('d/m/Y', strtotime($obs['observed_at'])) . '</p>';
        $html .= '<p><strong>Professor:</strong> ' . htmlspecialchars($obs['teacher_name']) . '</p>';
        $html .= '<p>' . nl2br(htmlspecialchars($obs['content'])) . '</p>';
        $html .= '<hr>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('observacoes_' . $student['name'] . '.pdf', 'D');
    exit;
}
```

#### 3.3 Rota
```php
$router->get('/admin/observations/export/{studentId}', [AdminObservationController::class, 'exportPDF']);
```

#### 3.4 Botão na View
```html
<a href="/admin/observations/export/<?php echo $student['id']; ?>" class="btn btn-outline-danger">
    <i class="fas fa-file-pdf me-2"></i> Exportar PDF
</a>
```

---

## 4. Busca Avançada

### Objetivo
Buscar observações por palavras-chave no conteúdo.

### Implementação Sugerida

#### 4.1 Model - Método search()
```php
public function search($keyword)
{
    try {
        $sql = "SELECT o.*, u.name as teacher_name, s.name as student_name
                FROM observations o
                JOIN users u ON o.user_id = u.id
                JOIN students s ON o.student_id = s.id
                WHERE o.content LIKE :keyword
                   OR o.type LIKE :keyword
                   OR s.name LIKE :keyword
                ORDER BY o.observed_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar observações: " . $e->getMessage());
        return [];
    }
}
```

#### 4.2 Controller - Método search()
```php
public function search()
{
    $keyword = $_GET['q'] ?? '';

    if (empty($keyword)) {
        header('Location: /admin/observations');
        exit;
    }

    $obsModel = new Observation();
    $observations = $obsModel->search($keyword);

    return $this->render('observations/index', [
        'observations' => $observations,
        'searchKeyword' => $keyword
    ]);
}
```

#### 4.3 View - Formulário de busca
```html
<div class="mb-4">
    <form method="GET" action="/admin/observations/search" class="row g-2">
        <div class="col-md-10">
            <input type="text" name="q" class="form-control" placeholder="Buscar por palavra-chave..." value="<?php echo $searchKeyword ?? ''; ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i> Buscar
            </button>
        </div>
    </form>
</div>
```

---

## 5. Dashboard de Estatísticas

### Objetivo
Exibir gráficos e estatísticas sobre as observações.

### Implementação Sugerida

#### 5.1 Controller - Método stats()
```php
public function stats()
{
    $obsModel = new Observation();

    $stats = [
        'total' => $obsModel->countTotal(),
        'byType' => $obsModel->countByType(),
        'recent' => $obsModel->recentObservations(10),
        'byMonth' => $this->getObservationsByMonth()
    ];

    return $this->render('observations/stats', $stats);
}

private function getObservationsByMonth()
{
    $obsModel = new Observation();
    // Query para agrupar por mês
    $sql = "SELECT strftime('%Y-%m', observed_at) as month, COUNT(*) as total
            FROM observations
            GROUP BY month
            ORDER BY month DESC
            LIMIT 12";
    // ... implementar query ...
}
```

#### 5.2 View - stats.php com Chart.js
```html
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Observações por Categoria</h5>
                <canvas id="chartByType"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Observações por Mês</h5>
                <canvas id="chartByMonth"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico por tipo
const ctxType = document.getElementById('chartByType').getContext('2d');
new Chart(ctxType, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_column($byType, 'type')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($byType, 'total')); ?>,
            backgroundColor: ['#007e66', '#ffb606', '#dc3545', '#ffc107', '#6c757d']
        }]
    }
});

// Gráfico por mês
const ctxMonth = document.getElementById('chartByMonth').getContext('2d');
new Chart(ctxMonth, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($byMonth, 'month')); ?>,
        datasets: [{
            label: 'Observações',
            data: <?php echo json_encode(array_column($byMonth, 'total')); ?>,
            borderColor: '#007e66',
            tension: 0.1
        }]
    }
});
</script>
```

---

## 6. Tags e Etiquetas

### Objetivo
Permitir adicionar tags personalizadas às observações para melhor organização.

### Implementação Sugerida

#### 6.1 Criar Tabelas
```sql
CREATE TABLE tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    color VARCHAR(7) DEFAULT '#6c757d',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE observation_tags (
    observation_id INTEGER,
    tag_id INTEGER,
    PRIMARY KEY (observation_id, tag_id),
    FOREIGN KEY (observation_id) REFERENCES observations(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
```

#### 6.2 Model Tag.php
```php
<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;

class Tag
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function all()
    {
        $sql = "SELECT * FROM tags ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function attachToObservation($observationId, $tagId)
    {
        $sql = "INSERT OR IGNORE INTO observation_tags (observation_id, tag_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$observationId, $tagId]);
    }

    public function getByObservation($observationId)
    {
        $sql = "SELECT t.* FROM tags t
                JOIN observation_tags ot ON t.id = ot.tag_id
                WHERE ot.observation_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$observationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

#### 6.3 View - Seletor de Tags
```html
<div class="mb-3">
    <label class="form-label fw-bold">Tags</label>
    <select name="tags[]" class="form-select" multiple>
        <?php foreach ($tags as $tag): ?>
            <option value="<?php echo $tag['id']; ?>">
                <?php echo htmlspecialchars($tag['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <small class="text-muted">Selecione uma ou mais tags para categorizar</small>
</div>
```

---

## 7. Histórico de Alterações

### Objetivo
Rastrear todas as modificações feitas em uma observação.

### Implementação Sugerida

#### 7.1 Criar Tabela
```sql
CREATE TABLE observation_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    observation_id INTEGER,
    user_id INTEGER,
    action VARCHAR(50),
    old_value TEXT,
    new_value TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (observation_id) REFERENCES observations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### 7.2 Registrar Alterações
```php
// No método update() do ObservationController:

// Antes de atualizar, salvar estado anterior
$oldObservation = $obsModel->find($id);

if ($obsModel->update($id, $data)) {
    // Registrar mudanças
    $historyModel = new ObservationHistory();
    $historyModel->create([
        'observation_id' => $id,
        'user_id' => $_SESSION['user_id'],
        'action' => 'update',
        'old_value' => json_encode($oldObservation),
        'new_value' => json_encode($data)
    ]);
    // ...
}
```

---

## Priorização Sugerida

1. **Alta Prioridade**
   - ✅ Sistema de Anexos (muito solicitado por usuários)
   - ✅ Exportação para PDF (importante para reuniões)

2. **Média Prioridade**
   - ⚠️ Busca Avançada (melhora usabilidade)
   - ⚠️ Notificações (engajamento dos pais)

3. **Baixa Prioridade**
   - 🔹 Tags e Etiquetas (nice to have)
   - 🔹 Dashboard de Estatísticas (analytics)
   - 🔹 Histórico de Alterações (auditoria)

---

**Documento criado em:** 10/02/2026
**Versão:** 1.0
**Status:** Sugestões para futuras implementações
