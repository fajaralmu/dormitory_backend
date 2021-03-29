<aside id="sidebar" style="position: fixed;width: 14.75%;" class="menu">
	<p class="menu-label">
		Utama
	</p>
	<ul class="menu-list">
		<li>
			<a href="{{ route('student.dashboard') }}">
				<i class="fas fa-tachometer-alt"></i> Dasbor
			</a>
		</li>
		{{-- <li>
			<a href="#">
				<i class="fas fa-briefcase"></i> Kursus
			</a>
		</li> --}}
		<li>
			<a href="{{ route('student.studies.index') }}">
				<i class="fas fa-book"></i> Studi
			</a>
		</li>
		<li>
			<a href="{{ route('student.cbt.index') }}">
				<i class="fas fa-desktop"></i> CBT
			</a>
		</li>
	</ul>
</aside>