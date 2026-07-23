@if($paginator->hasPages())
<div class="pagination-wrap">
    {{-- Previous --}}
    @if($paginator->onFirstPage())
        <span class="page-btn" style="opacity:.4"><i class="fas fa-chevron-left"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
    @endif

    {{-- Pages --}}
    @foreach($elements as $element)
        @if(is_string($element))
            <span class="page-btn" style="opacity:.5">...</span>
        @endif
        @if(is_array($element))
            @foreach($element as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
    @else
        <span class="page-btn" style="opacity:.4"><i class="fas fa-chevron-right"></i></span>
    @endif
</div>
@endif
