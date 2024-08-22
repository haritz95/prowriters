<form method="GET" action="{{ route('faq_search') }}">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="{{ __('Find solutions for your homework') }}" name="q">
        <div class="input-group-append">
          <button class="btn btn-success text-white" type="submit">
            <i class="fa fa-search"></i> {{ __('Search')}}
          </button>
        </div>
      </div>
</form>