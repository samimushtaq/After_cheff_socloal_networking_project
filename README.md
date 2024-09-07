After Chef
After Chef is a social networking platform designed for food enthusiasts to share, explore, and comment on recipes. Users can create profiles, post recipes, follow chefs, and engage with the community through likes and comments.

Features
Post Recipes: Users can upload recipes with detailed instructions and images.
Follow Chefs: Users can follow their favorite chefs and discover their latest creations.
Comments and Likes: Engage with the community by liking and commenting on recipes.
Recipe Categories: Filter recipes by cuisine, difficulty, and ingredients.
Responsive Design: Accessible on both desktop and mobile devices.
Technologies Used
HTML, CSS, JavaScript: Frontend design and layout.
AJAX: Dynamic updates without page reloads.
PHP: Server-side functionality.
MySQL: Database management.
Frameworks: Built using PHP and MySQL with a responsive interface.
Installation
Follow the steps below to set up the After Chef project locally:

Download or Clone the Repository

bash
Copy code
git clone https://github.com/your-username/After_cheff_socloal_networking_project

Set Up the Database

Go to the database folder.
Import the afterchef.sql file into your MySQL database using phpMyAdmin.
Open phpMyAdmin and create a new database.
Import the SQL file (afterchef.sql) to set up the database structure and initial data.
Configure Database Connection

Open the config.php file in the root folder.
Update the database connection parameters:
php

$host = 'localhost'; // Your database host
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$dbname = 'afterchef'; // The database name


Contributions
Feel free to contribute to the project by submitting a pull request or reporting issues.

License
This project is licensed under the MIT License.
