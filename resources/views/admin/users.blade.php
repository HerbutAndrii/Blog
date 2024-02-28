@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Users</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ route('admin.user.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete-user" type="submit">
                            <i class="fa-solid fa-trash"></i>
                            <span style="margin-left: 10px">Delete user</span>    
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $users->links() }}

    <script type="text/javascript">
        $(document).ready(function () {
            $('form').submit(function (event) {
                event.preventDefault();

                var form = $(this);

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        form.closest('tr').remove();
                    },
                    error: function (error) {
                        console.log(error.responseText);
                    }
                });
            });
        });
    </script>
@endsection