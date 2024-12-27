<div class="pagpagination">
    <div class="count-pagpagination">
        <span>{{ $pagpagination->firstItem() }} - {{ $pagpagination->lastItem() }} of {{ $pagpagination->total() }}</span>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            {{--Previous --}}
            @if ($pagpagination->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $pagpagination->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            {{-- page --}}
            @for ($i = 1; $i <= $pagpagination->lastPage(); $i++)
                <li class="page-item {{ $pagpagination->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $pagpagination->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next --}}
            @if ($pagpagination->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $pagpagination->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
