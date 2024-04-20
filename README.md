
# Laravel User Management System API

This project is a User Management System API developed using Laravel. It provides endpoints for user authentication, registration, user management, and more.

## Table of Contents

- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Running the Application](#running-the-application)
- [Usage](#usage)
  - [Authentication](#authentication)
  - [Endpoints](#endpoints)
- [Testing](#testing)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)

## Getting Started

### Prerequisites

Before running this application, ensure you have the following prerequisites installed:

- [PHP](https://www.php.net/) (>= 7.4 recommended)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/) database

### Installation

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/JayEs23/apex-api.git
   ```

2. Navigate to the project directory:

   ```bash
   cd apex-api
   ```

3. Install PHP dependencies using Composer:

   ```bash
   composer install
   ```

4. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

5. Generate a new application key:

   ```bash
   php artisan key:generate
   ```

6. Update the `.env` file with your database credentials and other configuration options.

7. Run the database migrations to create the necessary tables:

   ```bash
   php artisan migrate
   ```

8. (Optional) Seed the database with sample data:

   ```bash
   php artisan db:seed
   ```

### Running the Application

To run the application, use the following command:

```bash
php artisan serve
```

This will start a development server, and you can access the application at `http://localhost:8000`.

## Usage

### Authentication

The API uses Laravel Passport for authentication. You need to register and authenticate users to access protected endpoints.

To register a new user, send a POST request to `/api/authentication/register` with the user's name, email, and password.

To authenticate a user, send a POST request to `/api/authentication/login` with the user's email and password. This will return an access token that you can use to make authenticated requests to protected endpoints.

### Endpoints

The API provides the following endpoints:

- **User Management**: CRUD operations for managing user profiles.
  - `POST /api/admin/users`: Create a new user (admin only).
  - `GET /api/admin/users`: Get a list of all users (admin only).
  - `PUT /api/admin/users/{id}`: Update a user's profile (admin only).
  - `DELETE /api/admin/users/{id}`: Delete a user (admin only).
- **Profile Management**: Update the authenticated user's profile.
  - `PUT /api/profile/update`: Update the authenticated user's profile.
  - `PUT /api/profile/password`: Update the authenticated user's password.
- **Authentication**:
  - `POST /api/authentication/register`: Register a new user.
  - `POST /api/authentication/login`: Log in an existing user.
  - `POST /api/logout`: Log out the authenticated user.

Refer to the API documentation for detailed information on each endpoint.

## Testing

To run the PHPUnit tests, use the following command:

```bash
php artisan test
```

This will run all the tests in the `tests` directory and display the results.


# API Documentation

This document provides detailed documentation for the endpoints available in the Laravel User Management System API.

## Base URL

The base URL for accessing the API is `http://localhost:8000/api`.

## Authentication

### Register a New User

Endpoint: `POST /authentication/register`

#### Request Parameters

- `name` (string, required): The name of the user.
- `email` (string, required): The email address of the user.
- `password` (string, required): The password for the user.

#### Example Request

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### Possible Responses

- `201 Created`: User registered successfully.
  ```json
  {
      "status": "success",
      "message": "User registered successfully",
      "data": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2022-04-25T12:00:00Z",
          "updated_at": "2022-04-25T12:00:00Z"
      }
  }
  ```

- `422 Unprocessable Entity`: Validation error (e.g., invalid email format).
  ```json
  {
      "status": "error",
      "message": "Validation error",
      "data": {
          "email": ["The email must be a valid email address."]
      }
  }
  ```

### Login

Endpoint: `POST /authentication/login`

#### Request Parameters

- `email` (string, required): The email address of the user.
- `password` (string, required): The password for the user.

#### Example Request

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Possible Responses

- `200 OK`: Login successful.
  ```json
  {
      "status": "success",
      "message": "Login successful",
      "data": {
          "access_token": "<access-token>",
          "token_type": "Bearer",
          "expires_in": 3600
      }
  }
  ```

- `401 Unauthorized`: Incorrect email or password.
  ```json
  {
      "status": "error",
      "message": "Invalid credentials",
      "data": null
  }
  ```

### Logout

Endpoint: `POST /logout`

#### Authorization

Include the access token obtained during login in the Authorization header as a Bearer token.

#### Possible Responses

- `200 OK`: Logout successful.
  ```json
  {
      "status": "success",
      "message": "Logged out successfully",
      "data": null
  }
  ```

- `401 Unauthorized`: Invalid or missing access token.
  ```json
  {
      "status": "error",
      "message": "Unauthenticated.",
      "data": null
  }
  ```

### User Management

#### Creating a User (Admin Only)

Endpoint: `POST /admin/users`

#### Request Parameters

- `name` (string, required): The name of the user.
- `email` (string, required): The email address of the user.
- `password` (string, required): The password for the user.
- `role` (string, required): The role of the user (`user` or `admin`).

#### Authorization

Include the access token of an admin user obtained during login in the Authorization header as a Bearer token.

#### Example Request

```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "role": "user"
}
```

#### Possible Responses

- `201 Created`: User created successfully.
  ```json
  {
      "status": "success",
      "message": "User created successfully",
      "data": {
          "id": 2,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "role": "user",
          "created_at": "2022-04-25T12:00:00Z",
          "updated_at": "2022-04-25T12:00:00Z"
      }
  }
  ```

- `422 Unprocessable Entity`: Validation error or duplicate email.
  ```json
  {
      "status": "error",
      "message": "Validation error",
      "data": {
          "email": ["The email has already been taken."]
      }
  }
  ```

#### Retrieving a List of Users (Admin Only)

Endpoint: `GET /admin/users`

#### Authorization

Include the access token of an admin user obtained during login in the Authorization header as a Bearer token.

#### Possible Responses

- `200 OK`: Users retrieved successfully.
  ```json
  {
      "status": "success",
      "message": "Users retrieved successfully",
      "data": [
          {
              "id": 1,
              "name": "John Doe",
              "email": "john@example.com",
              "role": "admin",
              "created_at": "2022-04-25T12:00:00Z",
              "updated_at": "2022-04-25T12:00:00Z"
          },
          {
              "id": 2,
              "name": "Jane Doe",
              "email": "jane@example.com",
              "role": "user",
              "created_at": "2022-04-25T12:00:00Z",
              "updated_at": "2022-04-25T12:00:00Z"
          }
      ]
  }
  ```

- `401 Unauthorized`: Access denied.
  ```json
  {
      "status": "error",
      "message": "Unauthorized.",
      "data": null
  }
  ```


## Contributing

Contributions are welcome! Please read the [Contributing Guidelines](CONTRIBUTING.md) for more information.

## License

This project is licensed under the [MIT License](LICENSE).

