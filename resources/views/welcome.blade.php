<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Kidicode LMS - STEM & AI Education for Kids</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">// Is code ko welcome.blade.php ke top mein add karein
@if(auth()->check())
    <script>
        // Agar already logged in hai tou directly dashboard par redirect karein
        window.location.href = "{{ route('home') }}";
    </script>
@endif
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .course-category {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                🚀 Kidicode LMS
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Kidicode LMS</h1>
            <p class="lead">Pakistan's Premier STEM & AI Learning Platform for Kids</p>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Get Started Free</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Existing Student</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Why Choose Kidicode?</h2>
                <p class="lead">Interactive learning platform designed specifically for young minds</p>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="display-4">👨‍💻</div>
                            <h5>Coding & Programming</h5>
                            <p>Learn Scratch, Python, Web Development and more through fun projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="display-4">🤖</div>
                            <h5>AI & Robotics</h5>
                            <p>Introduction to Artificial Intelligence and Robotics for kids</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <div class="display-4">🎮</div>
                            <h5>Gamified Learning</h5>
                            <p>Learn through games, quizzes, and interactive challenges</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Popular Courses</h2>
                <p>Explore our engaging curriculum designed for different age groups</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="course-category">
                        <h4>🔄 Scratch Programming</h4>
                        <p>Visual programming for beginners aged 8-12</p>
                        <ul>
                            <li>Drag-and-drop coding</li>
                            <li>Game development</li>
                            <li>Animation creation</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="course-category">
                        <h4>🐍 Python for Kids</h4>
                        <p>Text-based programming for ages 10+</p>
                        <ul>
                            <li>Basic programming concepts</li>
                            <li>Game development</li>
                            <li>Problem solving</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="course-category">
                        <h4>🌐 Web Development</h4>
                        <p>Build websites with HTML, CSS, JavaScript</p>
                        <ul>
                            <li>Website creation</li>
                            <li>Frontend development</li>
                            <li>Project portfolio</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="course-category">
                        <h4>🧠 Artificial Intelligence</h4>
                        <p>Introduction to AI and Machine Learning</p>
                        <ul>
                            <li>AI basics</li>
                            <li>Machine learning projects</li>
                            <li>Future technologies</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>How It Works</h2>
            </div>
            
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="display-4">1️⃣</div>
                    <h5>Sign Up</h5>
                    <p>Create your free account</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4">2️⃣</div>
                    <h5>Choose Course</h5>
                    <p>Select from various courses</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4">3️⃣</div>
                    <h5>Learn & Practice</h5>
                    <p>Interactive lessons and projects</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4">4️⃣</div>
                    <h5>Get Certified</h5>
                    <p>Earn certificates on completion</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 Kidicode LMS. All rights reserved.</p>
            <p>STEM Education for the Next Generation</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>