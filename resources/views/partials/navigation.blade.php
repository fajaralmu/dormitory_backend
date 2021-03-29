	<nav class="navbar is-dark is-fixed-top" role="navigation" aria-label="main navigation">
		<div class="columns is-gapless" style="width: 100%;">
			<div id="my-logo" class="column is-2">
				<div class="navbar-brand is-hidden-mobile">
		            <span class="navbar-item" href="#">
		                <span class="icon">
		                    <i class="fas fa-book"></i>
		                </span>
		                <span>Kelas Online</span>
		            </span>
		        </div>
			</div>
			<div class="column" style="background: #666;">
				<div class="navbar-brand is-hidden-desktop">
					<span class="navbar-item" href="#">
		                <span class="icon">
		                    <i class="fas fa-book"></i>
		                </span>
		                <span>MY APP</span>
		            </span>
		        	<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
		                <span aria-hidden="true"></span>
		                <span aria-hidden="true"></span>
		                <span aria-hidden="true"></span>
		            </a>
		        </div>
		        <div id="navbarBasicExample" class="navbar-menu">
		            <div class="navbar-start">
		                <a href="/" class="navbar-item">
		                    <span class="icon">
		                        <i class="fas fa-home"></i>
		                    </span>
		                    <span style="margin-left: 5px;">Beranda</span>
		                </a>
		                {{-- <a href="#" class="navbar-item">
		                    <span class="icon">
		                        <i class="fas fa-wrench"></i>
		                    </span>
		                    <span style="margin-left: 5px;">Pengaturan</span>
		                </a> --}}
		            </div>

		            <div class="navbar-end">
		                <div class="navbar-item">
	                        <div class="buttons">
	                            <a class="button">
	                                <span class="icon">
	                                    <i class="far fa-user-circle"></i>
	                                </span>
	                                <span>{{ Auth::user()->name }}</span>
	                            </a>
	                            <a class="button is-danger is-rounded" onclick="event.preventDefault();
	                                                         document.getElementById('logout-form').submit();">
	                                <span class="icon">
	                                    <i class="fas fa-sign-out-alt"></i>
	                                </span>
	                                <span>{{ __('Logout') }}</span>
	                            </a>

	                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                                @csrf
	                            </form>
	                        </div>
	                    </div>
		            </div>
		        </div>
			</div>
		</div>
	</nav>