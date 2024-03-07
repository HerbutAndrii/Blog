@extends('layout')
@section('title', 'Admin')
@section('content')
    @include('header')
    <h1>Categories</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <div style="display: flex; justify-content: center">
                        <a href="{{ route('admin.category.edit', $category) }}" class="edit-category" data-id="{{ $category->id }}">
                            <i class="fa-solid fa-pen-to-square"></i>  
                            <span style="margin-left: 10px">Edit category</span>
                        </a>
                        <form action="{{ route('admin.category.destroy', $category) }}" method="POST" class="delete-category-form">
                            @csrf
                            @method('DELETE')
                            <button class="delete-category" type="submit">
                                <i class="fa-solid fa-trash"></i>
                                <span style="margin-left: 10px">Delete category</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $categories->links() }}
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-category-form').submit(function (event) {
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