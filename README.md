# Laravel 9 To-Do List
This project is a simple To-Do List application built with Laravel 9. It allows users to manage tasks with functionalities such as adding tasks dynamically, marking tasks as completed, displaying all tasks (both completed and non-completed), deleting tasks with a confirmation alert, and preventing duplicate tasks.


## Features
Add Tasks Dynamically: Tasks can be added without page reload using AJAX.

Mark Tasks as Completed: Click on complete icon to mark status as completed.

Show All Tasks: Clicking the "Show All Tasks" button displays both completed and non-completed tasks.

Delete Tasks with Confirmation: Deleting tasks prompts a confirmation dialog ("Are you sure to delete this task?") using SweetAlert.

Prevent Duplicate Tasks: Duplicate tasks are prevented from being added to the list.

## Installation

Clone the repository:
git clone https://github.com/sakshamgoyal100/Todos-laravel-9.git

Navigate into the project directory:
cd Todos-laravel-9

Install dependencies:
composer install

Set up your environment variables:

Duplicate .env.example and rename it to .env.
Configure your database settings in .env.

Run database migrations:
php artisan migrate

Run Application
php artisan serve

Access the application in your browser at http://localhost:8000.

## Technologies Used
Laravel 9: PHP framework for building web applications.
jQuery: JavaScript library for DOM manipulation and AJAX.
Toastr: JavaScript library for non-blocking notifications.
SweetAlert: JavaScript library for beautiful alert dialogs.

## Contributing
Contributions are welcome! Fork the repository, make your changes, and submit a pull request.