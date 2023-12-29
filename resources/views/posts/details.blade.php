@extends('layout')
@section('title', 'Details')
@include('header')
@section('content')
    <h1 style="margin-bottom: 5px">{{ $post->title }}</h1> 
    <h2 style="text-align: center;">
        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none; color: #7878bd">
            {{ $post->category->name }}
        </a>
    </h2>
    <h3 style="text-align: center; color: #555">
        {{ auth()->user()->id == $post->user->id ? 'You' : $post->user->name }} | {{ $date->format('M d, Y, H:i') }} | {{ $post->likes->count() }} likes
    </h3> 
    <fieldset>
        <img class="preview-details" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
        <p style="text-align: center; font-size: 30px">{{ $post->content }}</p>
    </fieldset>
    <div class="like">
        @if(!$like)
            <form action="{{ route('post.like', $post) }}" method="POST">
                @csrf
                <button class="button-like" type="submit">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <span style="margin-left: 10px">Like</span>
                </button>
            </form>
        @else
            <form action="{{ route('post.unlike', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="button-like" type="submit">
                    <i class="fa-solid fa-thumbs-down"></i>
                    <span style="margin-left: 10px">Unlike</span>
                </button>
            </form> 
        @endif
    </div>
    <div class="tags">
        <h2>Tags</h2>
        @if(! $post->tags()->exists())
            <div style="font-size: 30px">No tags</div>
        @else
            @foreach($post->tags as $tag)
                <a href="{{ route('tag.show', $tag) }}">{{ $tag->name}}</a>
            @endforeach
        @endif
    </div>
    @if($post->user->id == auth()->user()->id)
        <div style="display: flex; height: 54px">
            <a class="link-edit" href="{{ route('post.edit', $post) }}">
                <i class="fa-solid fa-pen-to-square"></i>  
                <span style="margin-left: 10px">Edit post</span>
            </a>
            <form action="{{ route('post.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="button-delete" type="submit">
                    <i class="fa-solid fa-trash"></i>
                    <span style="margin-left: 10px">Delete post</span>
                </button>
            </form>    
        </div>
    @endif
    <h2 style="font-size: 30px" id="comments-count">Comments ({{ $post->comments()->count() }})</h2>
    <form action="{{ route('comment.store', $post) }}" method="POST">
        @csrf
        <label>
            Add a comment <br>
            <textarea name="content" rows="5" cols="33" placeholder="Comment..."></textarea> <br>
        </label>
        @error('content')
            <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
        @enderror
        <button class="button-comment" type="submit" value="comment" name="submit">Add</button>
    </form>
    @if(! $post->comments()->exists())
        <h2 style="text-align: center;">No comments</h2>
    @else
        @foreach($post->comments->sortByDesc('updated_at') as $comment)
            <div class="comment">
                @if($comment->user->id == auth()->user()->id)
                    <div style="display: flex;">
                        <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                        <h3>You</h3>
                        <div class="date">
                            <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                        </div>
                    </div>
                    @if(session('editComment') == $comment->id)
                        <form action="{{ route('comment.update', $comment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <textarea name="edit_content" style="font-size: 20px">{{ old('edit_content', $comment->content) }}</textarea> <br>
                            @error('edit_content')
                                <div style="color: red; font-size: 20px; margin-bottom: 20px" >{{ $message }}</div>
                            @enderror
                            <button type="submit" class="button-comment" value="edit_comment" name="submit">Update</button>
                        </form>
                    @else
                        <p style="font-size: 20px">{{ $comment->content }}</p>
                        <div style="display: flex; height: 54px">
                            <a href="{{ route('comment.edit', $comment) }}" class="link-edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span style="margin-left: 10px">Edit comment</span>
                            </a>
                            <form class="delete-comment">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $comment->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                    <span style="margin-left: 10px">Delete comment</span>
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div style="display: flex;">
                        <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                        <h3>{{ $comment->user->name }}</h3>
                        <div class="date">
                            <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                        </div>
                    </div>
                    <p style="font-size: 20px">{{ $comment->content }}</p>
                @endif
            </div>
            @endforeach
    @endif
    <h1>Related posts</h1> <hr>
    @if($relatedPosts->isEmpty())
        <h2 style="text-align: center;">No related posts</h2>
    @else
        <div class="blog-cards-container">
            @foreach($relatedPosts as $post)
                <div class="blog-card">
                    <img src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
                    <h2>{{ $post->title }}</h2>
                    <h3>
                        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none; color: #7878bd">
                            {{ $post->category->name }}
                        </a>
                    </h3>
                    <h4>{{ $post->likes()->count() }} likes</h4>
                    <p>{{ strlen($post->content) > 200 ? substr($post->content, 0, 200) . '...' : $post->content }}</p>
                    <a class="details-link" href="{{ route('post.show', $post) }}">
                        <span style="margin-right: 10px">Read more</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-comment').submit(function (event) {
                event.preventDefault();

                var commentId = $(this).find('button').data('id');
                var commentElement = $(this).closest('.comment');
                var deleteCommentUrl = "{{ route('comment.destroy', ':id') }}".replace(':id', commentId);

                $.ajax({
                    type: 'DELETE',
                    url: deleteCommentUrl,
                    data: $(this).serialize(),
                    success: function (data) {
                        commentElement.remove();
                        $('#comments-count').text('Comments (' + data.commentCount + ')');

                        console.log('Comment deleted successfully');
                    },
                    error: function (error) {
                        console.log('Error:', error.responseText);
                    },
                });
            });
        });
    </script>
@endsection