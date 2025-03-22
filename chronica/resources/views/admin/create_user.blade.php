@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Créer un utilisateur</h1>
    <form action="{{ route('admin.createUser') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" class="form-control">
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Créer</button>
    </form>
</div>
@endsection
