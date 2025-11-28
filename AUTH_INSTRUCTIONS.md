# Authentication Instructions

## Overview
The API uses Laravel Sanctum for authentication. You need to obtain a token via the login endpoint and send it in the `Authorization` header for protected routes.

## 1. Login
**Endpoint:** `POST /api/login`

**Body:**
```json
{
    "email": "admin@solar.com",
    "password": "password"
}
```

**Response:**
```json
{
    "access_token": "1|...",
    "token_type": "Bearer"
}
```

## 2. Access Protected Routes
Include the token in the `Authorization` header:

**Header:**
`Authorization: Bearer <your_access_token>`

**Example:**
```bash
curl -H "Authorization: Bearer 1|..." http://localhost:8088/api/clients
```

## 3. Default User
A default user has been created by the seeder:
- **Email:** `admin@solar.com`
- **Password:** `password`
