<?php

namespace App\Controllers\Admin;

use App\Models\PlanningTemplate;

class PlanningTemplateController
{
    public function index()
    {
        $model = new PlanningTemplate();
        $templates = $model->all();

        return $this->render('planning-templates/index', [
            'templates' => $templates
        ]);
    }

    public function create()
    {
        return $this->render('planning-templates/edit', [
            'template' => null,
            'sections' => []
        ]);
    }

    public function store()
    {
        if (empty($_POST['title'])) {
            $_SESSION['error_message'] = 'Informe o título do template.';
            header('Location: /admin/planning-templates/create');
            exit;
        }

        $model = new PlanningTemplate();
        $id = $model->create($_POST);

        if ($id) {
            $_SESSION['success_message'] = 'Template criado! Agora adicione as seções e campos.';
            header("Location: /admin/planning-templates/{$id}/edit");
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar template.';
        header('Location: /admin/planning-templates/create');
        exit;
    }

    public function edit($id)
    {
        $model = new PlanningTemplate();
        $template = $model->getWithSectionsAndFields($id);

        if (!$template) {
            $_SESSION['error_message'] = 'Template não encontrado.';
            header('Location: /admin/planning-templates');
            exit;
        }

        return $this->render('planning-templates/edit', [
            'template' => $template,
            'sections' => $template['sections'] ?? []
        ]);
    }

    public function update($id)
    {
        $model = new PlanningTemplate();
        if (!$model->find($id)) {
            $_SESSION['error_message'] = 'Template não encontrado.';
            header('Location: /admin/planning-templates');
            exit;
        }

        if (empty($_POST['title'])) {
            $_SESSION['error_message'] = 'Informe o título do template.';
            header("Location: /admin/planning-templates/{$id}/edit");
            exit;
        }

        if ($model->update($id, $_POST)) {
            $_SESSION['success_message'] = 'Template atualizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar template.';
        }
        header("Location: /admin/planning-templates/{$id}/edit");
        exit;
    }

    public function delete($id)
    {
        $model = new PlanningTemplate();
        if ($model->delete($id)) {
            $_SESSION['success_message'] = 'Template excluído com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir template.';
        }
        header('Location: /admin/planning-templates');
        exit;
    }

    // --- Sections ---

    public function addSection($templateId)
    {
        $model = new PlanningTemplate();
        if (!$model->find($templateId)) {
            $_SESSION['error_message'] = 'Template não encontrado.';
            header('Location: /admin/planning-templates');
            exit;
        }

        if (empty($_POST['title'])) {
            $_SESSION['error_message'] = 'Informe o título da seção.';
            header("Location: /admin/planning-templates/{$templateId}/edit");
            exit;
        }

        $_POST['template_id'] = $templateId;
        if ($model->createSection($_POST)) {
            $_SESSION['success_message'] = 'Seção adicionada!';
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar seção.';
        }
        header("Location: /admin/planning-templates/{$templateId}/edit");
        exit;
    }

    public function updateSection($sectionId)
    {
        $model = new PlanningTemplate();
        $section = $model->findSection($sectionId);
        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/planning-templates');
            exit;
        }

        $model->updateSection($sectionId, $_POST);
        $_SESSION['success_message'] = 'Seção atualizada!';
        header("Location: /admin/planning-templates/{$section['template_id']}/edit");
        exit;
    }

    public function deleteSection($sectionId)
    {
        $model = new PlanningTemplate();
        $section = $model->findSection($sectionId);
        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/planning-templates');
            exit;
        }

        $model->deleteSection($sectionId);
        $_SESSION['success_message'] = 'Seção excluída!';
        header("Location: /admin/planning-templates/{$section['template_id']}/edit");
        exit;
    }

    // --- Fields ---

    public function addField($sectionId)
    {
        $model = new PlanningTemplate();
        $section = $model->findSection($sectionId);
        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/planning-templates');
            exit;
        }

        if (empty($_POST['label'])) {
            $_SESSION['error_message'] = 'Informe o rótulo do campo.';
            header("Location: /admin/planning-templates/{$section['template_id']}/edit");
            exit;
        }

        $_POST['section_id'] = $sectionId;

        // Process options for checklist/select/radio fields
        if (!empty($_POST['options_text']) && in_array($_POST['field_type'] ?? '', ['checklist_group', 'select', 'radio', 'checkbox'])) {
            $options = array_filter(array_map('trim', explode("\n", $_POST['options_text'])));
            $_POST['options_json'] = json_encode(array_values($options), JSON_UNESCAPED_UNICODE);
        }

        if ($model->createField($_POST)) {
            $_SESSION['success_message'] = 'Campo adicionado!';
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar campo.';
        }
        header("Location: /admin/planning-templates/{$section['template_id']}/edit");
        exit;
    }

    public function deleteField($fieldId)
    {
        $model = new PlanningTemplate();
        $field = $model->findField($fieldId);
        if (!$field) {
            $_SESSION['error_message'] = 'Campo não encontrado.';
            header('Location: /admin/planning-templates');
            exit;
        }

        $section = $model->findSection($field['section_id']);
        $model->deleteField($fieldId);
        $_SESSION['success_message'] = 'Campo excluído!';
        header("Location: /admin/planning-templates/{$section['template_id']}/edit");
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Página {$view} em construção</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
