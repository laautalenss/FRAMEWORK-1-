<?php

class LoginController
{
    static function pintar()
    {
        $mensaje = '';
        $usuario_valor = 'laura';

        //si está logueado
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            return '
            <br><br><br><br><br>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="mt-3">Ya estas logueado: <strong>' . ($_SESSION['usuario_nombre'] ?? 'Usuario') . '</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $usuario_valor = htmlspecialchars($usuario);

            if ($usuario === 'laura' && $password === 'laura123') {
                //gardar en sesión
                $_SESSION['logueado'] = true;
                $_SESSION['usuario_id'] = 1;
                $_SESSION['usuario_nombre'] = 'Laura';
                $_SESSION['usuario_login'] = 'laura';

                header('Location: /');
                exit();
            } else {
                $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Usuario o contraseña incorrectos
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
            }
        }

        // Mostrar formulario
        return '
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h3 class="text-center mb-4">
                                <i class="bi bi-lock"></i> Login
                            </h3>
                            
                            ' . $mensaje . '
                            
                            <form method="post" action="/login/">
                                <div class="mb-3">
                                    <label class="form-label">Usuario</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="usuario" 
                                           value="' . $usuario_valor . '"
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           required>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    //logut
    static function logout()
    {
        $_SESSION = array();

        session_destroy();

        header('Location: /login/');
        exit();
    }
}
