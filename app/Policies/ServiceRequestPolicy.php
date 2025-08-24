<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceRequestPolicy
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
        // All authenticated users can view service requests
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ServiceRequest $serviceRequest)
    {
        // Users can view their own service requests
        // Agents can view service requests assigned to them
        // Admins can view any service request
        return $user->id === $serviceRequest->buyer_id || 
               $user->id === $serviceRequest->agent_id || 
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
        // Only buyers can create service requests
        return $user->isBuyer();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ServiceRequest $serviceRequest)
    {
        // Buyers can update their own service requests
        // Agents can update service requests assigned to them
        // Admins can update any service request
        return $user->id === $serviceRequest->buyer_id || 
               $user->id === $serviceRequest->agent_id || 
               $user->isAdmin();
    }

    /**
     * Determine whether the user can update the status of the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateStatus(User $user, ServiceRequest $serviceRequest)
    {
        // Agents can update status of service requests assigned to them
        // Admins can update status of any service request
        return $user->id === $serviceRequest->agent_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceRequest  $serviceRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ServiceRequest $serviceRequest)
    {
        // Buyers can delete their own service requests
        // Admins can delete any service request
        // Only if status is still "order"
        return ($user->id === $serviceRequest->buyer_id || $user->isAdmin()) && 
               $serviceRequest->status === 'order';
    }
}