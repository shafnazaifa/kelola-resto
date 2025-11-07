<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - feel')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkfG3lZ7eZ8P4RAKtxqf0S9Z3VQ2FQwIuNwYq8C+9V+YqD2VZ8S+6X5xg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @yield('styles')
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse min-vh-100">
          <div class="position-sticky pt-3">
            <div class="text-center mb-4">
              <h4 class="text-white">feel</h4>
            </div>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard.page') ? 'bg-primary' : '' }}" href="{{ route('dashboard.page') }}">
                  <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
              </li>
              @if(Auth::user()->role == 'admin')
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('meja.*') ? 'bg-primary' : '' }}" href="{{ route('meja.index') }}">
                  <i class="fas fa-chair me-2"></i>Meja
                </a>
              </li>
              @endif
              @if (Auth::user()->role == 'admin' || Auth::user()->role == 'waiter')
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('menu.*') ? 'bg-primary' : '' }}" href="{{ route('menu.index') }}">
                  <i class="fas fa-utensils me-2"></i>Menu
                </a>
              </li>
              @endif
              @if (Auth::user()->role == 'waiter')
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('order.index') ? 'bg-primary' : '' }}" href="{{ route('order.index') }}">
                  <i class="fas fa-book me-2"></i>Order
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('order.list') ? 'bg-primary' : '' }}" href="{{ route('order.list') }}">
                  <i class="fas fa-list me-2"></i>Order List
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pelanggan.*') ? 'bg-primary' : '' }}" href="{{ route('pelanggan.index') }}">
                  <i class="fas fa-user me-2"></i>Pelanggan
                </a>
              </li>
              @endif
              @if (Auth::user()->role == 'kasir')
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('transaction.*') ? 'bg-primary' : '' }}" href="{{ route('transaction.index') }}">
                  <i class="fas fa-credit-card me-2"></i>Transaksi
                </a>
              </li>
              @endif
              @if (!(Auth::user()->role == 'admin'))
              <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('report.*') ? 'bg-primary' : '' }}" href="{{ route('report.index') }}">
                  <i class="fas fa-chart-bar me-2"></i>Report
                </a>
              </li>
              @endif
            </ul>
            
            <hr class="text-white">
            <div class="dropdown">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
              </form>
            </div>
          </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.page') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
              </ol>
            </nav>
          </div>

          @yield('content')
        </main>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('scripts')
  </body>
</html>
