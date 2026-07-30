# 🛒 Katalogin — WhatsApp E-Catalog & Ordering System

A modern SaaS platform designed for **F&B businesses, retail stores, service providers, and online shops** to easily create their own digital catalog. Customers can browse products, add them to a cart, and send a fully formatted order directly to the seller's WhatsApp. Built with the powerful **TALL stack** (Tailwind CSS, Alpine.js, Laravel 11, Livewire 3), Katalogin delivers a seamless, responsive, and highly optimized experience.

## ✨ Comprehensive Key Features

### 🌐 Global & Modern Design Architecture
- **Bilingual Localization (ID / EN)**: Fully localized interface supporting seamless real-time switching between English and Indonesian. Built with modular JSON language files to allow effortless extension.
- **Persistent Dark & Light Mode**: Sleek, instant theme switching with persistent user preference storage and system color scheme auto-detection. Designed with curated color palettes and modern aesthetics.
- **SaaS-Style Landing Page**: Built-in responsive marketing landing page featuring interactive components, modern typography, responsive navbar/footer layouts, and a dedicated testimonial slider.

### 📱 For Customers — Frictionless WhatsApp Ordering
- **Mobile-Optimized Digital Catalog**: Customers can view the seller's store via a unique link and browse products neatly organized by categories.
- **Interactive Shopping Cart**: Slide-up interactive cart featuring real-time price calculations and easy quantity adjustments.
- **Direct WhatsApp Checkout**: No payment gateway setup required. Customers checkout and instantly send a beautifully formatted order summary (including items, quantities, and total price) directly to the seller's WhatsApp number.

### 💻 For Vendors & Staff — Powerful Store Management
- **Multi-Tenant Architecture**: Users can register and easily set up their own independent digital store complete with logo, banner, and WhatsApp contact details.
- **Product & Category CRUD**: Fast, Zero-reload product management powered by Livewire 3 and Alpine.js. Supports multiple image uploads per product.
- **Real-Time Analytics & Tracking**: Integrated tracking system to monitor **Store Visitors**, **Product Views**, and **WhatsApp Checkout Clicks**, giving vendors valuable insights into their catalog's performance.

### 📊 For Management — Security & Administration
- **Role-Based Access Control (RBAC)**: Fine-grained permission management powered by Spatie for diverse operational roles (Super Admin, Vendor, etc.).
- **Dynamic Application Settings**: Configure SMTP Host, Port, Application Appearance (Logo, Favicon), and toggles directly from the Admin Settings UI without touching `.env`.
- **Advanced Two-Factor OTP Login**: Optional secure 6-digit OTP code login via email to prevent spam and ensure account security.
- **Activity Logging**: Comprehensive activity logs to monitor user actions across the platform.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Frontend**: Livewire 3 + Alpine.js
- **Styling**: Tailwind CSS
- **DataTables**: Livewire PowerGrid
- **Alerts & Modals**: SweetAlert2
- **Permissions**: Spatie Laravel Permission

## 🚀 Getting Started

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Install Node dependencies: `npm install`
4. Copy `.env.example` to `.env` and configure your database credentials
5. Generate application key: `php artisan key:generate`
6. Run migrations and seeders: `php artisan migrate --seed`
7. Compile frontend assets: `npm run build`
8. Start the development server: `php artisan serve`

---
*Built with ❤️ for modern MSMEs (UMKM).*
