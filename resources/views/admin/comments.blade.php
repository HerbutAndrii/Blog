@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Comments</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
        @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->content }}</td>
                <td>{{ $comment->user->name }}</td>
                <td>
                    <div style="margin-top: 10px;">
                        <a href="{{ route('admin.comment.edit', $comment) }}" class="edit-comment">
                            <i class="fa-solid fa-pen-to-square"></i>  
                            <span style="margin-left: 10px">Edit comment</span>
                        </a>
                        <form action="{{ route('admin.comment.destroy', $comment) }}" method="POST" class="delete-comment">
                            @csrf
                            @method('DELETE')
                            <button type="submit">
                                <i class="fa-solid fa-trash"></i>
                                <span style="margin-left: 10px">Delete comment</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $comments->links() }}

    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-comment').submit(function (event) {
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