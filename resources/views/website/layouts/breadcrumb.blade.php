<div class="row mt-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach ($breadcrumbs as $item)
                    @if($item['url'] == url()->full())
                        <li class="breadcrumb-item active">{{ $item['label'] }}</li>
                    @else 
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>          
    </div>
</div>