<div id="{{ isset($modal_id) ? $modal_id : 'my-modal' }}" class="modal {{ isset($extra_classes) ? $extra_classes : '' }}">
    <div class="modal-background"></div>
    <div class="modal-content">
        {{ $slot }}
    </div>
    @if(isset($close_btn))
        <button class="modal-close is-large" aria-label="close"></button>
    @endif
</div>

@section('modal_script')
    <script>
        var myModal = document.querySelector('{{ isset($modal_id) ? '#' . $modal_id : '#my-modal' }}');
        @if(isset($close_btn))
            var modalCloseBtn = document.querySelector('{{ isset($modal_id) ? '#' . $modal_id : '#my-modal' }} button.modal-close');
            modalCloseBtn.addEventListener('click', function() {
                myModal.classList.remove('is-active');
            });
        @endif
    </script>
@endsection