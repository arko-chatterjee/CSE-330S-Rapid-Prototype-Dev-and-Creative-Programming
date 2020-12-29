# Module 3
News Site (60 Points):
- User Management (20 Points):
    - A session is created when a user logs in (3 points)
    - New users can register (3 points)
    - Passwords are hashed, salted, and checked securely (3 points)  
    *Note: You will receive 0 points for this section if you use the == or === operators to compare password hashes, or if you use the crypt or md5 functions at any point.*
    - Users can log out (3 points)
    - A user can edit and delete his/her own stories and comments but cannot edit or delete the stories or comments of another user (8 points)
- Story and Comment Management (20 Points):
    - Relational database is configured with correct data types and foreign keys (4 points)
    *Note: To demonstrate the structure of your database, you should commit a 'single' text file containing the output of the SHOW CREATE TABLE command for all tables in your database, called tables.sql. This one file should contain the output for all of your tables.*
    - Stories can be posted (3 points)
    - A link can be associated with each story, and is stored in a separate database field from the story (3 points)
    - Comments can be posted in association with a story (4 points)
    - Stories can be edited and deleted (3 points)
    - Comments can be edited and deleted (3 points)
    *Note: Although there are only 6 points allocated for editing/deleting in this section, there are 8 more points at stake in the User Management section that cannot be earned unless editing/deleting is implemented. Implementing editing but not deleting, or vice-versa, will result in earning half the points.*
- Best Practices (15 Points):
    - Code is well formatted and easy to read, with proper commenting (3 points)
    - Safe from SQL Injection attacks (2 points)
    - Site follows the FIEO philosophy (3 points)
    - All pages pass the W3C HTML and CSS validators (2 points)
    - CSRF tokens are passed when creating, editing, and deleting comments and stories (5 points)
- Usability (5 Points):
    - Site is intuitive to use and navigate (4 points)
    - Site is visually appealing (1 point)

Creative Portion (15 Points)
- Added the ability to sort stories by different factors like name and author
- Added timestamps for when stories and comments are created
- Added a profile view to see own stories and comments
- Able to post anonymously