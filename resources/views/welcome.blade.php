<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="welcome-page">
        <header class="welcome-header">
            <nav class="welcome-nav">
                @auth
                    <a href="{{ route('dashboard') }}" class="welcome-button welcome-button-outline">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="welcome-button welcome-button-outline">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="welcome-button welcome-button-primary">
                        Register
                    </a>
                @endauth
            </nav>
        </header>

        <section class="welcome-hero">
            <div class="welcome-content">
                <h1>
                    Welcome To
                    <span>My Library</span>
                </h1>

                <p class="welcome-description">
                    A website developed for the Web Programming midterm project,
                    where you can keep track of the books you have read and
                    manage your personal reading collection.
                </p>

                <div class="welcome-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="welcome-button welcome-button-primary">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="welcome-button welcome-button-primary">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="welcome-text-link">
                            Already have an account?
                        </a>
                    @endauth
                </div>
            </div>

            <div class="welcome-image-wrapper">
                <img
                    src="{{ asset('images/welcome_buku.jpg') }}"
                    alt="A collection of hardcover books"
                    class="welcome-image"
                >
            </div>
        </section>
    </main>
</body>
</html>