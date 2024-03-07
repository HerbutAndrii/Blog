@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Tags</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        @foreach($tags as $tag)
            <tr>
                <td>{{ $tag->id }}</td>
                <td>{{ $tag->name }}</td>
                <td>
                    <div style="display: flex; justify-content: center">
                        <a href="{{ route('admin.tag.edit', $tag) }}" class="edit-tag">
                            <i class="fa-solid fa-pen-to-square"></i>  
                            <span style="margin-left: 10px">Edit tag</span>
                        </a>
                        <form action="{{ route('admin.tag.destroy', $tag) }}" method="POST" class="delete-tag-form">
                            @csrf
                            @method('DELETE')
                            <button class="delete-category" type="submit">
                                <i class="fa-solid fa-trash"></i>
                                <span style="margin-left: 10px">Delete tag</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $tags->links() }}
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-tag-form').submit(function (event) {
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