<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Asset Management System</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- Responsive styles -->
    <style>
        /* Responsive table styles */
        @media screen and (max-width: 768px) {
            .table-responsive-stack tr {
                display: flex;
                flex-direction: column;
                border-bottom: 3px solid #ddd;
                margin-bottom: 1rem;
            }
            .table-responsive-stack td {
                border-top: none;
                border-bottom: 1px solid #ddd;
                position: relative;
                padding-left: 50%;
                text-align: left;
                display: flex;
                align-items: center;
                min-height: 50px;
            }
            .table-responsive-stack td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                font-weight: bold;
            }
            .table-responsive-stack thead tr {
                display: none;
            }
            
            /* Make buttons full width on mobile */
            .btn-mobile-full {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            /* Fix sidebar toggle */
            #content {
                margin-left: 0 !important;
            }
            
            /* Make cards stack better on mobile */
            .card-deck {
                display: block;
            }
            .card-deck .card {
                margin-bottom: 15px;
            }
            
            /* Improve modal display on small screens */
            .modal-dialog {
                margin: 0.5rem;
            }
        }
        
        /* Fix dropdown menus on mobile */
        @media (max-width: 576px) {
            .dropdown-menu {
                position: fixed;
                top: auto;
                right: 0;
                left: 0;
                width: 100%;
                transform: none !important;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            {{-- @include('layouts.footer') --}}
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Responsive Table Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize responsive tables
            const tables = document.querySelectorAll('.table-responsive-stack');
            
            tables.forEach(table => {
                const headerCells = table.querySelectorAll('thead th');
                const headerTexts = Array.from(headerCells).map(cell => cell.textContent.trim());
                
                const dataCells = table.querySelectorAll('tbody td');
                dataCells.forEach((cell, index) => {
                    const headerIndex = index % headerTexts.length;
                    cell.setAttribute('data-label', headerTexts[headerIndex]);
                });
            });
            
            // Handle sidebar toggle for mobile
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            const accordionSidebar = document.getElementById('accordionSidebar');
            
            if (sidebarToggleTop && accordionSidebar) {
                sidebarToggleTop.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-toggled');
                    accordionSidebar.classList.toggle('toggled');
                });
                
                // Auto-collapse sidebar on small screens
                function checkWidth() {
                    if (window.innerWidth < 768) {
                        document.body.classList.add('sidebar-toggled');
                        accordionSidebar.classList.add('toggled');
                    }
                }
                
                // Check on load and resize
                checkWidth();
                window.addEventListener('resize', checkWidth);
            }
        });
    </script>
    
    @stack('scripts')
</body>

</html>
