<div class="box">
	<h4 class="title is-4">
		<span class="fa-stack">
			<i class="fas fa-circle fa-stack-2x"></i>
			<i class="fas fa-trash fa-stack-1x fa-inverse"></i>
		</span>
		<span>Confirmation</span>
	</h4>
	<div class="box" style="background: #eaeaea;">
		<p>Are you sure to delete this item?</p>
	</div>
	<div class="has-text-right" style="margin-top: 10px;">
		<button id="yes" class="button is-primary is-uppercase">Yes</button>
	</div>
</div>

@section('extra_script')
	@parent
	<script>
		var modalDeleteConfirmation = document.getElementById('delete-confirmation')
		var deleteBtns = document.querySelectorAll('button[name="delete-item"]');

		deleteBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var form = this.parentElement;
                var yesBtn = document.querySelector('#delete-confirmation button#yes');

                yesBtn.addEventListener('click', function() {
                    // submit form
                    form.submit();

                    // hide modal dialog
                    modalDeleteConfirmation.classList.remove('is-active');
                });

                modalDeleteConfirmation.classList.add('is-active');
            });
        });
	</script>
@endsection