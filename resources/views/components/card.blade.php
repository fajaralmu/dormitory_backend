<div id="{{ $id ?? str_slug($title) }}" class="card{{ $extra_class ?? '' }}" style="margin-bottom: 20px;">
  @if(isset($title))
    <header class="card-header">
      <p class="card-header-title">
        {!! $title !!}
      </p>
      <a href="#" class="card-header-icon" aria-label="more options">
        <span class="icon">
          <i class="fas fa-angle-down" aria-hidden="true"></i>
        </span>
      </a>
    </header>
  @endif

  @if(isset($image))
    <div class="card-image">
      <figure class="image">
        <img src="{{ $image }}">
      </figure>
    </div>
  @endif

  <div class="card-content">
    {{ $slot }}
  </div>

  @if(isset($footer))
    <footer class="card-footer">
      @foreach($footer as $url => $label)
        <a href="{{ $url }}" class="card-footer-item">{!! $label !!}</a>
      @endforeach
    </footer>
  @endif
</div>