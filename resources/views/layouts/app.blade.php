<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penilaian PKUMI')</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root {
            --color-primary-start: #3c83f6;
            --color-primary-end: #0a5adb;
            --color-text-light: #ffffff;
            --color-text-medium: rgba(255, 255, 255, 0.8);
            --color-text-muted: rgba(255, 255, 255, 0.7);
            --color-text-faded: rgba(255, 255, 255, 0.6);
            --color-bg-accent: rgba(255, 255, 255, 0.2);
            --color-border-light: rgba(255, 255, 255, 0.1);
            --color-border-main: #e5e7eb;
            --sidebar-width: 256px;
            --sidebar-collapsed-width: 70px;
        }

        .sidebar-container {
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--color-primary-start) 0%, var(--color-primary-end) 100%);
            border-right: 1px solid var(--color-border-main);
            color: var(--color-text-light);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar-collapsed .sidebar-container {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-hidden .sidebar-container {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 24px 16px;
            border-bottom: 1px solid var(--color-border-light);
            gap: 12px;
            flex-shrink: 0;
            min-height: 80px;
        }

        .sidebar-footer {
            display: flex;
            align-items: center;
            padding: 18px 16px;
            border-top: 1px solid var(--color-border-light);
            gap: 12px;
            flex-shrink: 0;
            min-height: 70px;
        }

        .icon-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: var(--color-bg-accent);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .icon-wrapper .fa-solid {
            font-size: 20px;
        }

        .brand-info, .user-info {
            opacity: 1;
            transition: opacity 0.2s ease;
            overflow: hidden;
        }

        .sidebar-collapsed .brand-info,
        .sidebar-collapsed .user-info,
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .nav-title {
            opacity: 0;
            pointer-events: none;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            line-height: 28px;
            color: var(--color-text-light);
            margin: 0;
            white-space: nowrap;
        }

        .brand-subtitle {
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--color-text-muted);
            margin: 0;
            white-space: nowrap;
        }

        .sidebar-content {
            flex-grow: 1;
            padding: 32px 16px 16px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-collapsed .sidebar-content {
            padding: 32px 8px 16px;
        }

        .nav-title {
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            letter-spacing: 0.6px;
            color: var(--color-text-faded);
            margin: 0 0 24px 12px;
            text-transform: uppercase;
            transition: opacity 0.2s ease;
        }
        .sidebar-collapsed .nav-title {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }

        .main-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .main-nav li {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--color-text-medium);
            transition: all 0.2s ease;
            position: relative;
            white-space: nowrap;
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
            padding: 12px 8px;
        }

        .nav-link:hover {
            background-color: var(--color-bg-accent);
            color: var(--color-text-light);
            transform: translateX(4px);
        }

        .sidebar-collapsed .nav-link:hover {
            transform: none;
        }

        .nav-link.active {
            background-color: var(--color-bg-accent);
            color: var(--color-text-light);
            box-shadow: 0px 2px 4px -2px rgba(0, 0, 0, 0.1), 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-text {
            transition: opacity 0.2s ease;
            flex: 1;
        }

        .sidebar-collapsed .nav-text {
            display: none;
        }

        .nav-arrow {
            margin-left: auto;
            width: 16px;
            height: 16px;
            transition: all 0.2s ease;
            opacity: 0;
            transform: translateX(-8px);
        }

        .nav-link:hover .nav-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar-collapsed .nav-arrow {
            display: none;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            color: var(--color-text-light);
            margin: 0;
            white-space: nowrap;
        }

        .user-role {
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            color: var(--color-text-faded);
            margin: 0;
            white-space: nowrap;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
            border: none;
            background: none;
            cursor: pointer;
            color: inherit;
        }

        .logout-button:hover {
            background-color: var(--color-bg-accent);
        }

        .toggle-button {
            position: fixed;
            top: 26px;
            left: 15px;
            z-index: 1001;
            background: linear-gradient(135deg, var(--color-primary-start), var(--color-primary-end));
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .toggle-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .sidebar-collapsed .toggle-button {
            left: 15px;
        }

        .sidebar-hidden .toggle-button {
            left: 20px;
        }

        .content-wrapper {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-collapsed .content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-hidden .content-wrapper {
            margin-left: 0;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0;
            margin-left: 0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            min-height: 80px;
        }
        
        .page-header h1 {
            font-size: 20px;
            font-weight: 700;
            line-height: 28px;
            color: #1d2025;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .page-header img {
            max-height: 57px;
            vertical-align: middle;
        }

        .main-content-body {
            flex-grow: 1;
            padding: 24px;
        }

        .nav-tooltip {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 1002;
            pointer-events: none;
        }

        .nav-tooltip::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.8);
        }

        .sidebar-collapsed .nav-link:hover .nav-tooltip {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 768px) {
            .sidebar-container {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar-container.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .page-header p {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .toggle-button {
                top: 16px;
                left: 16px;
                width: 40px;
                height: 40px;
            }

            .main-content-body {
                padding: 16px;
            }
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Custom Select2 Styling for Tailwind */
        .select2-container--default .select2-selection--single {
            background-color: rgb(249 250 251);
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            height: 48px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
            padding-left: 0;
            color: rgb(55 65 81);
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: rgb(59 130 246);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .select2-dropdown {
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
        
        .select2-search--dropdown .select2-search__field {
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        
        .select2-search--dropdown .select2-search__field:focus {
            border-color: rgb(59 130 246);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .select2-results__option {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        
        .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(59 130 246);
        }
        
        .select2-results__option[aria-selected=true] {
            background-color: rgb(219 234 254);
            color: rgb(30 64 175);
        }
        
        /* Disabled state */
        .select2-container--default .select2-selection--single.select2-selection--disabled {
            background-color: rgb(229 231 235);
            cursor: not-allowed;
        }
        
        /* For green theme (bulk) */
        .select2-green.select2-container--default.select2-container--focus .select2-selection--single,
        .select2-green.select2-container--default.select2-container--open .select2-selection--single {
            border-color: rgb(16 185 129);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* ============================================
        SELECT2 MULTIPLE SELECTION - IMPROVED STYLING
        Based on Tailwind CSS & Reference Image
        ============================================ */

        /* Container */
        .select2-container--default .select2-selection--multiple {
            background-color: #ffffff;
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            min-height: 60px;
            padding: 0.5rem;
            font-size: 0.875rem;
            cursor: text;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* Focus State */
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: rgb(59 130 246);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Selected Items (Tags/Pills) - Improved to match reference */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgb(224 231 255);
            border: none;
            border-radius: 0.375rem;
            color: rgb(67 56 202);
            padding: 0.5rem 0.75rem;
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        /* Remove Button on Selected Items - Improved */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgb(99 102 241);
            font-size: 1.125rem;
            font-weight: 400;
            margin-left: 0;
            margin-right: 0.25rem;
            cursor: pointer;
            border: none;
            background: transparent;
            padding: 0;
            line-height: 1;
            opacity: 0.7;
            transition: all 0.2s ease;
            order: -1;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            opacity: 1;
            color: rgb(79 70 229);
            transform: scale(1.1);
        }

        /* Search Input Field inside Multiple Select */
        .select2-container--default .select2-selection--multiple .select2-search--inline {
            display: inline-flex;
            align-items: center;
            margin: 0;
            flex: 1;
            min-width: 200px;
        }

        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            margin: 0;
            padding: 0.5rem;
            font-size: 0.9375rem !important;
            color: rgb(55 65 81) !important;
            min-width: 200px;
            width: 100% !important;
            border: none;
            background: transparent;
            outline: none;
            height: auto !important;
            line-height: 1.5 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
            color: rgb(75 85 99) !important;
            opacity: 1 !important;
            font-weight: 400;
            font-size: 0.9375rem !important;
        }

        /* Placeholder when no items selected - CRITICAL FIX */
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: rgb(75 85 99) !important;
            font-size: 0.9375rem !important;
            padding: 0.5rem !important;
            font-weight: 400;
            opacity: 1 !important;
            display: block !important;
            line-height: 1.5 !important;
        }

        /* Fix for rendering issues */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 0 !important;
            width: 100%;
            align-items: center;
            box-sizing: border-box;
        }

        /* Ensure placeholder is visible when empty */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-search--inline {
            flex: 1 1 auto;
            min-width: 200px;
        }

        /* Override Select2 default hiding of placeholder */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            list-style: none;
        }

        /* Dropdown */
        .select2-container--default .select2-dropdown {
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05);
            margin-top: 0.25rem;
        }

        /* Search in Dropdown */
        .select2-container--default .select2-search--dropdown {
            padding: 0.5rem;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid rgb(209 213 219);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
            width: 100%;
            outline: none;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Results Container */
        .select2-results {
            max-height: 300px;
        }

        .select2-results__options {
            max-height: 300px;
        }

        /* Results Options */
        .select2-container--default .select2-results__option {
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            color: rgb(55 65 81);
            cursor: pointer;
        }

        /* Highlighted Option */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(239 246 255);
            color: rgb(59 130 246);
        }

        /* Selected Option in Dropdown */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgb(224 231 255);
            color: rgb(67 56 202);
            position: relative;
        }

        .select2-container--default .select2-results__option[aria-selected=true]::after {
            content: '✓';
            position: absolute;
            right: 1rem;
            color: rgb(67 56 202);
            font-weight: bold;
        }

        .select2-container--default .select2-results__option[aria-selected=true]:hover {
            background-color: rgb(199 210 254);
        }

        /* Disabled State */
        .select2-container--default .select2-selection--multiple.select2-selection--disabled {
            background-color: rgb(243 244 246);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .select2-container--default .select2-selection--multiple.select2-selection--disabled .select2-selection__choice {
            background-color: rgb(229 231 235);
            color: rgb(107 114 128);
        }

        /* Clear All Button */
        .select2-container--default .select2-selection--multiple .select2-selection__clear {
            color: rgb(107 114 128);
            font-size: 1.25rem;
            font-weight: bold;
            margin: 0.25rem 0.5rem;
            cursor: pointer;
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
            color: rgb(239 68 68);
        }

        /* Loading State */
        .select2-container--default .select2-results__option--loading {
            padding: 0.75rem 1rem;
            color: rgb(107 114 128);
        }

        /* No Results */
        .select2-container--default .select2-results__option--no-results {
            padding: 0.75rem 1rem;
            color: rgb(107 114 128);
            background-color: rgb(249 250 251);
        }

        /* Message for searching */
        .select2-container--default .select2-results__message {
            padding: 0.75rem 1rem;
            color: rgb(107 114 128);
        }

        /* Alternative Color Scheme - Green */
        .select2-green.select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgb(209 250 229);
            color: rgb(6 95 70);
        }

        .select2-green.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgb(16 185 129);
        }

        .select2-green.select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-green.select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: rgb(16 185 129);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .select2-green.select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(209 250 229);
            color: rgb(6 95 70);
        }

        /* Responsive - Mobile Optimization */
        @media (max-width: 640px) {
            .select2-container--default .select2-selection--multiple {
                min-height: 56px;
                font-size: 1rem;
            }
            
            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                font-size: 0.875rem;
                padding: 0.375rem 0.625rem;
            }
            
            .select2-container--default .select2-results__option {
                padding: 1rem;
                font-size: 1rem;
            }
            
            .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
                min-width: 150px;
            }
        }

        /* Fix for proper spacing */
        .select2-container--default .select2-selection--multiple .select2-selection__choice:not(:last-child) {
            margin-right: 0;
        }

        /* Ensure dropdown appears below select */
        .select2-container--default.select2-container--below .select2-selection--multiple {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .select2-container--default.select2-container--above .select2-selection--multiple {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body x-data="{ 
    sidebarCollapsed: window.innerWidth >= 1024 ? false : false,
    sidebarHidden: window.innerWidth < 768 ? true : false,
    showOverlay: false,
    init() {
        this.handleResize();
        window.addEventListener('resize', () => this.handleResize());
    },
    handleResize() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = true;
            this.sidebarCollapsed = false;
            this.showOverlay = false;
        } else {
            this.sidebarHidden = false;
            this.showOverlay = false;
        }
    },
    toggleSidebar() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = !this.sidebarHidden;
            this.showOverlay = !this.sidebarHidden;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        }
    },
    closeSidebar() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = true;
            this.showOverlay = false;
        }
    }
}" 
:class="{ 
    'sidebar-collapsed': sidebarCollapsed && !sidebarHidden,
    'sidebar-hidden': sidebarHidden 
}">

    <div class="sidebar-overlay" 
        :class="{ 'show': showOverlay }"
        @click="closeSidebar()"></div>

    <button class="toggle-button" @click="toggleSidebar()">
        <i class="fas" :class="sidebarCollapsed || sidebarHidden ? 'fa-bars' : 'fa-times'"></i>
    </button>

    <nav class="sidebar-container" 
        :class="{ 'show': !sidebarHidden }"
        @click.away="window.innerWidth < 768 && closeSidebar()">
        <header class="sidebar-header">
            <div class="icon-wrapper">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div class="brand-info">
                <h1 class="brand-name">PKUMI</h1>
                <p class="brand-subtitle">System</p>
            </div>
        </header>

        <div class="sidebar-content">
            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-tachometer-alt nav-icon"></i>
                            <span class="nav-text">Dashboard</span>
                            <div class="nav-tooltip">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('periods.index') }}" class="nav-link {{ request()->routeIs('periods.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-graduation-cap nav-icon"></i>
                            <span class="nav-text">Manage Tahun Angkatan</span>
                            <div class="nav-tooltip">Manage Tahun Angkatan</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('semesters.index') }}" class="nav-link {{ request()->routeIs('semesters.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-graduation-cap nav-icon"></i>
                            <span class="nav-text">Manage Semester</span>
                            <div class="nav-tooltip">Manage Semester</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('years.index') }}" class="nav-link {{ request()->routeIs('years.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-graduation-cap nav-icon"></i>
                            <span class="nav-text">Manage Angkatan</span>
                            <div class="nav-tooltip">Manage Angkatan</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student_classes.index') }}" class="nav-link {{ request()->routeIs('student_classes.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-chalkboard-teacher nav-icon"></i>
                            <span class="nav-text">Manage Kelas</span>
                            <div class="nav-tooltip">Manage Kelas</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-users nav-icon"></i>
                            <span class="nav-text">Manage Mahasiswa</span>
                            <div class="nav-tooltip">Manage Mahasiswa</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-book nav-icon"></i>
                            <span class="nav-text">Manage Mata Kuliah</span>
                            <div class="nav-tooltip">Manage Mata Kuliah</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('enrollments.index') }}" class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-users nav-icon"></i>
                            <span class="nav-text">Enrollments</span>
                            <div class="nav-tooltip">Enrollments</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-clipboard-list nav-icon"></i>
                            <span class="nav-text">Manage Nilai</span>
                            <div class="nav-tooltip">Manage Nilai</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grade-weights.index') }}" class="nav-link {{ request()->routeIs('grade-weights.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-balance-scale-left nav-icon"></i>
                            <span class="nav-text">Manage Bobot Nilai</span>
                            <div class="nav-tooltip">Manage Bobot Nilai</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-chart-bar nav-icon"></i>
                            <span class="nav-text">Laporan</span>
                            <div class="nav-tooltip">Laporan</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('log.index') }}" class="nav-link {{ request()->routeIs('log.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-history nav-icon"></i>
                            <span class="nav-text">Log History</span>
                            <div class="nav-tooltip">Log History</div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <footer class="sidebar-footer">
            <div class="icon-wrapper">
                <i class="fa-solid fa-circle-user"></i>
            </div>
            <div class="user-info">
                <p class="user-name">Admin User</p>
                <p class="user-role">Administrator</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="logout-button" aria-label="Logout dari akun" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </footer>
    </nav>

    <main class="content-wrapper">
        <header class="page-header">
            <h1>
                <i class="fa-solid fa-graduation-cap"></i>
                Sistem Penilaian Mahasiswa
            </h1>
            <img src="{{ asset('images/logo0pkumi.png') }}" alt="Logo PKUMI">
        </header>

        <div class="main-content-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <ul class="list-none m-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>