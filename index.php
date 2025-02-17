<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Social Media</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .container {
            display: flex;
            max-width: 900px;
            margin: 20px auto;
        }
        .sidebar {
            width: 25%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        .feed {
            width: 50%;
            padding: 20px;
        }
        .profile {
            width: 25%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .post h3 {
            margin: 0 0 10px;
        }
        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .post-form {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .post-form textarea {
            width: 100%;
            height: 60px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .post-form button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">xd</a></li>
            </ul>
        </div>
        <div class="feed">
            <div class="post-form">
                <h3>Create a Post</h3>
                <textarea placeholder="What's on your mind?"></textarea>
                <button>Post</button>
            </div>
            <div class="post">
                <h3>John Doe</h3>
                <p>This is my first post!</p>
            </div>
            <div class="post">
                <h3>Jane Smith</h3>
                <p>Lovely weather today!</p>
            </div>
        </div>
        <div class="profile">
            <img src="profile-pic.jpg" alt="Profile Picture">
            <h3>John Doe</h3>
            <p>Web Developer | Blogger</p>
        </div>
    </div>
</body>
</html>
