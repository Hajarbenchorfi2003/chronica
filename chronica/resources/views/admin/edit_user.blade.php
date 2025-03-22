@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Modifier l'utilisateur</h1>
    <form action="{{ route('admin.editUser', $user->id) }}" method="POST">
        @csrf
        @method('POST')
        <div class="form-group">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" class="form-control">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>
@endsection
