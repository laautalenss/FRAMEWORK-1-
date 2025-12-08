<?php

// Archivo: includes/paginas/login.controller.php

// NO pongas session_start() aquí, ya está en index.php

class LoginController
{
    static function pintar()
    {
        // Inicializar variables
        $mensaje = '';
        $usuario_valor = 'admin';
        
        // Verificar si ya está logueado
        if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
            return '
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3><i class="bi bi-check-circle text-success"></i> Ya estás logueado</h3>
                                <p class="mt-3">Hola, <strong>' . ($_SESSION['usuario_nombre'] ?? 'Usuario') . '</strong></p>
                                <div class="mt-4">
                                    <a href="/" class="btn btn-primary">
                                        <i class="bi bi-house"></i> Ir al inicio
                                    </a>
                                    <a href="/login/logout" class="btn btn-outline-secondary">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        // Procesar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $usuario_valor = htmlspecialchars($usuario);
            
            // Verificar credenciales
            if ($usuario === 'admin' && $password === 'admin123') {
                // Guardar en sesión
                $_SESSION['logueado'] = true;
                $_SESSION['usuario_id'] = 1;
                $_SESSION['usuario_nombre'] = 'Administrador';
                $_SESSION['usuario_login'] = 'admin';
                
                // Redirigir
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
                                           placeholder="admin" 
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           placeholder="admin123" 
                                           required>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Usuario demo: <strong>admin</strong><br>
                                        Contraseña: <strong>admin123</strong>
                                    </small>
                                </div>
                            </form>
                            
                            <div class="text-center mt-3">
                                <a href="/" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-house"></i> Volver al inicio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
    
    // Método para logout
    static function logout()
    {
        // Limpiar sesión
        $_SESSION = array();
        
        // Destruir sesión
        session_destroy();
        
        // Redirigir al login
        header('Location: /login/');
        exit();
    }
}