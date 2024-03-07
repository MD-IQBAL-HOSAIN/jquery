<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX MySQL Example</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<div id="result">
    <!-- The data will be loaded here -->
</div>

<button id="loadData">Load Data</button>

<script>
    $(document).ready(function () {
        $("#loadData").click(function () {
            // Make an AJAX request to the PHP script
            $.ajax({
                url: "getdata.php",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    // Update the content in the result div
                    if (data.length > 0) {
                        var html = "<ul>";
                        $.each(data, function (index, item) {
                            html += "<li>ID: " + item.id + ", Name: " + item.name + ", Age: " + item.age + "</li>";
                        });
                        html += "</ul>";
                        $("#result").html(html);
                    } else {
                        $("#result").html("No data found");
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error("Error: " + status + " - " + error);
                }
            });
        });
    });
</script>

</body>
</html>
