// ==== NAVIGATION CONTROLLER CLASS ====
// This is like a helper that controls how the top menu bar works
class NavigationController {
            // This is the setup part - it runs when we first create the controller
            constructor() {
                // Find the navigation bar on the page and save it
                this.navbar = document.getElementById('main-navbar');

                // Find the debug panel (used for testing) and save it
                this.debugPanel = document.getElementById('debug-panel');
                this.debugScroll = document.getElementById('debug-scroll');
                this.debugNavbarState = document.getElementById('debug-navbar-state');
                this.debugViewport = document.getElementById('debug-viewport');

                // This number tells us how far to scroll before changing the menu bar
                // 60 means 60 pixels down the page
                this.scrollThreshold = 60;

                // This keeps track of if we have scrolled down or not
                // false means we have not scrolled yet
                this.isScrolled = false;

                // Start everything up
                this.init();
            }

            // This function starts all the features we need
            init() {
                // Listen for when someone scrolls the page
                // passive: true makes scrolling smoother
                window.addEventListener('scroll', this.handleScroll.bind(this), { passive: true });

                // Set up smooth scrolling when clicking menu links
                this.bindSmoothScroll();

                // Update the debug information
                this.updateDebugInfo();

                // Listen for when someone changes the window size
                window.addEventListener('resize', this.updateDebugInfo.bind(this));
            }

            // This function runs every time someone scrolls the page
            handleScroll() {
                // Get how far down the page we have scrolled
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;

                // Check if we scrolled past our threshold (60 pixels)
                const shouldBeScrolled = scrollY > this.scrollThreshold;

                // Only update if the scroll state changed
                if (shouldBeScrolled !== this.isScrolled) {
                    // Remember the new scroll state
                    this.isScrolled = shouldBeScrolled;

                    // Update how the navbar looks
                    this.updateNavbarState();
                }

                // Update debug information
                this.updateDebugInfo();
            }

            // This function changes how the navigation bar looks when scrolling
            updateNavbarState() {
                if (this.isScrolled) {
                    // If we scrolled down, add the scrolled style
                    this.navbar.classList.add('nav-scrolled');
                    this.navbar.setAttribute('data-state', 'scrolled');
                } else {
                    // If we are at the top, remove the scrolled style
                    this.navbar.classList.remove('nav-scrolled');
                    this.navbar.setAttribute('data-state', 'transparent');
                }
            }

            // This function makes menu links scroll smoothly instead of jumping
            bindSmoothScroll() {
                // Find all links that start with # (like #home, #about)
                const navLinks = document.querySelectorAll('a[href^="#"]');

                // For each link, add a click listener
                navLinks.forEach(link => {
                    link.addEventListener('click', (e) => {
                        // Stop the normal jump behavior
                        e.preventDefault();

                        // Get where the link wants to go (like #home)
                        const targetId = link.getAttribute('href');

                        // Find that section on the page
                        const targetElement = document.querySelector(targetId);

                        // If we found the section
                        if (targetElement) {
                            // Calculate where to scroll (80 pixels less to account for navbar)
                            const offsetTop = targetElement.offsetTop - 80;

                            // Scroll to that spot smoothly
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });

                            // Highlight the clicked link
                            this.updateActiveNavLink(link);
                        }
                    });
                });
            }

            // This function highlights which menu link is active
            updateActiveNavLink(activeLink) {
                // Find all menu links
                const allNavLinks = document.querySelectorAll('.nav-menu-link');

                // Turn off highlighting for all links
                allNavLinks.forEach(link => {
                    link.setAttribute('data-active', 'false');
                    link.style.color = '';
                });

                // Turn on highlighting for the clicked link
                activeLink.setAttribute('data-active', 'true');
                activeLink.style.color = '#ffffff';
            }

            // This function updates debugging information (for developers to test)
            updateDebugInfo() {
                if (this.debugScroll) {
                    // Get how far we scrolled and round it to a whole number
                    const scrollY = Math.round(window.pageYOffset || document.documentElement.scrollTop);

                    // Show the scroll position
                    this.debugScroll.textContent = scrollY;

                    // Show if navbar is scrolled or transparent
                    this.debugNavbarState.textContent = this.isScrolled ? 'scrolled' : 'transparent';

                    // Show the window width and height
                    this.debugViewport.textContent = `${window.innerWidth}x${window.innerHeight}`;
                }
            }
        }

        // ==== START THE NAVIGATION CONTROLLER ====
        // Wait for the page to fully load before starting
        document.addEventListener('DOMContentLoaded', () => {
            // Create a new navigation controller
            const navController = new NavigationController();

            // Add some markers to help with debugging
            document.body.setAttribute('data-nav-initialized', 'true');
            document.body.setAttribute('data-timestamp', new Date().toISOString());
        });

        // ==== MAKE THE WEBSITE RUN FASTER ====
        // This prevents too many updates when resizing the window
        let resizeTimeout;
        window.addEventListener('resize', () => {
            // Cancel the previous timer
            clearTimeout(resizeTimeout);

            // Wait 250 milliseconds before updating
            // This prevents updating too many times in a row
            resizeTimeout = setTimeout(() => {
                document.body.setAttribute('data-viewport-updated', new Date().toISOString());
            }, 250);
        });