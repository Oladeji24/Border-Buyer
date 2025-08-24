<?php

namespace App\Policies;

use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentProfilePolicy
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AgentProfile  $agentProfile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AgentProfile $agentProfile)
    {
        // Users can view their own agent profile
        // Anyone can view a verified agent profile
        return $user->id === $agentProfile->user_id || 
               $agentProfile->verification_status === 'verified' || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Only users with agent role can create agent profiles
        // And they should not have an existing profile
        return $user->role === 'agent' && !$user->agentProfile;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AgentProfile  $agentProfile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AgentProfile $agentProfile)
    {
        // Users can update their own agent profile
        return $user->id === $agentProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AgentProfile  $agentProfile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AgentProfile $agentProfile)
    {
        // Users can delete their own agent profile or admin can delete any
        return $user->id === $agentProfile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can approve agent profiles.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user)
    {
        // Only admin can approve agent profiles
        return $user->isAdmin();
    }
}