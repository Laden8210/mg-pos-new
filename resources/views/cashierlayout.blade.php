<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="robots" content="noindex, nofollow">

    <title>MG Mini Mart | @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap and Core Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Box Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
      .menu-item.active > .menu-link {
    background-color: #007bff; /* Highlight color */
    color: white; /* Text color for active link */
}
.menu-item.active .menu-sub {
    display: block; /* Show the sub-menu when the parent is active */
}

    </style>
      @livewireStyles
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/dashboard" class="app-brand-link">
                        <span class="app-brand-text menu-text fw-bolder ms-2">MG MINI MART</span>
                    </a>
                    <a href="/dashboard" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <!-- Dashboard -->

                  {{-- <!-- Item Management -->
                  <li class="menu-item">
                      <a href="/salereturn" class="menu-link" data-url="/item_management">

                        <i class='menu-icon tf-icons bx bx-recycle'></i>
                          <div data-i18n="Item Management">Sale Return</div>
                      </a>
                  </li> --}}
                  <li class="menu-item">
                    <a href="/saletransaction" class="menu-link" data-url="/item_management">
                        <i class='menu-icon tf-icons bx bx-receipt'></i>

                        <div data-i18n="Item Management">Sale Transaction</div>
                    </a>
                </li>
                  <!-- Logout -->

                </ul>
            </aside>
            <!-- /Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <nav
                class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                    <i class="bx bx-menu bx-sm"></i>
                  </a>
                </div>
                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                  <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                          <!-- Display the avatar from the session -->
                          <img src="{{ auth()->user()->avatar ? asset('assets/img/avatars/' . auth()->user()->avatar) : asset('assets/img/avatars/1.png') }}"
                          alt="User Avatar"
                          class="rounded-circle custom-avatar-size" />

                        </div>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <a class="dropdown-item" href="{{ route('user_account') }}">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">

                                    <img src="{{ auth()->user()->avatar ? asset('assets/img/avatars/' . auth()->user()->avatar) : asset('assets/img/avatars/1.png') }}"
                                    alt="User Avatar"
                                    class="rounded-circle custom-avatar-size" />

                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <!-- Display the full name and role from the session -->
                                <span class="fw-semibold d-block">{{ session('employee_name') }}</span>
                                <small class="text-muted">{{ session('employee_role') }}</small>
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                            <li class="menu-item">
                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    @csrf <!-- Include CSRF token for Laravel -->
                                </form>
                                <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="menu-icon tf-icons bx bx-power-off"></i>
                                    <div data-i18n="Logout">Logout</div>
                                </a>
                            </li>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </nav>
                @yield('content')
            </div>
        </div>
    </div>
    @livewireScripts
    <!-- Core JS -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
  $(document).ready(function() {
    var currentUrl = window.location.pathname; // Get the current URL path

    // Highlight the active menu item
    $('.menu-link').each(function() {
        var menuUrl = $(this).data('url');
        var isActive = false; // Flag to check if the current item is active

        // Check if the menu URL matches the current URL
        if (menuUrl === currentUrl) {
            $(this).addClass('active'); // Add active class to the matching link
            isActive = true; // Set active flag
        } else {
            // Check for sub-menu items
            var subMenu = $(this).next('.menu-sub').find('.menu-link');
            subMenu.each(function() {
                if ($(this).data('url') === currentUrl) {
                    $(this).addClass('active'); // Add active class to the matching sub-menu link
                    isActive = true; // Set active flag
                }
            });
        }

        // If the item or any sub-item is active, add active class to the parent item and show the sub-menu
        if (isActive) {
            $(this).closest('.menu-item').addClass('active'); // Add active class to the parent item
            if ($(this).hasClass('menu-toggle')) {
                $(this).next('.menu-sub').show(); // Show the submenu if it's a toggle
            }
        } else {
            $(this).removeClass('active'); // Remove active class if not active
        }
    });
});
  </script>

    @yield('scripts')
</body>

</html>
