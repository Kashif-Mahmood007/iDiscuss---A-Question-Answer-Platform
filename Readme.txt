    Cloning the Repository

To clone the project, use the following command:
    git clone <repository_url>


Setting Up the Database:
    1. Create a MySQL database named idiscuss.
    2. Create the following tables with their respective columns:


    Table: categories
        category_id (Primary Key, INT, Auto Increment)
        category_name (VARCHAR(50))
        category_description (TEXT)
        datetime (DATETIME, default: CURRENT_TIMESTAMP)


    Table: comments
        comment_id (Primary Key, INT, Auto Increment)
        comment_content (TEXT)
        thread_id (INT)
        user_id (INT)
        datetime (DATETIME, default: CURRENT_TIMESTAMP)    


    Table: signup
        sno (Primary Key, INT, Auto Increment)
        name (VARCHAR(50))
        password (VARCHAR(255))
        datetime (DATETIME, default: CURRENT_TIMESTAMP)


    Table: threads
        thread_id (Primary Key, INT, Auto Increment)
        thread_title (VARCHAR(255))
        thread_desc (TEXT)
        thread_cat_id (INT)
        thread_user_id (INT)
        datetime (DATETIME, default: CURRENT_TIMESTAMP)    

