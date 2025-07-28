# 🎓 Oyu Academy Platform - School Management SaaS

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com/)

A comprehensive School Management System built as a Software-as-a-Service (SaaS) platform. This modern educational platform streamlines school operations, enhances learning experiences, and builds stronger educational communities.

## ✨ Features

- **🏫 Multi-School Management** - Support for multiple schools with isolated data
- **👥 User Management** - Students, Teachers, Administrators, and Parents
- **📚 Academic Management** - Classes, Subjects, Timetables, and Curriculum
- **📊 Assessment & Grading** - Exams, Assignments, and Grade Management
- **💬 Communication Hub** - Announcements, Messages, and Notifications
- **📱 Mobile-First Design** - Responsive interface for all devices
- **🔐 Role-Based Access Control** - Secure permissions system
- **📈 Analytics & Reports** - Comprehensive reporting dashboard
- **💳 Subscription Management** - Flexible pricing packages
- **🌐 Multi-Language Support** - Internationalization ready

## 🏗️ Architecture

Built with modern web technologies:

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates with modern CSS/JS
- **Database**: MySQL/MariaDB
- **Styling**: Custom CSS with Glassmorphism design
- **Authentication**: Laravel Sanctum
- **Real-time**: Laravel Broadcasting
- **File Storage**: Laravel Storage (Local/S3)

## 🚀 Quick Start

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (for asset compilation)
- `mysqldump` utility (for database operations)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/academy.oyuplatform.com.git
   cd academy.oyuplatform.com
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   ```

5. **Configure environment variables**
   
   Edit your `.env` file with the following essential configurations:
   
   ```env
   # Application
   APP_NAME="Oyu Academy Platform"
   APP_ENV=local
   APP_KEY=base64:your-app-key-here
   APP_DEBUG=true
   APP_URL=http://localhost
   
   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=oyu_academy
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # Mail Configuration
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email@domain.com
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   
   # File Storage
   FILESYSTEM_DISK=local
   
   # Queue
   QUEUE_CONNECTION=database
   ```

6. **Generate application key**
   ```bash
   php artisan key:generate
   ```

7. **Verify mysqldump installation**
   ```bash
   mysqldump --version
   ```
   If not installed, install it using your system's package manager.

8. **Run database migrations**
   ```bash
   php artisan migrate
   ```

9. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

10. **Compile assets**
    ```bash
    npm run dev
    ```

11. **Start the development server**
    ```bash
    php artisan serve
    ```

Your application will be available at `http://localhost:8000`

## 🔑 Default Credentials

### Super Administrator
- **Email**: `defaultadmin@oyuacademy.co.ke`
- **Password**: `password`

⚠️ **Security Note**: Change these credentials immediately after first login!

## 📱 Usage

### For Schools
1. Register your school on the platform
2. Choose a subscription plan
3. Set up your school profile and structure
4. Add teachers, students, and administrative staff
5. Configure classes, subjects, and timetables
6. Start managing your educational operations

### For Students & Parents
1. Receive login credentials from your school
2. Access your personalized dashboard
3. View assignments, grades, and announcements
4. Communicate with teachers and peers
5. Track academic progress

## 🛠️ Development

### Project Structure
```
academy.oyuplatform.com/
├── app/                    # Application logic
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Eloquent models
│   ├── Providers/         # Service providers
│   └── ...
├── resources/             # Views and assets
│   ├── views/            # Blade templates
│   └── lang/             # Language files
├── database/             # Migrations and seeders
├── public/               # Public assets
└── routes/               # Route definitions
```

### Running Tests
```bash
php artisan test
```

### Code Style
We follow PSR-12 coding standards. Run the following to check code style:
```bash
composer run-script cs-check
```

### Building for Production
```bash
npm run production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Configuration

### Multi-School Setup
Each school operates in isolation with:
- Separate databases or schemas
- Individual subscription plans
- Custom branding and settings
- Independent user management

### Subscription Management
- Trial periods for new schools
- Flexible billing cycles
- Feature-based plan restrictions
- Automatic plan upgrades/downgrades

### Customization
- Theme customization
- Logo and branding upload
- Custom domain support
- Localization options

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificates
- [ ] Configure mail settings
- [ ] Set up file storage (S3/etc.)
- [ ] Configure caching (Redis/Memcached)
- [ ] Set up queue workers
- [ ] Configure backup strategies

### Recommended Hosting
- **VPS/Dedicated**: DigitalOcean, Linode, AWS EC2
- **Shared**: Laravel Forge, Cloudways
- **Database**: MySQL 8.0+, MariaDB 10.4+
- **Cache**: Redis or Memcached
- **CDN**: CloudFlare, AWS CloudFront

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Documentation
- [User Guide](docs/user-guide.md)
- [API Documentation](docs/api.md)
- [Installation Guide](docs/installation.md)

### Community
- [Discord Server](https://discord.gg/oyuacademy)
- [GitHub Discussions](https://github.com/yourusername/academy.oyuplatform.com/discussions)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/oyu-academy)

### Professional Support
For enterprise support and custom development:
- 📧 Email: support@oyuacademy.co.ke
- 🌐 Website: [https://oyuacademy.co.ke](https://oyuacademy.co.ke)
- 📞 Phone: +254-XXX-XXXX

## 🛣️ Roadmap

- [ ] Mobile Applications (iOS/Android)
- [ ] Advanced Analytics Dashboard
- [ ] Integration with LMS platforms
- [ ] AI-powered recommendations
- [ ] Video conferencing integration
- [ ] Advanced reporting modules
- [ ] Multi-language expansion

## 🙏 Acknowledgments

- Arnold, Ambrose and Mbithi for the amazing framework
- Laravel Team for the incredible Laravel framework
- All contributors who help improve this platform
- Educational institutions using and testing the platform

---

<div align="center">
  <p>Built with ❤️ by the Oyu Academy Team</p>
  <p>
    <a href="https://oyuacademy.co.ke">🌐 Website</a> •
    <a href="mailto:support@oyuacademy.co.ke">📧 Contact</a> •
    <a href="https://twitter.com/oyuacademy">🐦 Twitter</a>
  </p>
</div>