<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <script>
        localStorage.removeItem('access_token');
        localStorage.removeItem('user_data');
        window.location.href = 'index.php';
    </script>
</head>
<body>
    <p>Logging out...</p>
</body>
</html>
