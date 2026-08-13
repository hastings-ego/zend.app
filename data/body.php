<body class="antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    # $app()->card(navbar);

    <!-- MAIN CONTENT -->
    <main class="flex-grow pt-32">
        # $app()->page(e);
    </main>

    <!-- NEW REDESIGNED GRID FOOTER -->
    <footer class="bg-archive-ink text-archive-paper">
        <!-- Top Footer Grid -->
        <div class="">
            <div class="grid grid-cols-1 md:grid-cols-4">

                <!-- Brand Column -->
                <div class="p-10 md:p-12 flex flex-col justify-between min-h-[300px]">
                    <img src="https://themes.varsitymarket.co.za/collection/voide/assets/b-logo.png" style="max-width:6rem; margin:auto; ">
                    <h2 class="font-display font-black text-4xl leading-none" style="text-align:center;">VOIDE</h2>
                </div>

                <!-- Link Column 1 -->
                <div class="p-10 md:p-12">
                    <h3 class="text-[14px] font-bold uppercase opacity-30 mb-10">The Brand</h3>
                    <ul class="space-y-6 text-xs uppercase">
                        <li><a href="<!-- #!/engine/node/ page('shop') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Our Shop</a></li>
                        <li><a href="<!-- #!/engine/node/ page('about') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">About Us</a></li>
                        <li><a href="<!-- #!/engine/node/ page('cart') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Cart</a></li>
                        <li><a href="<!-- #!/engine/node/ page('login') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Account</a></li>
                    </ul>
                </div>

                <!-- Link Column 2 -->
                <div class="p-10 md:p-12">
                    <h3 class="text-[14px] font-bold uppercase opacity-30 mb-10">Support</h3>
                    <ul class="space-y-6 text-xs uppercase tracking-widest">
                        <li><a href="<!-- #!/engine/node/ page('contact') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Contact Us</a></li>
                        <li><a href="<!-- #!/engine/node/ page('faq') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">FAQ</a></li>
                        <li><a href="<!-- #!/engine/node/ page('terms-of-use') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Terms of Use</a></li>
                        <li><a href="<!-- #!/engine/node/ page('privacy-policy') -->"  class="hover:text-white opacity-70 hover:opacity-100 transition-opacity">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Subscribe Column -->
                <div class="p-10 md:p-12">
                    <h3 class="text-[14px] font-bold uppercase opacity-30 mb-10">Subscribe</h3>
                    <p class="text-[10px] opacity-60 leading-loose mb-6">Join The Voide Club</p>
                    <form class="flex pb-2">
                        <input type="email" placeholder="EMAIL ADDRESS" class="bg-transparent border-none outline-none text-xs w-full uppercase placeholder:text-archive-paper/30 font-mono text-archive-paper" required>
                        <button type="submit" class="text-[14px] font-bold uppercase hover:italic">Subscribe</button>
                    </form>
                </div>

            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="p-10 flex flex-col md:flex-row justify-between items-center text-[12px] uppercase opacity-40">
            <p></p>
            <p class="mt-4 md:mt-0">Powered By Varsity Market Technologies</p>
        </div>
    </footer>

    <!-- MOBILE MENU LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('menu-toggle');
            const menu = document.getElementById('mobile-menu');
            let isOpen = false;

            btn.addEventListener('click', () => {
                isOpen = !isOpen;
                if (isOpen) {
                    menu.classList.remove('menu-closed');
                    menu.classList.add('menu-open');
                    btn.innerHTML = '<img style="width: 3rem; display: block; opacity: 1; filter: invert(1);" src="https://themes.varsitymarket.co.za/collection/voide/assets/close.png">';
                } else {
                    menu.classList.remove('menu-open');
                    menu.classList.add('menu-closed');
                    btn.innerHTML = '<img style="width: 3rem; display: block; opacity: 1; filter: invert(1);" src="https://themes.varsitymarket.co.za/collection/voide/assets/menu.png">';
                }
            });

            // Close menu if a link is clicked
            const links = menu.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', () => {
                    isOpen = false;
                    menu.classList.remove('menu-open');
                    menu.classList.add('menu-closed');
                    btn.textContent = '[ MENU ]';
                });
            });
        });
    </script>

<!-- #!/engine/node/ analytics() --> 
</body>

</html>