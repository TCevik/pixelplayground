<!DOCTYPE php>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground - High Scores</title>
</head>
<body> 
    <?php include 'header.php'; ?>

    <main>
        <h1>High Scores</h1>
        <p>Beste highscores</p>
        <!-- High scores verschijnen hier -->
    </main>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #16213e;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 4rem 2rem;
        }

