@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit User: {{ $user->name }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="user" @if($user->role === 'user') selected @endif>User</option>
                        <option value="agent" @if($user->role === 'agent') selected @endif>Agent</option>
                        <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
