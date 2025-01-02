 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">MyApp</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.index') }}">Admin</a>
                </li>
             
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('transaction.index') }}">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('member.index') }}">Member</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>