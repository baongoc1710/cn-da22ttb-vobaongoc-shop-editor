@if ($paginator->hasPages())
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        {{-- Nút Previous --}}
        @if ($paginator->onFirstPage())
            <a class="pagination-previous" disabled>Trang trước</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous">Trang trước</a>
        @endif

        {{-- Nút Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next">Trang sau</a>
        @else
            <a class="pagination-next" disabled>Trang sau</a>
        @endif

        <ul class="pagination-list">
            {{-- Các phần tử phân trang --}}
            @foreach ($elements as $element)
                {{-- Dấu ba chấm "..." --}}
                @if (is_string($element))
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                @endif

                {{-- Mảng các link số trang --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <a class="pagination-link is-current" aria-label="Page {{ $page }}" aria-current="page">{{ $page }}</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="pagination-link" aria-label="Goto page {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif