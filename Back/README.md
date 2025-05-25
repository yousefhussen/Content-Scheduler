
# Backend for Post Management System

This is the backend of the Post Management System, built using Laravel 10. Below is an overview of the Laravel version, packages used, installation instructions, and key features.

---

## Laravel Version and Packages

- **Laravel Version**: 10
- **Key Packages**:
  - **Sanctum**: Used for authentication with session cookies instead of tokens, as it is more appropriate for this simple task.
  - **Nwidart Laravel Modules**: Implements the HMVC (Hierarchical Model-View-Controller) design pattern for modular development.

---

## Features

### Core Features
- **Authentication**:
  - User login and registration using Laravel Sanctum.
  - Basic user profile management.
- **Post Management**:
  - Create, update, and delete posts with platform selection.
  - Schedule posts for future publication.
  - Filter posts by status and date.
- **Platform Management**:
  - List available platforms.
  - Toggle active platforms for a user.
- **Post Scheduling**:
  - Laravel command/job to process due posts.
  - Mock the actual publishing process.
- **Validation**:
  - Handle basic validation for different platform requirements (e.g., character limits).

### Creative Features
- **Post Analytics**:
  - Show posts per platform.
  - Display publishing success rate.
  - Provide counts for scheduled vs. published posts.
- **Rate Limiting**:
  - Restrict users to a maximum of 10 scheduled posts per day.
- **Activity Logging**:
  - Log panel showing user actions.
- **Custom Feature**:
  - Freedom to add any innovative feature that enhances the platform.

---


## Testing with Postman

If the frontend is not working, you can use Postman to test the backend API. A Postman environment file has been provided for your convenience.

1. **Open The Workspace**:
   [Postman Environment File](<https://www.postman.com/iti-graduation-project-caseguard/workspace/contentscheduler>)


2. **Start The Backend**:

    - Ensure the Laravel backend is running by executing:
      ```bash
      php artisan serve
      ```
      

3. **Session Domain**:
    - Ensure the `SESSION_DOMAIN` in your `.env` file is set to `null` when testing with Postman.


---



## Installation and Running the Project

clone the repository and follow these steps to set up the backend:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yousefhussen/Content-Scheduler
   ```

2. **Navigate to the Backend Folder**:
   ```bash
   cd Back
   ```

3. **Install Dependencies**:
   Use Composer to install the required PHP packages:
   ```bash
   composer install
   ```

4. **Set Up the `.env` File**:
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Update the `.env` file with the following:
        - **Database Configuration**: Set up the database connection details.
        - **Email Sender Configuration**: Configure the email sender settings.
        - **Frontend URL**: Specify the frontend URL.
        - **Session Domain**:
            - Set to `null` if you are using Postman for testing.
            - Set to `localhost` if testing locally in a browser.
    - Generate the application key:
      ```bash
      php artisan key:generate
      ```
5. **Run Migrations**:
   Run the database migrations to set up the necessary tables:
   ```bash
   php artisan migrate
   ```

6. **Run the Server**:
   Start the Laravel development server:
   ```bash
   php artisan serve
   ```

---

## Authentication

- **Sanctum** is used for authentication with session cookies, not tokens.
- This approach was chosen for simplicity and is suitable for this project.

---

## Design Pattern

- The project follows the **HMVC (Hierarchical Model-View-Controller)** design pattern.
- The **Nwidart Laravel Modules** package is used to organize the application into modules for better maintainability and scalability.
- The **Auth Module** is reused from another project.

---

## Seeding and Scheduling

1. **Post Seeding**:
    - A custom seeding command is available to generate posts for a specific user:
      ```bash
      php artisan posts:make <user_id>
      ```
    - Replace `<user_id>` with the ID of the user who will own the posts.
    - Ensure you register a user first before running this command.

2. **Scheduled Posts**:
    - The scheduler command must be run to publish scheduled posts:
      ```bash
      php artisan schedule:work
      ```

---

## Resources and Request Forms

- Appropriate resources and request forms have been created to handle validation and formatting for the application.

