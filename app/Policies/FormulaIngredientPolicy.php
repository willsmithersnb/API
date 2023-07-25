<?php

namespace App\Policies;

use App\Models\FormulaIngredient;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormulaIngredientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\FormulaIngredient  $formulaIngredient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FormulaIngredient $formulaIngredient)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\FormulaIngredient  $formulaIngredient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FormulaIngredient $formulaIngredient)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\FormulaIngredient  $formulaIngredient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FormulaIngredient $formulaIngredient)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\FormulaIngredient  $formulaIngredient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, FormulaIngredient $formulaIngredient)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\FormulaIngredient  $formulaIngredient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, FormulaIngredient $formulaIngredient)
    {
        return false;
    }
}
