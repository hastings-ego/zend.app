<!-- #/engine/head/encode/:BEGIN -->
<!DOCTYPE html>
<html lang="en">
<!-- 

    #   TITLE   : VOIDE Application Interface
    #   DESC    : This is the interface for the VOIDE Application, which is a collection of tools and features designed to enhance user experience and provide additional functionality. The interface is designed to be user-friendly and intuitive, allowing users to easily navigate and access the various features of the application.
    #   PROPRIETOR: VARSITYMARKET_TECHNOLOGIES
    #   VERSION : v2.1.1.1
    #   AUTHOR  : LEVIDOC AGENCY  
    #   RELEASE : 2026/07/18
    #   CATEGORY: ONLINE STORE

-->

<head>
    <meta charset="UTF-8">
    <title>VOIDE CLOTHING</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;900&family=JetBrains+Mono:wght@300;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        archive: {
                            paper: '#F5F5F5',
                            ink: '#1A1A1A',
                            line: '#E0E0E0'
                        }
                    },
                    fontFamily: {
                        display: ['"Inter"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F5F5F5;
            color: #1A1A1A;
        }

        /* Architecture lines */
        .specimen-border {
            border: 0.5px solid #E0E0E0;
        }

        .dark-border {
            border: 0.5px solid rgba(245, 245, 245, 0.2);
        }

        /* Subtle reveal animations */
        .reveal {
            animation: specReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes specReveal {
            from {
                opacity: 0;
                transform: translateY(20px);
                filter: blur(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
        }

        /* Hover Logic */
        .product-card .info {
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .product-card:hover .info {
            opacity: 1;
        }

        .product-card img {
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .product-card:hover img {
            transform: scale(1.03);
        }

        /* Mobile Menu Transitions */
        #mobile-menu {
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .menu-open {
            transform: translateY(0%);
        }

        .menu-closed {
            transform: translateY(-100%);
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #1A1A1A;
        }
    </style>
</head>
<!-- #/engine/head/encode/:END; -->