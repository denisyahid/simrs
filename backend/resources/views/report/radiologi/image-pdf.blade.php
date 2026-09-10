<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image PDF</title>
    <style>
        body { 
            text-align: center; 
            margin: 5px; /* Add margins to avoid content overflow */
        }
        img { 
            max-width: 100%; /* Ensure it fits within the page */
            height: auto; /* Maintain aspect ratio */
            margin: 10px auto; /* Add spacing and center */
            display: block;
        }
    </style>
</head>
<body>
    <img src="{{ $imagePath }}" alt="Image">
</body>
</html>
