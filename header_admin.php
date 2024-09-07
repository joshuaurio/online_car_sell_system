<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="header_admin.css">
</head>
<header>

    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 -960 960 960" width="24"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>

            <li><a href="home.php">Dashboard</a></li>

            <li><a href="add_car.php">Add Car</a></li>

            <li><a href="modify_car.php">All Cars</a></li>

            <li><a href="notification.php">Notification</a></li>

            <li><a href="feedback.php">Feedback</a></li>

            <li><a href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 -960 960 960" width="24"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg></a></li>


        </ul>

    
        <ul>
            <li><a href="home.php">Jaki General Supply</a></li>

            <li class="hideOnMobile"><a href="add_car.php">Add Car</a></li>
            <li class="hideOnMobile"><a href="modify_car.php">All Cars</a></li>
            
            <li
            class="hideOnMobile"><a href="notification.php">Notification</a></li>

            <li
            class="hideOnMobile"><a href="feedback.php">Feedback</a></li>

            <li
            class="hideOnMobile"><a href="profile.php">Profile</a></li>

            <li class="hideOnMobile"><a href="logout.php"><img src="logout.png" alt="logout"></a></li>
            

            
            <li class="menu-button" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="26"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>
    <script>
        // to show the sidebr when clicked without going to css we use this code
        function showSidebar(){
            //to get the id of the original side bar that allows to show sidebar
            const sidebar = document.querySelector('.sidebar')
            //to change the form none to flex on css
            sidebar.style.display = 'flex'
        }
        function hideSidebar(){
             //to get the id of the original side bar that allows to show sidebar
             const sidebar = document.querySelector('.sidebar')
            //to change the form none to flex on css
            sidebar.style.display = 'none'
        }

    </script>
</header>

</html>
