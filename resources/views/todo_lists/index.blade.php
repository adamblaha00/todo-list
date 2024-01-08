@extends('layouts.app')

@section('content')
    <h1>Seznam úkolů</h1>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Název</th>
            <th>Popis</th>
            <th>Splnit do</th>
            <th>Splněno</th>
            <th>Akce</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($todoLists as $todoList)
            <tr>
                <form method="POST" action="{{ route('todo_lists.update', $todoList->id) }}" class="d-inline">
                    @csrf
                    <td>{{ $todoList->id }}</td>
                    <td>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{$todoList->name}}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" value="{{$todoList->description}}" name="description">
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
                        @enderror
                    </td>
                    <td>
                        <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" id="due_date" value="{{$todoList->due_date->format('Y-m-d\TH:i')}}" name="due_date" required
                        @error('due_date')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </td>
                    <td>
                        <select class="form-control" id="is_done" name="is_done">
                            <option value="0" @if($todoList->is_done === 0) selected @endif>Ne</option>
                            <option value="1" @if($todoList->is_done === 1) selected @endif>Ano</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">Upravit</button>
                </form>
                <form method="POST" action="{{ route('todo_lists.destroy', $todoList->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Smazat</button>
                </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Vytvořit nový úkol</h2>

    <form action="{{ route('todo_lists.store') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="name">Název</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
            @error('name')
            <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Popis</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description">
            @error('description')
            <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="due_date">Do</label>
            <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" required>
            @error('due_date')
            <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Vytvořit</button>
    </form>

    @if (session()->has('message'))
        <script>
            function showAlert(type, message) {
                Swal.fire({
                    icon: type,
                    title: 'Oznámení',
                    text: message,
                    showConfirmButton: false,
                    timer: 3000,
                });
            }

            showAlert("{!! session()->get('alertType') !!}", "{!! session()->get('message') !!}");
        </script>
    @endif
@endsection
