@if(session('status'))
	<div id="status" class="notification is-warning animated fadeInDownBig">
		<button class="delete"></button>
		<p>
			<span class="icon">
				<i class="fas fa-info-circle fa-lg"></i>
			</span>
			<span>{{ session('status') }}</span>
		</p>
	</div>

	@section('status_script')
		<script>
	        // fade out status notification
	        /*setTimeout(function () {
	            var statusEl = document.getElementById('status');
	            statusEl.style.opacity = 0;
	            statusEl.style.transition = 'opacity 1000ms';
	        }, 3000);*/

	        var closeStatusBtn = document.querySelector('#status > button.delete');
	        closeStatusBtn.addEventListener('click', function () {
	        	var statusEl = document.getElementById('status');
	            statusEl.classList.replace('fadeInDownBig', 'fadeOutRightBig');
	        });
	    </script>
	@endsection
@endif