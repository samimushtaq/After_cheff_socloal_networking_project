
<?php  include 'php/session_check.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search People</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/search_people.js"></script>
    <style>
        body {
    background-color: #1a1a1a;
    color: #f0db4f;
    font-family: 'Arial', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
}

#searchForm {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    margin-top: 50px;
}

#searchQuery {
    padding: 10px;
    border: 2px solid #f0db4f;
    border-radius: 5px 0 0 5px;
    width: 250px;
    outline: none;
    background-color: #333;
    color: #f0db4f;
    font-family: 'Arial', sans-serif;
}

#searchQuery::placeholder {
    color: #f0db4f;
}

button[type="submit"] {
    background-color: #f0db4f;
    color: #1a1a1a;
    border: none;
    padding: 10px 20px;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-family: 'Arial', sans-serif;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #e5c73f;
}

#searchResults {
    width: 100%;
    max-width: 600px;
    background-color: #333;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    margin-top: 20px;
    color: #f0db4f;
}

#searchResults p {
    margin: 0;
    padding: 10px 0;
    border-bottom: 1px solid #444;
}

#searchResults p:last-child {
    border-bottom: none;
}

img {
    width: 41px;
    height: 41px;
    margin: 5px;
    object-fit: cover; 
}
    </style>
</head>
<?php include('header.php');?>


<body>
    <form id="searchForm">
        <input type="text" id="searchQuery" placeholder="Search for people..." required>
        <button type="submit">Search</button>
    </form>
    <div id="searchResults"></div>
</body>
</html>
