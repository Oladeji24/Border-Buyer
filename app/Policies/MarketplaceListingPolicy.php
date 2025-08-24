<?php

namespace App\Policies;

use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MarketplaceListing $marketplaceListing)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role === 'seller' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MarketplaceListing $marketplaceListing)
    {
        return $user->id === $marketplaceListing->seller_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MarketplaceListing $marketplaceListing)
    {
        return $user->id === $marketplaceListing->seller_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MarketplaceListing $marketplaceListing)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MarketplaceListing  $marketplaceListing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MarketplaceListing $marketplaceListing)
    {
        return $user->role === 'admin';
    }
}
