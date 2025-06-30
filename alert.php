<?php

if (isset($_SESSION['message'])) {

    echo "<div id='flash-message' class='{$_SESSION['type_alert']} flash alert'>{$_SESSION['message']}</div>";

    unset($_SESSION['message']);
    unset($_SESSION['type_alert']);
}
?>

<script>
    window.onload = function() {
        const message = document.getElementById('flash-message');
        if (message) {
            setTimeout(() => {
                message.classList.add('fade-out');
                setTimeout(() => {
                    message.remove();
                }, 1000); 
            }, 2000); 
        }
    };
</script>
