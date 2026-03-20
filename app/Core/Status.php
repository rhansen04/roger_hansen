<?php

namespace App\Core;

/**
 * Constantes de status para os modulos pedagogicos.
 * Fonte unica da verdade — evita strings literais espalhadas pelo codigo.
 */
final class Status
{
    // Status compartilhados (observacoes, pareceres, portfolios)
    const IN_PROGRESS         = 'in_progress';
    const FINALIZED           = 'finalized';
    const REVISION_REQUESTED  = 'revision_requested';

    // Status de planejamento
    const SUBMITTED  = 'submitted';
    const APPROVED   = 'approved';
    const REJECTED   = 'rejected';

    // Listas para validacao
    const PEDAGOGICAL_STATUSES = [
        self::IN_PROGRESS,
        self::FINALIZED,
        self::REVISION_REQUESTED,
    ];

    const PLANNING_STATUSES = [
        self::IN_PROGRESS,
        self::SUBMITTED,
        self::APPROVED,
        self::REJECTED,
    ];

    private function __construct() {}
}
