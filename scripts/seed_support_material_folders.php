<?php
/**
 * Seed: Criar estrutura inicial de pastas de Material de Apoio
 *
 * Uso: php scripts/seed_support_material_folders.php
 */

require_once __DIR__ . '/../public/index.php';

// Re-initialize without routing
$db = \App\Core\Database\Connection::getInstance();

$folders = [
    [
        'name' => 'Eixos de Atividades',
        'slug' => 'eixos-de-atividades',
        'sort_order' => 1,
        'children' => [
            ['name' => 'Manuais', 'slug' => 'manuais', 'sort_order' => 1],
            ['name' => 'Musicais', 'slug' => 'musicais', 'sort_order' => 2],
            ['name' => 'Contos', 'slug' => 'contos', 'sort_order' => 3],
            ['name' => 'Movimento', 'slug' => 'movimento', 'sort_order' => 4],
        ]
    ],
    [
        'name' => 'Centros de Aprendizagem',
        'slug' => 'centros-de-aprendizagem',
        'sort_order' => 2,
        'children' => []
    ],
    [
        'name' => 'Familias de Brinquedos',
        'slug' => 'familias-de-brinquedos',
        'sort_order' => 3,
        'children' => []
    ]
];

echo "Criando estrutura de pastas de Material de Apoio...\n\n";

foreach ($folders as $folder) {
    // Check if already exists
    $stmt = $db->prepare("SELECT id FROM support_material_folders WHERE slug = :slug AND parent_id IS NULL");
    $stmt->execute([':slug' => $folder['slug']]);
    $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($existing) {
        echo "  [SKIP] {$folder['name']} (ja existe, id={$existing['id']})\n";
        $parentId = $existing['id'];
    } else {
        $stmt = $db->prepare("INSERT INTO support_material_folders (parent_id, name, slug, sort_order) VALUES (NULL, :name, :slug, :sort_order)");
        $stmt->execute([
            ':name' => $folder['name'],
            ':slug' => $folder['slug'],
            ':sort_order' => $folder['sort_order']
        ]);
        $parentId = $db->lastInsertId();
        echo "  [OK] {$folder['name']} (id={$parentId})\n";
    }

    foreach ($folder['children'] as $child) {
        $stmt = $db->prepare("SELECT id FROM support_material_folders WHERE slug = :slug AND parent_id = :parent_id");
        $stmt->execute([':slug' => $child['slug'], ':parent_id' => $parentId]);
        $existingChild = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($existingChild) {
            echo "    [SKIP] {$child['name']} (ja existe)\n";
        } else {
            $stmt = $db->prepare("INSERT INTO support_material_folders (parent_id, name, slug, sort_order) VALUES (:parent_id, :name, :slug, :sort_order)");
            $stmt->execute([
                ':parent_id' => $parentId,
                ':name' => $child['name'],
                ':slug' => $child['slug'],
                ':sort_order' => $child['sort_order']
            ]);
            echo "    [OK] {$child['name']} (id={$db->lastInsertId()})\n";
        }
    }
}

echo "\nEstrutura de pastas criada com sucesso!\n";
