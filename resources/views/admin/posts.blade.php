@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Posts</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Likes</th>
            <th>Actions</th>
        </tr>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->likes->count() }}</td>
                <td>
                    <div style="display: flex; justify-content: center">
                        <a class="edit-post" href="{{ route('admin.post.edit', $post) }}">
                            <i class="fa-solid fa-pen-to-square"></i>  
                            <span style="margin-left: 10px">Edit post</span>
                        </a>
                        <form action="{{ route('admin.post.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="delete-post" type="submit">
                                <i class="fa-solid fa-trash"></i>
                                <span style="margin-left: 10px">Delete post</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $posts->links() }}
    
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