## Blog and Post API

This project provides a RESTful API for managing blogs and their associated posts. It also supports user interactions such as liking and commenting on posts.

# Prerequisites
# Before running this project, ensure you have the following installed on your machine:

1. PHP >= 8.1
2. Composer
3. MySQL or another supported database
5. Laravel >= 10.x


## Follow these steps to set up and run the project:

1. Clone the Repository and navigate to project directory
2. run `composer install` to Install Dependencies
3. run `cp .env.example .env` Set Up Environment Variables
    Edit the .env file to configure your database and other environment-specific settings. For example:
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_username
        DB_PASSWORD=your_password

4. run `php artisan key:generate` to Generate Application Key

5. run `php artisan migrate` to Migrate the Database

6. run `php artisan db:seed` to Seed the Database

7. run `php artisan serve` to Serve the Application

The application will be accessible at http://127.0.0.1:8000 by default.

## API Documentation
# Blogs Endpoints
    View All Blogs: GET /api/blogs
    Create Blog: POST /api/blogs
    Show Blog: GET /api/blogs/{blogId}
    Update Blog: PUT /api/blogs/{blogId}
    Delete Blog: DELETE /api/blogs/{blogId}
# Posts Endpoints
    View All Posts: GET /api/blogs/{blogId}/posts
    Create Post: POST /api/blogs/{blogId}/posts
    Show Post: GET /api/blogs/{blogId}/posts/{postId}
    Update Post: PUT /api/blogs/{blogId}/posts/{postId}
    Delete Post: DELETE /api/blogs/{blogId}/posts/{postId}
# Interaction Endpoints
    Like Post: POST /api/posts/{postId}/like
    Comment on Post: POST /api/posts/{postId}/comments

# Authentication
The API uses Laravel Sanctum for authentication. Make sure to include the authentication token in your request headers as follows:
    Authorization: Bearer {token}
    Token: CourierPlus@321

