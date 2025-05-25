# Content Scheduler Frontend

This is the frontend for the Content Scheduler application, built with Angular. It provides a user-friendly interface for scheduling posts across multiple social platforms, managing posts, viewing analytics, and more.

---

## Table of Contents

- [Content Scheduler Frontend](#content-scheduler-frontend)
  - [Table of Contents](#table-of-contents)
  - [Project Overview](#project-overview)
  - [Key Features](#key-features)
  - [Tech Stack](#tech-stack)
  - [Installation](#installation)
  - [Running the App](#running-the-app)
  - [API Integration](#api-integration)
  - [Folder Structure](#folder-structure)
  - [Approach \& Trade-offs](#approach--trade-offs)
  - [Contact](#contact)

---

## Project Overview

The Content Scheduler lets users create, schedule, and manage posts for multiple social media platforms. It integrates with a Laravel backend and demonstrates best practices in frontend architecture, state management, and UI/UX.

---

## Key Features

- **Authentication**: User login, registration, and profile management (via backend API).
- **Post Editor**: Create/edit posts with title, content, image upload, platform selection, date/time picker, and character counter.
- **Dashboard**: Calendar and list views of scheduled posts with status indicators and filters.
- **Platform Management**: Enable/disable platforms for posting.
- **Analytics**: Visualize posts per platform, publishing success rates, and scheduled vs. published counts.
- **Rate Limiting**: UI feedback for daily post limits.
- **Activity Logging**: View user actions in a dedicated log panel.
- **Responsive UI**: Works on desktop and mobile.
- **Error Handling & Validation**: Friendly error messages and form validation.

---

## Tech Stack

- **Framework**: Angular (latest stable)
- **State Management**: RxJS, Angular Services
- **UI Library**: Angular Material (or specify if different)
- **HTTP Client**: Angular HttpClient
- **Other Tools**: Day.js (or similar for date handling), ngx-toastr (for notifications), etc.

---

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yousefhussen/Content-Scheduler
   cd Content-Scheduler/Front/content-scheduler
   ```

2. **Install Dependencies**

   ```bash
   npm install
   ```

3. **Environment Configuration**
   - Copy `src/environments/environment.example.ts` to `src/environments/environment.ts`.
   - Set the `apiUrl` to point to your backend (e.g., `http://localhost:8000/`).

---

## Running the App

Start the development server:

```bash
ng serve
```

The app will be available at [http://localhost:4200](http://localhost:4200).

---

## API Integration

- The frontend communicates with the Laravel backend via RESTful API endpoints.
- Ensure the backend is running and accessible.
- Authentication is handled using Laravel Sanctum with session cookies.
- CORS and CSRF settings must be configured on the backend for local development.

---

## Folder Structure

```
src/
  app/
    pages/
      dashboard/         # Dashboard and calendar/list views
      post-editor/       # Post creation/editing
      settings/          # Platform management
      logs/              # Activity log panel
    shared/
      services/          # API and utility services
      models/            # TypeScript interfaces/models
      components/        # Shared/reusable components
  assets/
  environments/
```

---

## Approach & Trade-offs

- **Component-based Structure**: Pages and features are modular for maintainability.
- **State Management**: Used Angular services and RxJS for simplicity and performance.
- **Form Validation**: Both template and reactive forms for robust validation.
- **Performance**: Lazy loading for major modules, optimized API calls.
- **Security**: Handles authentication securely with session cookies.
- **Trade-offs**:
  - Chose Angular Material for rapid UI development and consistency.
  - Focused on clean code and SOLID principles over advanced state libraries (e.g., NgRx) for this project’s scale.
  - Mocked some backend behaviors for demo purposes (see code comments).

---

## Contact

For questions or contributions, please contact [yousefhussen139@gmail.com].
