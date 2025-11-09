<?php
// Start the session to check if user is logged in
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Grilliance</title>

    <!-- Google Fonts - Sophisticated Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&family=Crimson+Text:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
     <!-- ==== MAIN NAVIGATION BAR ==== -->
    <nav class="main-navigation" id="main-navbar">
      <div class="nav-container">
        <!-- Logo Section -->
        <div class="nav-logo-container">
          <img
            src="assets/images/logo.png"
            alt="Company Logo"
            class="nav-logo-image"
            id="nav-logo"
          />
        </div>

        <!-- Main Navigation Menu -->
        <ul class="nav-menu-list" id="nav-main-menu">
          <li class="nav-menu-item" data-nav-item="home">
            <a href="#home" class="nav-menu-link" data-nav-link="home">Home</a>
          </li>
          <li class="nav-menu-item" data-nav-item="about">
            <a href="#about" class="nav-menu-link" data-nav-link="about">About</a>
          </li>
          <li class="nav-menu-item" data-nav-item="contact">
            <a href="#contact" class="nav-menu-link" data-nav-link="contact">Contact</a>
          </li>
          <li class="nav-menu-item" data-nav-item="gallery">
            <a href="#gallery" class="nav-menu-link" data-nav-link="gallery">Gallery</a>
          </li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
              <!-- Show Admin Dashboard for admins -->
              <li class="nav-menu-item" data-nav-item="admin">
                <a href="admin/dashboard.php" class="nav-menu-link" data-nav-link="admin">Admin Dashboard</a>
              </li>
            <?php else: ?>
              <!-- Show Reservations for customers -->
              <li class="nav-menu-item" data-nav-item="reservations">
                <a href="reservations/reservations.php" class="nav-menu-link" data-nav-link="reservations">Reservations</a>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>

        <!-- Sign In Button -->
        <div class="nav-signin-container">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="auth/logout.php" class="nav-signin-button" id="nav-signin" data-nav-action="signin">
              Logout
            </a>
          <?php else: ?>
            <a href="auth/login.php" class="nav-signin-button" id="nav-signin" data-nav-action="signin">
              Sign In
            </a>
          <?php endif; ?>
        </div>
      </div>
    </nav>

    <!-- Debug Information (Hidden by default) -->
    <div class="debug-info" id="debug-panel">
      <div>Scroll Y: <span id="debug-scroll">0</span>px</div>
      <div>Navbar State: <span id="debug-navbar-state">transparent</span></div>
      <div>Viewport: <span id="debug-viewport">0x0</span></div>
    </div>

    <!-- ==== MAIN CONTENT ==== -->
    <!-- Video Hero Section -->
    <section class="hero-section" id="home" data-section="hero">
      <!-- Background Video -->
      <video
        class="hero-video"
        autoplay
        muted
        loop
        playsinline
        poster="https://via.placeholder.com/1920x1080/1a1a1a/ffffff?text=Video+Poster"
      >
        <source src="assets/videos/hero-video.mp4" type="video/mp4" />
        <!-- Add additional video formats for better browser support -->
        <source src="your-video.webm" type="video/webm" />
        Your browser does not support the video tag.
      </video>

      <!-- Dark overlay for better text readability -->
      <div class="hero-overlay"></div>

      <!-- Hero Content with Buttons -->
      <div class="hero-content">
        <div class="hero-logo-container">
          <img
            src="assets/images/logo.png"
            alt="Restaurant Logo"
            class="hero-logo"
          />
        </div>

        <!-- Hero Slogan -->
        <div class="hero-text-container">
          <h1 class="hero-slogan">SAVORING THE PERFECT SEAR</h1>
          <p class="hero-description">
            A quiet fine dining escape near the city center. Grilliance blends old world service with bold, flame-kissed steaks.
          </p>
        </div>

        <div class="hero-buttons-container">
          <a href="#menu" class="hero-button menu-btn" data-action="menu">
            Menu
          </a>

          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- If logged in, show Reserve button -->
            <a href="reservations/new-reservation.php" class="hero-button reserve-btn" data-action="reserve">
              Reserve
            </a>
          <?php else: ?>
            <!-- If not logged in, show Sign In to Reserve button -->
            <a href="auth/login.php" class="hero-button reserve-btn" data-action="signin">
              Reserve
            </a>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="content-section" id="about" data-section="about">
      <h2 class="section-title">About Our Vision</h2>
      <p class="section-paragraph">
        We believe in creating digital experiences that transcend the ordinary.
        Our approach combines timeless elegance with modern functionality,
        ensuring every interaction feels intentional and refined.
      </p>
      <p class="section-paragraph">
        Through meticulous attention to typography, spacing, and motion, we
        craft interfaces that not only serve their purpose but inspire and
        delight at every touchpoint.
      </p>
    </section>

    <!-- Gallery Section -->
    <section class="content-section" id="gallery" data-section="gallery">
      <h2 class="section-title">Our Gallery</h2>
      <p class="section-paragraph">
        Discover a curated collection of our finest work, where each piece
        represents our commitment to excellence and our passion for creating
        memorable digital experiences.
      </p>
      <p class="section-paragraph">
        From conceptual designs to fully realized projects, our gallery
        showcases the evolution of ideas into sophisticated solutions that stand
        the test of time.
      </p>
    </section>

    <!-- Contact Section -->
    <section class="content-section" id="contact" data-section="contact">
      <h2 class="section-title">Get in Touch</h2>
      <p class="section-paragraph">
        We welcome the opportunity to discuss your vision and explore how we can
        bring your ideas to life with the same attention to detail and
        commitment to excellence that defines all our work.
      </p>
      <p class="section-paragraph">
        Whether you're looking to create something entirely new or enhance an
        existing project, we're here to collaborate and create something
        extraordinary together.
      </p>
    </section>
    <script src="script.js"></script>
  </body>
</html>
