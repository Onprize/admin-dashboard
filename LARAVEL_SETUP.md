# Laravel Admin Panel Frontend - Setup & Architecture Guide

## Overview

This is a **PHP/Laravel-based frontend** for the On Prize admin panel that consumes the existing REST API backend. It replaces the Next.js frontend with a traditional server-side rendered Blade templating approach.

**Key Point**: This is frontend-only. The backend API remains unchanged.

---

## Quick Setup

### Prerequisites
- PHP 8.3+
- Composer
- Laravel 13
- Node.js (for Tailwind CSS compilation)

### Installation

1. **Navigate to the Laravel app directory**
   ```bash
   cd admin-panel/laravel-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Setup environment file**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with:
   ```
   APP_NAME="On Prize Admin"
   APP_ENV=local
   APP_KEY=base64:...  # Generate with: php artisan key:generate
   APP_URL=http://localhost:8000
   
   API_BASE_URL=https://backendapi.on-prize.online/api
   
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   ```

5. **Generate app key**
   ```bash
   php artisan key:generate
   ```

6. **Create session table** (if using database sessions)
   ```bash
   php artisan session:table
   php artisan migrate
   ```

7. **Build Tailwind CSS**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```
   Open: http://localhost:8000/login

---

## Architecture Overview

### File Structure

```
laravel-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── LoginController.php          # Authentication
│   │   │   ├── DashboardController.php      # Dashboard stats
│   │   │   ├── UserController.php           # Users management
│   │   │   ├── RestaurantController.php     # Restaurants management
│   │   │   ├── OrderController.php          # Orders listing
│   │   │   ├── DeliveryPartnerController.php# Delivery partners
│   │   │   ├── SettingsController.php       # App settings
│   │   │   ├── ApiTokenCheck.php            # Auth middleware
│   │   │   └── RedirectIfAuthenticated.php  # Guest middleware
│   └── Services/                            # (Optional: API client service)
│
├── resources/
│   ├── views/
│   │   ├── app.blade.php                    # Main layout
│   │   ├── login.blade.php                  # Login page
│   │   ├── dashboard.blade.php              # Dashboard
│   │   ├── users.blade.php                  # Users list
│   │   ├── restaurants.blade.php            # Restaurants list
│   │   ├── orders.blade.php                 # Orders list
│   │   ├── delivery_partners.blade.php      # Partners list
│   │   ├── settings.blade.php               # Settings page
│   │   ├── sidebar.blade.php                # Navigation sidebar
│   │   └── navbar.blade.php                 # Top navbar
│   └── css/
│       └── app.css                          # Tailwind CSS
│
├── routes/
│   └── web.php                              # All web routes
│
├── config/
│   └── api.php                              # API configuration
│
├── bootstrap/
│   └── app.php                              # Middleware registration
│
├── .env                                     # Environment config
├── composer.json                            # PHP dependencies
├── package.json                             # Node dependencies
└── tailwind.config.js                       # Tailwind configuration
```

---

## How It Works

### 1. **Authentication Flow**

```
User submits login form
    ↓
LoginController@login (processes form)
    ↓
Makes POST request to: /auth/login (API)
    ↓
API returns: {access_token, user}
    ↓
Token stored in: $_SESSION['api_token']
User data stored in: $_SESSION['admin_user']
    ↓
Redirect to dashboard (/dashboard)
```

**Session Storage**: Secure server-side sessions (not localStorage)

### 2. **API Request Cycle**

Every HTTP request to a protected route:

```
1. Route middleware checks: session('api_token') exists
2. If no token → redirect to /login
3. If token exists → Controller receives request
4. Controller makes API call with Authorization header
5. API responses automatically handled
   - 401 Unauthorized → Clear session, redirect to /login
   - 4xx/5xx Errors → Flash error message to user
   - 2xx Success → Data passed to view
6. View renders with returned data
```

### 3. **Middleware Protection**

All protected routes use the `auth.api` middleware (aliases configured in `bootstrap/app.php`):

```php
Route::middleware('auth.api')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    // ...
});
```

### 4. **API Headers**

Every API request includes:

```
Authorization: Bearer {token_from_session}
Content-Type: application/json
Accept: application/json
ngrok-skip-browser-warning: true  # For ngrok-tunneled APIs
```

---

## Controllers & API Integration

### UserController Example

```php
class UserController extends Controller
{
    protected function getApiHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'ngrok-skip-browser-warning' => 'true',
        ];

        if ($token = session('api_token')) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    public function index(Request $request)
    {
        // Make API call
        $response = Http::withHeaders($this->getApiHeaders())
            ->get(config('api.base_url') . '/admin/users', [
                'page' => $request->input('page', 1),
                'search' => $request->input('search', ''),
            ]);

        // Handle errors
        if ($response->failed()) {
            if ($response->status() === 401) {
                return redirect('/login');
            }
            return view('users')->with('error', 'Failed to load users');
        }

        // Pass data to view
        return view('users', [
            'users' => $response->json('data'),
            'pagination' => $response->json('meta'),
        ]);
    }
}
```

---

## Views & Blade Templates

### Main Layout (app.blade.php)

```blade
@extends('app')
@section('title', 'Page Title')
@section('page-title', 'Page Title')

@section('content')
    <!-- Your content here -->
@endsection
```

### Displaying Data

```blade
<!-- User List -->
@forelse ($users as $user)
    <tr>
        <td>{{ $user['name'] }}</td>
        <td>{{ $user['email'] }}</td>
        <td>
            @if ($user['status'] === 'active')
                <span class="badge badge-success">Active</span>
            @else
                <span class="badge badge-danger">Inactive</span>
            @endif
        </td>
    </tr>
@empty
    <tr><td colspan="3">No users found</td></tr>
@endforelse
```

### Form Example (POST with CSRF)

```blade
<form action="{{ route('users.update-status', $user['id']) }}" method="POST">
    @csrf
    @method('PUT')
    
    <select name="status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    
    <button type="submit">Update</button>
</form>
```

---

## Routes Reference

### Public Routes
- `GET /login` - Show login form
- `POST /login` - Process login

### Protected Routes (require auth)
- `GET /dashboard` - Dashboard with stats
- `GET /users` - Users list
- `GET /users/{id}` - User details
- `PUT /users/{id}/status` - Update user status
- `GET /restaurants` - Restaurants list
- `GET /restaurants/{id}` - Restaurant details
- `PUT /restaurants/{id}/approve` - Approve restaurant
- `PUT /restaurants/{id}/reject` - Reject restaurant
- `PUT /restaurants/{id}/toggle-status` - Toggle status
- `GET /orders` - Orders list
- `GET /orders/{id}` - Order details
- `GET /delivery-partners` - Delivery partners list
- `GET /delivery-partners/{id}` - Partner details
- `PUT /delivery-partners/{id}/verify` - Verify partner
- `PUT /delivery-partners/{id}/reject` - Reject partner
- `PUT /delivery-partners/{id}/toggle-status` - Toggle status
- `GET /settings` - Settings page
- `PUT /settings/{key}` - Update setting
- `POST /logout` - Logout

---

## Development

### Watch CSS Changes
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

### Run Tests
```bash
php artisan test
```

### Database Sessions (Optional)
If using database sessions instead of files:
```bash
php artisan session:table
php artisan migrate
```

---

## Error Handling

### 401 Unauthorized
- Session token expired or invalid
- Controller auto-redirects to `/login`
- Session is cleared

### 4xx Client Errors
- Flash error message shown to user
- User stays on current page

### Network Errors
- Caught and displayed as "Connection error"
- User can retry

### Validation Errors
- From API or form validation
- Displayed in alert box at top of page

---

## Security Features

1. **Server-Side Sessions** - Token stored securely on server, not in client-side localStorage
2. **CSRF Protection** - All forms protected with @csrf token
3. **Middleware** - Authentication checked before accessing protected routes
4. **Auto-Logout** - Session expires after configured time (default: 120 minutes)
5. **Authorization Header** - Token automatically added to all API requests
6. **Error Messages** - Generic messages shown to prevent information leakage

---

## Troubleshooting

### "Not authenticated" Errors
- Check if API_BASE_URL in .env is correct
- Verify login credentials match backend user
- Ensure session table is created: `php artisan migrate`

### Tailwind CSS Not Loading
- Run `npm run build`
- Check @vite directive in views

### CSRF Token Errors
- Ensure all POST/PUT/DELETE forms have @csrf
- Check SESSION_DRIVER in .env

### API Connection Errors
- Verify API_BASE_URL is accessible
- Check ngrok tunnel is running (if using ngrok)
- Verify backend is running

---

## Next Steps

1. ✅ Configure `.env` with correct API_BASE_URL
2. ✅ Run `composer install && npm install`
3. ✅ Set up database sessions: `php artisan migrate`
4. ✅ Start server: `php artisan serve`
5. ✅ Login with: admin@fooddelivery.com / password
6. ✅ Test all pages

---

## Support

For issues or questions, check:
- Backend API endpoints in `backend-laravel/routes/`
- Controller logic in `app/Http/Controllers/`
- View templates in `resources/views/`

