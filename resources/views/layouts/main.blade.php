<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png" />

    <!--plugins CSS-->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/fancy-file-uploader/fancy_fileupload.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />

    <!-- Icônes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">

    <title>Conformité — YAKO AFRICA</title>
    @stack('styles')
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        @include('layouts.sidebar')
        <!--end sidebar wrapper -->
        <!--start header -->
        @include('layouts.header')
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <style>
                /* ===== HERO BANNER & COUVERTURE ===== */
                .profile-hero {
                    position: relative;
                    background: #ffffff;
                    border: none;
                }

                .profile-hero-cover {
                    height: 140px;
                    background: linear-gradient(135deg, #022b15 0%, #033f1f 50%, #b8860b 100%);
                    border-radius: 1rem 1rem 0 0;
                }

                /* ===== AVATAR & ÉDITION ===== */
                .profile-avatar-wrap {
                    margin-top: -65px;
                }

                .profile-avatar {
                    width: 130px;
                    height: 130px;
                    border: 4px solid #ffffff;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
                    background-color: #ffffff;
                    transition: transform 0.3s ease;
                }

                .profile-avatar:hover {
                    transform: scale(1.02);
                }

                .profile-avatar-edit {
                    position: absolute;
                    bottom: 5px;
                    right: 5px;
                    width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 2px solid #ffffff;
                    background-color: #b8860b;
                    color: #ffffff;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                    transition: all 0.2s ease;
                }

                .profile-avatar-edit:hover {
                    background-color: #9c7209;
                    color: #ffffff;
                    transform: scale(1.1);
                }

                /* ===== BOUTONS ET BADGES PERSONNALISÉS ===== */
                .btn-gold {
                    background-color: #b8860b;
                    border-color: #b8860b;
                    color: #ffffff;
                    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.25);
                }

                .btn-gold:hover,
                .btn-gold:focus {
                    background-color: #9c7209;
                    border-color: #9c7209;
                    color: #ffffff;
                    box-shadow: 0 6px 15px rgba(184, 134, 11, 0.35);
                }

                .bg-gold {
                    background-color: #b8860b !important;
                    color: #ffffff !important;
                }

                .bg-gold-subtle {
                    background-color: rgba(184, 134, 11, 0.12) !important;
                    color: #805d07 !important;
                    border: 1px solid rgba(184, 134, 11, 0.25);
                }

                .bg-green-brand {
                    background-color: #033f1f !important;
                    color: #ffffff !important;
                }

                /* ===== STATISTIQUES & ICÔNES ===== */
                .stat-card {
                    transition: transform 0.25s ease, box-shadow 0.25s ease;
                }

                .stat-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06) !important;
                }

                .stat-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                /* Variantes de couleurs d'icônes fluides */
                .bg-light-primary {
                    background-color: rgba(13, 110, 253, 0.1);
                }

                .bg-light-success {
                    background-color: rgba(25, 135, 84, 0.1);
                }

                .bg-light-info {
                    background-color: rgba(13, 202, 240, 0.1);
                }

                .bg-light-warning {
                    background-color: rgba(255, 193, 7, 0.15);
                }

                /* ===== RANGÉES D'INFORMATIONS ===== */
                .info-row {
                    padding: 10px 0;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                    transition: background-color 0.2s ease;
                }

                .info-row:hover {
                    background-color: rgba(0, 0, 0, 0.01);
                }

                .info-row:last-child {
                    border-bottom: none;
                }

                /* ===== RESPONSIVE ===== */
                @media (max-width: 991.98px) {
                    .profile-hero-cover {
                        height: 110px;
                    }

                    .profile-avatar-wrap {
                        margin-top: -50px;
                    }

                    .profile-avatar {
                        width: 100px;
                        height: 100px;
                    }
                }
            </style>

            @foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $cle => $classe)
                @if (session($cle))
                    <div class="alert alert-{{ $classe }} alert-dismissible fade show m-3 mb-0">
                        {{ session($cle) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <footer class="page-footer"
            style="text-align: center; margin: 0; background-color: #033f1f; color: #fff!important">
            <p class="mb-0">Copyright © Yako Africa {{ date('Y') }} — Conformité & Gouvernance | Tous droits réservés</p>
        </footer>
    </div>
    <!--end wrapper-->

    {{-- ============================================================
         SCRIPTS — ORDRE IMPORTANT :
         1) jQuery AVANT Bootstrap
         2) Plugins APRÈS jQuery
         3) app.js EN DERNIER
         4) Init explicite MetisMenu en sécurité
    ============================================================ --}}

    <!-- 1) jQuery EN PREMIER (obligatoire pour MetisMenu & plugins) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- 2) Bootstrap APRÈS jQuery -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- 3) Plugins -->
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/chart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 4) Scripts du thème -->
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/index2.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- 5) PerfectScrollbar container -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof PerfectScrollbar !== 'undefined') {
                new PerfectScrollbar('.app-container');
            }
        });
    </script>

    <!-- 6) DataTables -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
                if (document.getElementById('example')) {
                    jQuery('#example').DataTable();
                }
                if (document.getElementById('example2')) {
                    var table = jQuery('#example2').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'print'],
                        language: {
                            searchPlaceholder: 'Rechercher dans le tableau ...',
                            sSearch: '',
                            lengthMenu: '_MENU_ ',
                            sInfo: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
                            sInfoEmpty: 'Affichage de 0 à 0 sur 0 entrées',
                            sInfoFiltered: '(filtrées de _MAX_ entrées totales)',
                            sInfoPostFix: '',
                            sLoadingRecords: 'Chargement en cours...',
                            sZeroRecords: 'Aucun enregistrement correspondant trouvé',
                            sEmptyTable: 'Aucune donnée disponible dans le tableau',
                            oPaginate: {
                                sFirst: 'Premier',
                                sPrevious: 'Précédent',
                                sNext: 'Suivant',
                                sLast: 'Dernier'
                            },
                            oAria: {
                                sSortAscending: ': activer pour trier la colonne par ordre croissant',
                                sSortDescending: ': activer pour trier la colonne par ordre décroissant'
                            }
                        }
                    });
                    table.buttons().container()
                        .appendTo('#example2_wrapper .col-md-6:eq(0)');
                }
            }
        });
    </script>

    {{-- ============================================================
         INIT EXPLICITE METISMENU (sécurité)
         Permet au sidebar de plier/déplier même si app.js
         ne l'initialise pas correctement.
    ============================================================ --}}
    <script>
        (
            function() {
                function initMetisMenu() {
                    if (typeof jQuery === 'undefined') {
                        console.warn('⚠️ jQuery non chargé — MetisMenu impossible');
                        return;
                    }
                    if (!jQuery.fn.metisMenu) {
                        console.warn('⚠️ metisMenu non chargé — vérifier assets/plugins/metismenu/js/metisMenu.min.js');
                        return;
                    }
                    var menu = jQuery('#menu');
                    if (menu.length === 0) {
                        console.warn('⚠️ #menu introuvable dans le DOM');
                        return;
                    }
                    // Éviter la double-init : si déjà initialisé, on ne refait pas
                    if (!menu.data('metisMenu')) {
                        menu.metisMenu();
                        console.log('✅ MetisMenu initialisé avec succès');
                    } else {
                        console.log('ℹ️ MetisMenu déjà initialisé (par app.js)');
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initMetisMenu);
                } else {
                    initMetisMenu();
                }
            })();

        // document.addEventListener('DOMContentLoaded', function() {
        //     var toggleBtn = document.getElementById('sidebar-toggle');
        //     var wrapper = document.querySelector('.wrapper');

        //     if (!toggleBtn || !wrapper) {
        //         console.warn('⚠️ #sidebar-toggle ou .wrapper introuvable');
        //         return;
        //     }

        //     // Restaurer l'état sauvegardé
        //     if (localStorage.getItem('sidebar-toggled') === 'true') {
        //         wrapper.classList.add('toggled');
        //     }

        //     toggleBtn.addEventListener('click', function(e) {
        //         e.preventDefault();
        //         e.stopPropagation();
        //         wrapper.classList.toggle('toggled'); // ← classe sur .wrapper
        //         localStorage.setItem('sidebar-toggled', wrapper.classList.contains('toggled'));
        //     });
        // });
    </script>

    @stack('scripts')
</body>

</html>
