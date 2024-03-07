@extends('layout')
@section('title', 'Details')
@section('content')
    @include('header')
    <h1 style="margin-bottom: 5px; flex-wrap: wrap; overflow-wrap: break-word;">{{ $post->title }}</h1> 
    <h2 style="text-align: center;">
        <a href="{{ route('category.show', $post->category) }}" style="text-decoration: none; color: #7878bd">
            {{ $post->category->name }}
        </a>
    </h2>
    <h3 style="text-align: center; color: #555">
        {{ auth()->user()->id == $post->user->id ? 'You' : $post->user->name }} | {{ $date->format('M d, Y, H:i') }} | <span id="likes-count">{{ $post->likes->count() }}</span> likes
    </h3> 
    <fieldset>
        <img class="preview-details" src="{{ asset('storage/previews/' . $post->preview) }}" alt="Preview">
        <p style="text-align: center; font-size: 30px; overflow-wrap: anywhere">{{ $post->content }}</p>
    </fieldset>
    <div class="post-like">
        <form action="{{ route('post.like', $post) }}" method="POST" id="like" @style([
                'display : none' => $post->isLikedByUser()
            ])>
            @csrf
            <button class="button-like" type="submit">
                <i class="fa-regular fa-thumbs-up"></i>
                <span style="margin-left: 10px">Like</span>
            </button>
        </form>
        <form action="{{ route('post.unlike', $post) }}" method="POST" id="unlike" @style([
                'display : none' => ! $post->isLikedByUser()
            ])>
            @csrf
            @method('DELETE')
            <button class="button-like" type="submit">
                <i class="fa-solid fa-thumbs-up"></i>
                <span style="margin-left: 10px">Unlike</span>
            </button>
        </form> 
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
            <a class="edit-post" href="{{ route('post.edit', $post) }}">
                <i class="fa-solid fa-pen-to-square"></i>  
                <span style="margin-left: 10px">Edit post</span>
            </a>
            <form action="{{ route('post.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="delete-post" type="submit">
                    <i class="fa-solid fa-trash"></i>
                    <span style="margin-left: 10px">Delete post</span>
                </button>
            </form>    
        </div>
    @endif
    <h2 style="font-size: 30px" id="comments-count">Comments ({{ $post->comments()->count() }})</h2>
    <form action="{{ route('comment.store', $post) }}" method="POST" id="add-comment">
        @csrf
        <label>
            Add a comment <br>
            <textarea name="content" rows="5" cols="33" placeholder="Comment..."></textarea> <br>
        </label>
        <div style="color: red; font-size: 20px; margin-bottom: 20px; display: none" id="content-error"></div>
        <button class="button-comment" type="submit">Add</button>
    </form>
    <div id="comments">
        @if(! $post->comments()->exists())
            <h2 style="text-align: center; font-size: 30px">No comments</h2> <hr>
        @else
            @foreach($post->comments->sortByDesc('updated_at') as $comment)
                <div class="comment">
                    <fieldset>
                        @if($comment->user->id == auth()->user()->id)
                            <div style="display: flex;">
                                <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                                <h3>You</h3>
                                <div class="date">
                                    <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                                </div>
                            </div>
                            <p style="font-size: 20px; overflow-wrap: anywhere">{{ $comment->content }}</p>
                            <div style="display: flex; height: 54px">
                                <span class="edit-comment" data-id="{{ $comment->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span style="margin-left: 10px">Edit comment</span>
                                </span>
                                <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="delete-comment">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                        <span style="margin-left: 10px">Delete comment</span>
                                    </button>
                                </form>
                                <div class="comment-like" style="margin-left: 550px">
                                    <form action="{{ route('comment.like', $comment) }}" method="POST" class="like-comment" @style([
                                            'display : none' => $comment->isLikedByUser()
                                        ])>
                                        @csrf
                                        <button class="button-like" type="submit">
                                            <i class="fa-regular fa-thumbs-up"></i>
                                            <span style="margin-left: 10px">
                                                Like | 
                                                <span class="comment-likes">{{ $comment->likes->count() }}</span>
                                            </span>
                                        </button>
                                    </form>
                                    <form action="{{ route('comment.unlike', $comment) }}" method="POST" class="unlike-comment" @style([
                                            'display : none' => ! $comment->isLikedByUser()
                                        ])>
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-like" type="submit">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                            <span style="margin-left: 10px">
                                                Unlike | 
                                                <span class="comment-likes">{{ $comment->likes->count() }}</span>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div style="display: flex;">
                                <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="avatar">
                                <h3>{{ $comment->user->name }}</h3>
                                <div class="date">
                                    <strong>{{ $comment->getDateAsCarbon()->diffForHumans() }}</strong>
                                </div>
                            </div>
                            <p style="font-size: 20px; overflow-wrap: anywhere">{{ $comment->content }}</p>
                            <div class="comment-like">
                                <form action="{{ route('comment.like', $comment) }}" method="POST" class="like-comment" @style([
                                        'display : none' => $comment->isLikedByUser()
                                    ])>
                                    @csrf
                                    <button class="button-like" type="submit">
                                        <i class="fa-regular fa-thumbs-up"></i>
                                        <span style="margin-left: 10px">
                                            Like | 
                                            <span class="comment-likes">{{ $comment->likes->count() }}</span>
                                        </span>
                                    </button>
                                </form>
                                <form action="{{ route('comment.unlike', $comment) }}" method="POST" class="unlike-comment" @style([
                                        'display : none' => ! $comment->isLikedByUser()
                                    ])>
                                    @csrf
                                    @method('DELETE')
                                    <button class="button-like" type="submit">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                        <span style="margin-left: 10px">
                                            Unlike | 
                                            <span class="comment-likes">{{ $comment->likes->count() }}</span>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </fieldset>
                </div>
            @endforeach
        @endif
    </div>
    @if($relatedPosts->isEmpty())
        <h2 style="text-align: center; font-size: 30px">No related posts</h2>
    @else
        <h1>Related posts</h1> <hr>
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

    <script id="comment-template" type="text/x-handlebars-template">
        <div class="comment">
            <fieldset>
                <div style="display: flex;">
                    <img src="@{{ avatar }}" alt="avatar">
                    <h3>You</h3>
                    <div class="date">
                        <strong>Just now</strong>
                    </div>
                </div>
                <p style="font-size: 20px; overflow-wrap: anywhere">@{{ content }}</p>
                <div style="display: flex; height: 54px">
                    <span class="edit-comment" data-id="@{{ id }}">
                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                        <span style="margin-left: 10px">Edit comment</span>
                    </span>
                    <form action="@{{ deleteUrl }}" method="POST" class="delete-comment">
                        @csrf        
                        @method('DELETE')                           
                        <button type="submit">
                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            <span style="margin-left: 10px">Delete comment</span>
                        </button>
                    </form>
                    <div class="comment-like" style="margin-left: 550px">
                        <form action="@{{ likeUrl }}" method="POST" class="like-comment">
                            @csrf
                            <button class="button-like" type="submit">
                                <i class="fa-regular fa-thumbs-up"></i>
                                <span style="margin-left: 10px">
                                    Like | 
                                    <span class="comment-likes">0</span>
                                </span>
                            </button>
                        </form>
                        <form action="@{{ unlikeUrl }}" method="POST" class="unlike-comment" style="display: none">
                            @csrf
                            @method('DELETE')
                            <button class="button-like" type="submit">
                                <i class="fa-solid fa-thumbs-up"></i>
                                <span style="margin-left: 10px">
                                    Unlike | 
                                    <span class="comment-likes"></span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </fieldset>
        </div>
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#comments').on('submit', '.delete-comment', function (event) {
                event.preventDefault();

                var commentElement = $(this).closest('.comment');

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        commentElement.remove();
                        $('#comments-count').text('Comments (' + data.commentCount + ')');
                        if(data.commentCount === 0) {
                            $('#comments').append('<h2 style="text-align: center; font-size: 30px">No comments</h2> <hr>');
                        }
                    },
                    error: function (error) {
                        console.log('Error:', error.responseText);
                    }
                });
            });

            $('#add-comment').submit(function (event) {
                event.preventDefault();

                var commentContent = $(this).find('textarea');

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        var deleteCommentUrl = "{{ route('comment.destroy', ':id') }}".replace(':id', data.comment.id);
                        var likeCommentUrl = "{{ route('comment.like', ':id') }}".replace(':id', data.comment.id);
                        var unlikeCommentUrl = "{{ route('comment.unlike', ':id') }}".replace(':id', data.comment.id);

                        var template = Handlebars.compile($('#comment-template').html());
                        var html = template({
                            content: data.comment.content,
                            id: data.comment.id,
                            avatar: "{{ asset('storage/avatars') }}" + '/' + data.comment.user.avatar,
                            deleteUrl: deleteCommentUrl,
                            likeUrl: likeCommentUrl,
                            unlikeUrl: unlikeCommentUrl
                        });

                        if(data.commentCount === 1) {
                            $('#comments').html(html);
                        } else {
                            $('#comments').prepend(html);
                        }

                        commentContent.val('');

                        $('#comments-count').text('Comments (' + data.commentCount + ')');
                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $('#content-error').show();
                        $.each(error.errors, function (index, value) {
                            $('#content-error').text(value);
                        })
                        console.log(error.responseText);
                    }
                });
            });

            $('#comments').on('click', '.edit-comment', function (event) {
                event.preventDefault();

                var commentElement = $(this).closest('.comment');
                var commentContent = commentElement.find('p').text(); 
                var commentId = $(this).data('id');
                var updateCommentUrl = "{{ route('comment.update', ':id') }}".replace(':id', commentId);

                commentElement.find('p').next().hide();
                commentElement.find('p').hide();

                commentElement.find('fieldset').append(
                    '<form action="' + updateCommentUrl + '" method="POST" class="comment-update">' +
                        '@csrf' +
                        '@method("PUT")' +
                        '<textarea name="content" rows=5 style="font-size: 20px; width: 1255px">' + commentContent + '</textarea> <br>' +
                        '<div style="color: red; font-size: 20px; margin-bottom: 20px; display: none" class="edit-content-error"></div>' +
                        '<button type="submit" class="button-comment">Update</button>' +
                    '</form>'
                );
            });

            $('#comments').on('submit', '.comment-update', function (event) { 
                event.preventDefault();

                var commentContent = $(this).find('textarea');
                var commentElement = $(this).closest('.comment');
                var form = $(this);

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        form.remove();

                        commentElement.find('p').text(data.comment.content).show();
                        commentElement.find('p').next().show();
                    },
                    error: function (err) {
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            form.find('.edit-content-error').text(value);
                        });
                        form.find('.edit-content-error').show();
                    }
                });
            });

            $('#like, #unlike').submit(function (event) {
                event.preventDefault();
                
                var form = $(this);

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        form.closest('.post-like').find('#like, #unlike').toggle();
                        $('#likes-count').text(data.likes);
                    },
                    error: function (error) {
                        console.log(error.responseText);
                    }
                });
            });

            $('#comments').on('submit', '.like-comment, .unlike-comment', function (event) {
                event.preventDefault();
                
                var form = $(this);

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        form.hide();
                        if(form.hasClass('like-comment')) {
                            var newForm = form.closest('.comment-like').find('.unlike-comment');
                            newForm.find('.comment-likes').text(data.likes);
                            newForm.show();
                        } else {
                            var newForm = form.closest('.comment-like').find('.like-comment');
                            newForm.find('.comment-likes').text(data.likes); 
                            newForm.show();
                        }
                    },
                    error: function (error) {
                        console.log(error.responseText);
                    }
                });
            });
        });
    </script>
@endsection