@if($paginator->hasPages())
    <div class="pagination">
        @if($paginator->onFirstPage())
            <span>&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if(is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
        @else
            <span>&rsaquo;</span>
        @endif
    </div>
@endif