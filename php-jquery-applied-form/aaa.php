
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $time = $_POST['time'];
    $date = $_POST['date'];

    // Process the data (e.g., insert into a database)
    // You can use $time and $date in your MySQL query

    // Respond with success message (optional)
    echo 'Data received successfully!';
} else {
    // Handle other HTTP methods (GET, etc.) if needed
    echo 'Invalid request method.';
}
?>




<html>
<head>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
        $(function () {
            $('form').on('submit', function (e) {
                e.preventDefault(); // Prevent the default form submission
                $.ajax({
                    type: 'post',
                    url: 'post.php', // Specify the PHP file to handle form data
                    data: $('form').serialize(), // Serialize form data
                    success: function () {
                        alert('Form was submitted successfully');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <form>
        <input name="time" value="00:00:00.00"><br>
        <input name="date" value="0000-00-00"><br>
        <input name="submit" type="submit" value="Submit">
    </form>
</body>
</html>
