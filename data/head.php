<!-- #/engine/head/encode/:BEGIN -->
<!DOCTYPE html>
<html lang="en">
<!-- 

    #   TITLE   : PVTT Application Interface
    #   DESC    : This is the interface for the PVTT Application, which is a collection of tools and features designed to enhance user experience and provide additional functionality. The interface is designed to be user-friendly and intuitive, allowing users to easily navigate and access the various features of the application.
    #   PROPRIETOR: VARSITYMARKET_TECHNOLOGIES
    #   VERSION : v2.1.1.1
    #   AUTHOR  : LEVIDOC AGENCY  
    #   RELEASE : 2026/07/18
    #   CATEGORY: ONLINE STORE

-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Thoughts</title>
    
    <!-- Unified Typography & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* --------------------------------------------------
         * Design Tokens & CSS Variables (Strict Rules Enforced)
         * -------------------------------------------------- */
        :root {
            /* Palette: High-Contrast Monochromatic & Acid Accent */
            --bg-page: #F4F5F7;
            --bg-card: #FFFFFF;
            --bg-card-subtle: #FAFAFA;
            
            --border-light: #E4E7EC;
            --border-medium: #D0D5DD;

            --text-main: #0F172A;
            --text-muted: #475569;
            --text-subtle: #94A3B8;

            --acid: #CCFF00;
            --acid-hover: #B8E600;

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;

            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-machined: 0 1px 0 #FFFFFF inset, 0 1px 3px rgba(0,0,0,0.06);
        }

        /* --------------------------------------------------
         * Base & Reset
         * -------------------------------------------------- */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-main);
            letter-spacing: -0.022em;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .container {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Machined Card Component (Smooth Borders, No Gradients/Blur) */
        .card-machined {
            background: var(--bg-card);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-machined);
            position: relative;
        }

        /* Interactive Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-sm);
            transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-acid {
            background-color: var(--acid);
            color: var(--text-main);
            border: 1px solid var(--text-main);
            box-shadow: 2px 2px 0px var(--text-main);
        }

        .btn-acid:hover {
            background-color: var(--acid-hover);
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0px var(--text-main);
        }

        .btn-acid:active {
            transform: translate(0px, 0px);
            box-shadow: 0px 0px 0px var(--text-main);
        }

        .btn-outline {
            background-color: var(--bg-card);
            color: var(--text-main);
            border: 1px solid var(--border-medium);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline:hover {
            border-color: var(--text-main);
            background-color: var(--bg-card-subtle);
        }

        /* --------------------------------------------------
         * Header / Navigation
         * -------------------------------------------------- */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: var(--bg-page);
            border-bottom: 1px solid var(--border-medium);
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4.5rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--text-main);
            text-decoration: none;
            letter-spacing: -0.03em;
        }

        .logo-icon {
            width: 2rem;
            height: 2rem;
            background: var(--text-main);
            color: var(--acid);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .nav-link:hover {
            color: var(--text-main);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-main);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            inset: 4.5rem 0 0 0;
            background: var(--bg-page);
            padding: 2rem;
            flex-direction: column;
            gap: 1.5rem;
            border-bottom: 1px solid var(--border-medium);
            z-index: 49;
        }

        .mobile-menu.active {
            display: flex;
        }

        /* --------------------------------------------------
         * Policy Page Layout
         * -------------------------------------------------- */
        .policy-section {
            padding: 4rem 0 6rem 0;
        }

        .policy-header {
            margin-bottom: 3rem;
        }

        .policy-title {
            font-size: clamp(2.25rem, 4vw, 3.25rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .policy-meta {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .policy-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Sidebar Navigation */
        .policy-sidebar {
            padding: 1.5rem;
            position: sticky;
            top: 6.5rem;
        }

        .sidebar-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-subtle);
            margin-bottom: 1rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
            list-style: none;
        }

        .sidebar-link {
            display: block;
            padding: 0.625rem 0.875rem;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        .sidebar-link:hover {
            background-color: var(--bg-card-subtle);
            color: var(--text-main);
        }

        /* Content Sections */
        .policy-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .policy-card {
            padding: 2.25rem;
        }

        .section-heading {
            font-size: 1.375rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-heading i {
            color: var(--text-main);
        }

        .policy-text {
            color: var(--text-muted);
            font-size: 0.9375rem;
            line-height: 1.65;
            margin-bottom: 1rem;
        }

        .policy-text:last-child {
            margin-bottom: 0;
        }

        .policy-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .policy-list-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9375rem;
            color: var(--text-muted);
        }

        .policy-list-item i {
            margin-top: 0.25rem;
            flex-shrink: 0;
            color: var(--text-main);
        }

        /* --------------------------------------------------
         * Footer
         * -------------------------------------------------- */
        footer {
            background-color: var(--bg-card);
            border-top: 1px solid var(--border-medium);
            padding: 4rem 0 2rem 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr repeat(3, 1fr);
            gap: 3rem;
            margin-bottom: 4rem;
        }

        .footer-brand p {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 1rem;
            max-width: 280px;
        }

        .footer-column-title {
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.875rem;
            transition: color 0.15s ease;
        }

        .footer-links a:hover {
            color: var(--text-main);
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 2rem;
            border-top: 1px solid var(--border-light);
            font-size: 0.8125rem;
            color: var(--text-subtle);
        }

        /* --------------------------------------------------
         * Media Queries
         * -------------------------------------------------- */
        @media (max-width: 992px) {
            .policy-grid {
                grid-template-columns: 1fr;
            }

            .policy-sidebar {
                position: static;
            }

            .footer-grid {
                grid-template-columns: 1fr repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nav-links, .nav-actions {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<!-- #/engine/head/encode/:END; -->