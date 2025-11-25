<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container px-5">
        <a class="navbar-brand" href="index.php">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <!-- Enlaces principales -->
                <li class="nav-item"><a class="nav-link" href="/casa/">{{ casa }}</a></li>
                <li class="nav-item"><a class="nav-link" href="/about/">{{ acercade }}</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact/">{{ contacto }}</a></li>
                <li class="nav-item"><a class="nav-link" href="/pricing/">{{ precio }}</a></li>

                <!-- Cambio de idioma -->
                <li class="nav-item"><a class="nav-link" href="/?lang=es">{{ ES }}</a></li>
                <li class="nav-item"><a class="nav-link" href="/?lang=en">{{ EN }}</a></li>

                <!-- Sección usuarios usando la lógica del profesor y ejemplo añadido-->
                <li class="nav-item">
                    <a class="nav-link" href="/usuarios/">{{ usuarios }}</a>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="/libros/">{{ libros }}</a>
                </li>

                <!-- Otros enlaces -->
                <li class="nav-item"><a class="nav-link" href="/faq/">{{ FAQ }}</a></li>

                <!-- Dropdown Blog -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Blog</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                        <li><a class="dropdown-item" href="/assets/plantilla/blog-home.html">Blog Home</a></li>
                        <li><a class="dropdown-item" href="/assets/plantilla/blog-post.html">Blog Post</a></li>
                    </ul>
                </li>

                <!-- Dropdown Portfolio -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Portfolio</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
                        <li><a class="dropdown-item" href="/assets/plantilla/portfolio-overview.html">Portfolio Overview</a></li>
                        <li><a class="dropdown-item" href="/assets/plantilla/portfolio-item.html">Portfolio Item</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
