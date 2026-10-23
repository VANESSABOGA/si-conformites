<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yako Africa RH</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --yako: #033f1f;
            --yako-light: #0b7a42;
            --gold: #f4b400;
        }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #e8f5ee 50%, #033f1f 100%);
            padding-top: 76px;
            /* compense la navbar fixe */
        }

        /* NAVBAR */
        .navbar {
            background: rgba(3, 63, 31, .85);
            backdrop-filter: blur(12px);
        }

        .navbar-brand img {
            height: 45px;
        }

        /* HERO */
        .hero {
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .hero-box {
            background: #fff;
            border-radius: 35px;
            padding: 60px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .15);
        }

        .badge-yako {
            background: var(--gold);
            color: var(--yako);
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 700;
            display: inline-block;
        }

        h1 {
            color: var(--yako);
            font-weight: 900;
        }

        .text-muted {
            line-height: 1.8;
        }

        /* IMAGE */
        .hero-img {
            border-radius: 35px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .3);
            transition: .5s;
            max-width: 100%;
            height: auto;
        }

        .hero-img:hover {
            transform: scale(1.03);
        }

        /* BUTTON */
        .btn-yako {
            background: var(--yako);
            color: #fff;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 700;
            transition: .3s;
        }

        .btn-yako:hover {
            background: #065a30;
            color: #fff;
            transform: translateY(-3px);
        }

        /* CARDS */
        .service-card {
            background: #fff;
            border-radius: 25px;
            padding: 30px;
            height: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card i {
            font-size: 40px;
            color: var(--yako);
        }

        /* STATS */
        .stat {
            background: #fff;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
        }

        .stat h3 {
            color: var(--yako);
            font-weight: 800;
        }

        footer {
            background: #033f1f;
            color: #fff;
            padding: 20px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Yako Africa">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Basculer la navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ config('sirh.conformite_url') ?: '#' }}">Conflits d'intérêts</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('bibliotheque.index') }}">Documents RH</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-box">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge-yako">ESPACE RH YAKO AFRICA</span>

                        <h1 class="display-5 mt-4">
                            Votre parcours professionnel, simplifié.
                        </h1>

                        <p class="lead text-muted mt-4">
                            Une plateforme digitale dédiée aux collaborateurs
                            pour gérer vos informations RH, vos demandes,
                            vos documents et votre évolution professionnelle.
                        </p>

                        <div class="mt-4">
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-yako">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Se connecter
                                </a>
                            @else
                                <a href="{{ url('/home') }}" class="btn btn-yako">
                                    Accéder à mon espace
                                </a>
                            @endguest
                        </div>
                    </div>

                    <div class="col-lg-6 text-center">
                        <img src="{{ asset('assets/images/images-2.jpg') }}" class="img-fluid hero-img"
                            alt="Collaborateurs Yako Africa" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>



    <footer class="text-center">
        © {{ date('Y') }} Yako Africa - Plateforme RH
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
