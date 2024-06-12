@extends('layout')
@section('title', 'Posts')
@section('content')
    @include('header')
    @isset($user)
        <div class="user-avatar">
            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="avatar">
        </div>
        <h1 style="margin-top: 5px; margin-bottom: 0px">{{ $user->name }}</h1>
        <h3 class="subscribers">
            <span id="subscribers">{{ $user->subscribers()->count() }}</span> subscribers
        </h3>
        <div class="subscription">
            <form action="{{ route('subscribe', $user) }}" method="POST" id="subscribe" @style([
                    'display : none' => $user->hasSubscriber(auth()->user())
                ])>
                @csrf
                <button class="subscribe">Subscribe</button> 
            </form>
            <form action="{{ route('unsubscribe', $user) }}" method="POST" id="unsubscribe" @style([
                    'display : none' => ! $user->hasSubscriber(auth()->user())
                ])>
                @csrf
                <button class="subscribe">Unsubscribe</button> 
            </form>
        </div> <hr>
    @else
        <h1>{{ $title }}</h1> <hr>
    @endisset
    @isset($posts)
        <div class="blog-cards-container">
            @foreach($posts as $post)
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
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
        @if(method_exists($posts, 'links'))
            {{ $posts->links() }}
        @endif
    @endisset

    <script type="text/javascript">
        $(document).ready(function () {
            $('#subscribe, #unsubscribe').submit(function (event) {
                event.preventDefault();

                var form = $(this);

                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        form.closest('.subscription').find('#subscribe, #unsubscribe').toggle();
                        $('#subscribers').text(data.subscribers);
                    },
                    error: function (error) {
                        console.log(error.responseText);
                    }
                });
            });
        });
    </script>
@endsection