<body class="antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    # $app()->card(navbar);

    <!-- MAIN CONTENT -->
    <main class="flex-grow pt-32">
        # $app()->page(e);
    </main>


    <!-- Multi-Column Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <div class="logo-icon"><i data-lucide="package" size="18"></i></div>
                        <span>PVTT</span>
                    </a>
                    <p>Independent streetwear label. Precision apparel engineered for minimal design setups.</p>
                </div>

                <div>
                    <h3 class="footer-column-title">Collection</h3>
                    <ul class="footer-links">
                        <li><a href="#">Latest Drop</a></li>
                        <li><a href="#">Outerwear</a></li>
                        <li><a href="#">Tops & Tees</a></li>
                        <li><a href="#">Accessories</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-column-title">Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Order Tracking</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Size Guide</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-column-title">Support</h3>
                    <ul class="footer-links">
                        <li><a href="?page=privacy-policy">Privacy Policy</a></li>
                        <li><a href="?page=terms-of-use">Terms of Service</a></li>
                        <li><a href="?page=faq">Frequently Asked Questions </a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; 2026 PVTT Inc. All rights reserved.</div>
                <div style="display: flex; gap: 1.5rem;">
                    <a href="#" class="nav-link">Instagram</a>
                    <a href="#" class="nav-link">Discord</a>
                    <a href="#" class="nav-link">X / Twitter</a>
                </div>
                <br>
                <div>Powered by Varsitymarket Technologies.</div>
            </div>
        </div>
    </footer>

    <!-- Interactive Script -->
    <script>
        // Initialize Icons
        lucide.createIcons();

        // Mobile Nav Drawer Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });
    </script>


<!-- #!/engine/node/ analytics() --> 
</body>
</html>