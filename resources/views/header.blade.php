<header class="site-header">
  <div class="avatar-user-name">
    <div class="avatar">
        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
    </div>
    <div class="user-name">
      <h2>{{ auth()->user()->name }}</h2>
    </div>
  </div>
  <nav class="main-menu">
    <ul>
      @if(auth()->user()->hasVerifiedEmail())
        <form class="search" action="{{ route('search') }}" method="POST">
            @csrf
            <input id="search" type="text" name="search" placeholder="Search posts...">
            <div id="search-results" class="dropdown-content"></div>
        </form>
        <div class="header-links">
            <li><a href="{{ route('post.create') }}">Create post</a></li>
            <li><a href="{{ route('post.index') }}">All posts</a></li>
            <li><a href="{{ route('post.user.index') }}">My posts</a></li>
        </div>
      @endif
      <li>
        <form action="{{ route('auth.logout') }}" method="POST">
          @csrf
          <button class="logout" type="submit">Log out</button>
        </form>
      </li>
    </ul>
  </nav>
</header>

<script type="text/javascript">
  $(document).ready(function () {
    $('#search').keyup(function () {
      if($(this).val() == '') {
        $('#search-results').html('');
        $('#search-results').hide();
      } else {
          $.ajax({
          type: 'GET',
          url: "{{ route('search') }}",
          data: {
            search: $(this).val()
          },
          success: function (data) {
            $('#search-results').empty();
            $.each(data, function(index, post) {
              let url = "{{ route('post.show', ':id') }}".replace(':id', post.id);
              $('#search-results').append('<a href="' + url + '">' + post.title + '</a> <br>');
            })
            $('#search-results').show();
          }
        });
      }
    });
  });
</script>