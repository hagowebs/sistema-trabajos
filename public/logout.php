<?php // public/logout

session_destroy();
header("Location: login");
exit();