<?php

// app/Policies/OrderPolicy.php
namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // ¿Puede ver todas las órdenes? Solo admin
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    // ¿Puede ver esta orden específica?
    public function view(User $user, Order $order): bool
    {
        // Admin puede ver cualquier orden
        // Customer solo puede ver sus propias órdenes
        return $user->isAdmin() || $user->id === $order->user_id;
    }

    // ¿Puede crear órdenes? Cualquier usuario autenticado
    public function create(User $user): bool
    {
        return true;
    }

    // ¿Puede actualizar esta orden?
    public function update(User $user, Order $order): bool
    {
        // Solo admin puede cambiar el status de una orden
        return $user->isAdmin();
    }

    // ¿Puede cancelar esta orden?
    public function cancel(User $user, Order $order): bool
    {
        // Admin siempre puede; customer solo si está pending
        return $user->isAdmin() ||
               ($user->id === $order->user_id && $order->status === 'pending');
    }

    // ¿Puede eliminar?
    public function delete(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }
}