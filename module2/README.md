# Module 2
File Sharing Site (40 Points):
- File Management (25 Points):
    - Users should not be able to see any files until they enter a username and log in (4 points)  
        *Remember that users.txt should be stored in a secure location on your filesystem. That is, you should not be able to type any URL into your browser and see the raw users.txt file!*
    - Users can see a list of all files they have uploaded (4 points)
    - Users can open files they have previously uploaded (5 points)  
        *Note: Users should be able to open not only plain text files but also other file formats: images, spreadsheets, etc.*
    - Users can upload files (4 points)
        *Note: Like users.txt, uploaded files should be stored in a secure location on your filesystem. That is, do not keep your uploads directory underneath a directory served by Apache!*
    - Users can delete files. If a file is "deleted", it should actually be removed from the filesystem (4 points)
    - The directory structure is hidden. Users should not be able to access or view files by manipulating a URL. (2 points)
    - Users can log out (2 points)
        *Note: If using session variables, you must actually log out the user by destroying their session; i.e., don't just redirect them to the login screen.*
- Best Practices (10 Points):
    - Code is well formatted and easy to read, with proper commenting (4 points)
    - The site follows the FIEO philosophy (3 points)
    - All pages pass the W3C validator (3 points)
- Usability (5 Points):
    - Site is intuitive to use and navigate (4 points)
    - Site is visually appealing (1 point)

Creative Portion (15 Points)  
Implemented a stock tracker/watchlist system using curl as part of the creative portion