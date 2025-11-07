<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{  asset('template/build/assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{  asset('template/build/assets/img/favicon.png') }}" />
    <title>@yield('title', 'feel')</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{  asset('template/build/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{  asset('template/build/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Main Styling -->
    <link href="{{  asset('template/build/assets/css/argon-dashboard-tailwind.css') }}" rel="stylesheet" />
    @yield('styles')
    <style>
      /* Force mobile nav text color to match desktop */
      @media (max-width: 1024px) {
        #navigation a { color: #334155 !important; } /* slate-700 */
        #navigation i { color: #334155 !important; }
        /* Prevent fade-out on mobile links */
        #navigation .lg-max\:opacity-0 { opacity: 1 !important; }
        #navigation.open { opacity: 1 !important; visibility: visible !important; }
        #navigation.open a { opacity: 1 !important; }
        #navigation.open * { transition: none !important; }
      }
    </style>
  </head>

  <body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
    <div class="container sticky top-0 z-sticky">
      <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 flex-0">
          <!-- Navbar -->
          <nav class="absolute top-0 left-0 right-0 z-30 flex flex-wrap items-center px-4 py-2 m-6 mb-0 shadow-sm rounded-xl bg-white/80 backdrop-blur-2xl backdrop-saturate-200 lg:flex-nowrap lg:justify-start">
            <div class="flex items-center justify-between w-full p-0 px-6 mx-auto flex-wrap-inherit">
              <a class="py-1.75 text-sm mr-4 ml-4 whitespace-nowrap font-bold text-slate-700 lg:ml-0" href="{{ route('home.page') }}" target="_blank"> feel </a>
              <button navbar-trigger class="px-3 py-1 ml-2 leading-none transition-all ease-in-out bg-transparent border border-transparent border-solid rounded-lg shadow-none cursor-pointer text-lg lg:hidden" type="button" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="inline-block mt-2 align-middle bg-center bg-no-repeat bg-cover w-6 h-6 bg-none">
                  <span bar1 class="w-5.5 rounded-xs relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                  <span bar2 class="w-5.5 rounded-xs mt-1.75 relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                  <span bar3 class="w-5.5 rounded-xs mt-1.75 relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                </span>
              </button>
              <div id="navigation" navbar-menu class="items-center flex-grow transition-all duration-500 lg-max:overflow-hidden ease lg-max:max-h-0 basis-full lg:flex lg:basis-auto text-slate-700">
                <div class="items-center flex-grow transition-all duration-500 lg-max:overflow-hidden ease lg-max:max-h-0 basis-full lg:flex lg:basis-auto">
                  <!-- Empty space for left side -->
                </div>
                <ul class="flex flex-col pl-0 mb-0 list-none lg:flex-row">
                  <li>
                    <a class="flex items-center px-4 py-2 mr-2 font-normal transition-all ease-in-out lg-max:opacity-0 duration-250 text-sm text-slate-700 lg:px-2" aria-current="page" href="{{ route('home.page') }}">
                      <i class="mr-1 fa fa-home opacity-60"></i>
                      Home
                    </a>
                  </li>
                  @auth
                  <li>
                    <a class="block px-4 py-2 mr-2 font-normal transition-all ease-in-out lg-max:opacity-0 duration-250 text-sm text-slate-700 lg:px-2" href="{{ route('dashboard.page') }}">
                      <i class="mr-1 fas fa-key opacity-60"></i>
                      Dashboard
                    </a>
                  </li>  
                  @else
                  <li>
                    <a class="block px-4 py-2 mr-2 font-normal transition-all ease-in-out lg-max:opacity-0 duration-250 text-sm text-slate-700 lg:px-2" href="{{ route('login') }}">
                      <i class="mr-1 fas fa-key opacity-60"></i>
                      Sign In
                    </a>
                  </li>
                  @endauth
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>

    @yield('content')

    <footer class="py-12">
      <div class="container">
        <div class="flex flex-wrap -mx-3">
          <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
            <p class="mb-0 text-slate-400">
              Copyright ©
              <script>
                document.write(new Date().getFullYear());
              </script>
              feel by Creative Tim.
            </p>
          </div>
        </div>
      </div>
    </footer>

    <!-- plugin for scrollbar  -->
    <script src="{{  asset('template/build/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <!-- main script file  -->
    <script src="{{  asset('template/build/assets/js/argon-dashboard-tailwind.js') }}" defer></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var trigger = document.querySelector('[navbar-trigger]');
        var menu = document.getElementById('navigation');
        if (!trigger || !menu) return;
        trigger.addEventListener('click', function () {
          var expanded = trigger.getAttribute('aria-expanded') === 'true';
          trigger.setAttribute('aria-expanded', (!expanded).toString());
          menu.classList.toggle('lg-max:max-h-0');
          menu.classList.toggle('max-h-screen');
          menu.classList.toggle('open');
          // enforce text color on toggle (avoid accidental white text)
          menu.querySelectorAll('a, i').forEach(function(el){
            el.classList.remove('text-white');
            el.style.color = '#334155';
            el.style.opacity = '1';
          });
        });
      });
    </script>
    
    @yield('scripts')
  </body>
</html>
