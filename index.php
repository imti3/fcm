<?php
$resultMessage = '';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form submission logic
    $title = $_POST['title'];
    $message = $_POST['message'];
    $imageUrl = $_POST['imageUrl'];
    $apiKey = $_POST['apiKey'];
    $topicName = $_POST['topicName'];

    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    $notification = [
        'title' => $title,
        'body' => $message,
        'image' => $imageUrl
    ];

    $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

    $fcmNotification = [
        'to' => "/topics/" . $topicName,
        'notification' => $notification,
        'data' => $extraNotificationData
    ];

    $headers = [
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    $result = curl_exec($ch);
    curl_close($ch);

    $resultMessage = htmlspecialchars($result);
    //header("Location: index.php?notification=success");
    
    //exit();
    // Prevent form re-submission on page refresh
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send FCM Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result-container {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form action="index.php" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="message">Message:</label>
            <input type="text" id="message" name="message" required>
        </div>

        <div class="form-group">
            <label for="imageUrl">Image URL:</label>
            <input type="text" id="imageUrl" name="imageUrl">
        </div>

        <div class="form-group">
            <label for="apiKey">API Key:</label>
            <input type="text" id="apiKey" name="apiKey" required>
        </div>

        <div class="form-group">
            <label for="topicName">Topic Name:</label>
            <input type="text" id="topicName" name="topicName" required>
        </div>

        <input type="submit" value="Send Notification">
    </form>
</div>

<!-- Assuming $resultMessage is defined in your PHP script -->
<?php if (isset($resultMessage) && $resultMessage !== ''): ?>
    <div class="result-container">
        <p>Result:</p>
        <pre><?php echo $resultMessage; ?></pre>
    </div>
<?php endif; ?>

</body>
</html>
