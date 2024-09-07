<style>
            /* Basic Reset */
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

 

        /* Header Styling */
        .header {
            background-color: #1e1e1e;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .header .containers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header .logo img {
            height: 40px;
        }

        .header nav {
            display: flex;
            gap: 20px;
        }

        .header nav a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-family:sans-serif;
            padding: 10px;
            transition: color 0.3s;
        }

        .header nav a:hover {
            color: yellow;
        }

        /* Responsive Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle div {
            width: 25px;
            height: 3px;
            background-color: #333;
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .header nav {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #1e1e1e;
                position: absolute;
                top: 60px;
                left: 0;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .header nav a {
                padding: 15px 20px;
                border-top: 1px solid #f0f0f0;
            }

            .menu-toggle {
                display: flex;
            }

            .header nav.active {
                display: flex;
            }
        }


</style>



</head>
<body>

<header class="header">
    <div class="containers">
        <div class="logo">
           <a href="feed.php" style="color:white;text-decoration:none;"> <h3>After Chef</h3></a>
        </div>
        <div class="menu-toggle" id="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <nav id="nav">
            <a href="feed.php">Home</a>
            <a href="search_peoples.php">Search</a>
            <a href="friend_request.php">Friend Requests</a>
            <a href="friends.php">Friends</a>
            <a href="sitemap.php">Sitemap</a>
            <a href="profile.php">My Profile</a>
            <a href="php/logout.php">Logout</a>
        </nav>
    </div>
</header>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('nav').classList.toggle('active');
    });
</script>
