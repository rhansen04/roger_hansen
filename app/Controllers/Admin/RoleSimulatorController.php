<?php

namespace App\Controllers\Admin;

class RoleSimulatorController
{
    /**
     * Simular visao de outro perfil (apenas admin real)
     */
    public function simulate()
    {
        $realRole = $_SESSION['user_role'] ?? '';

        if ($realRole !== 'admin') {
            $_SESSION['error_message'] = 'Apenas administradores podem simular perfis.';
            header('Location: /admin/dashboard');
            exit;
        }

        $targetRole = $_POST['simulated_role'] ?? '';
        $allowedRoles = ['professor', 'coordenador'];

        if (!in_array($targetRole, $allowedRoles)) {
            $_SESSION['error_message'] = 'Perfil de simulacao invalido.';
            header('Location: /admin/dashboard');
            exit;
        }

        $_SESSION['simulated_role'] = $targetRole;
        $_SESSION['success_message'] = 'Simulando visao de ' . ucfirst($targetRole) . '.';
        header('Location: /admin/dashboard');
        exit;
    }

    /**
     * Voltar para visao de admin (limpar simulacao)
     */
    public function reset()
    {
        $realRole = $_SESSION['user_role'] ?? '';

        if ($realRole !== 'admin') {
            header('Location: /admin/dashboard');
            exit;
        }

        unset($_SESSION['simulated_role']);
        $_SESSION['success_message'] = 'Voltou para a visao de Administrador.';
        header('Location: /admin/dashboard');
        exit;
    }
}
