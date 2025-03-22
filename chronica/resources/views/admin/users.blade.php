@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Gestion des Utilisateurs</h1>
    <a href="{{ route('admin.createUser') }}" class="btn btn-primary mb-3">Ajouter un utilisateur</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.editUser', $user->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
