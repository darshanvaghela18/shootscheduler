<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShootScheduler</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="js/script.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
 <!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>



</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">
            <span>ShootScheduler</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#developers">Developers</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <i class="fa-solid fa-key admin-icon" onclick="location.href='admin/login.php'" title="Admin Login"></i> 
            <button onclick="location.href='auth/login.php'">Login</button>
            <button onclick="location.href='auth/signup.php'">Sign Up</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Plan. Shoot. Succeed.</h1>
            <p>Seamless movie production planning at your fingertips.</p>
            <button class="get-started" onclick="location.href='auth/signup.php'">Get Started</button>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about full-screen">
        <div class="about-content">
            <h2>About ShootScheduler</h2>
            <p>ShootScheduler is an innovative platform designed for filmmakers, production houses, and independent 
                creators to manage movie projects seamlessly. Our platform offers a structured workflow to handle everything 
                from project planning, budget tracking, scheduling, and document management, ensuring a smooth filmmaking experience.</p>
            <p>With an intuitive interface and powerful tools, ShootScheduler is your go-to solution for efficient movie production.</p>
        </div>
    </section>

    <!-- Services Section -->
    <div class="services-container">
    <section id="services" class="services full-screen">
        <h2 class="services-title">Our Services</h2> 
        
        <div class="service-cards">
            <div class="card">
                <div class="card-image" style="background-image: url('images/project-management.jpg');"></div>
                <div class="card-content">
                    <h3>🎬 Project Management</h3>
                    <p>Manage your movie project seamlessly from pre to post-production.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image" style="background-image: url('images/budget-tracking.jpg');"></div>
                <div class="card-content">
                    <h3>💰 Budget Tracking</h3>
                    <p>Keep track of your expenses and maintain financial control.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image" style="background-image: url('images/scheduling.jpg');"></div>
                <div class="card-content">
                    <h3>🎥 Scheduling</h3>
                    <p>Plan your shooting schedules efficiently.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image" style="background-image: url('images/document-management.jpg');"></div>
                <div class="card-content">
                    <h3>📂 Document Management</h3>
                    <p>Store and access your project files securely.   </p>
                </div>
            </div>

            <div class="card">
                <div class="card-image" style="background-image: url('images/upcoming-projects.jpg');"></div>
                <div class="card-content">
                    <h3>⏳ Upcoming Projects</h3>
                    <p>Get countdowns and reminders for future projects.</p>
                </div>
            </div>
        </div>
    </section>
</div>





    <!-- Developers Section -->
    <section id="developers" class="developers full-screen">
        <!-- <h2> Developers</h2> -->
        <div class="developer-profiles">
            <div class="developer-card">
                <img src="images/developer1.jpg" alt="Developer 1" class="developer-img"> 
                <h3>Darshan Vaghela</h3>
                <p>Developer </p>
                <p>Expert in web development, database management, and system architecture.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-x"></i></a>
                </div>
            </div>
            <div class="developer-card">
                <img src="images/developer2.jpg" alt="Developer 2" class="developer-img"> 
                <h3>Jaydip Kherala</h3>
                <p>Developer</p>
                <p>Specializes in user experience design, branding, and front-end development.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-x"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact full-screen">
        <h2>Contact Us</h2>
        <form action="server/contact-form.php" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send</button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 ShootScheduler. All rights reserved.</p>
        <button class="scroll-top" onclick="scrollToTop()">Back to Top</button>
    </footer>

    <script>
  
</script>
</body>
</html>
