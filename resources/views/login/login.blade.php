<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}" type="image/x-icon">

    <!-- PWA Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000"/>
    <link rel="manifest" href="/manifest.json">

    <!-- PWA Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
            console.log('ServiceWorker registration successful');
            })
            .catch(err => {
            console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #28a745;
            border-bottom: 2px solid #28a745;
        }
        .tab-content {
            margin-top: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h3 class="text-center text-success mb-4">SkillPath Vault</h3>

        <!-- Barre de navigation -->
        <ul class="nav nav-tabs justify-content-center mb-4">
            <li class="nav-item">
                <a class="nav-link active" id="user-tab" data-bs-toggle="tab" href="#user-form">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="student-tab" data-bs-toggle="tab" href="#student-form">Student</a>
            </li>
        </ul>

        <!-- Contenu des formulaires -->
        <div class="tab-content">
            <!-- Affichage des messages d'erreur -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Formulaire Utilisateur -->
            <div class="tab-pane fade show active" id="user-form">
                <form action="{{ route('login.user') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Your email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Your password" required>
                        </div>
                    </div>
                    {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="text-decoration-none text-success">Mot de passe oublié ?</a>
                    </div> --}}
                    <button type="submit" class="btn btn-success w-100">Log in</button>
                </form>

            </div>

            <div class="tab-pane fade" id="student-form">
                <form action="{{ route('student.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Your email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Your password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Log in</button>
                    <div class="text-center mt-3">
                        {{-- <p>Pas encore de compte ? <a href="#" class="text-success">Inscrivez-vous</a></p> --}}
                    </div>
                </form>
            </div>
        </div>

        </div>


    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
