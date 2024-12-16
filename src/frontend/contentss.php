
<?php
// Content Section
$page = $_GET['page'] ?? 'about';
switch($page) {
    case 'about':
        include('about.php');
        break;
    case 'resume':
        include('resume.php');
        break;
    case 'portfolio':
        include('portfolio.php');
        break;
    case 'blog':
        include('blog.php');
        break;
    case 'contact':
        include('contact.php');
        break;
    case 'ask':
        include('ask.php');
        break;
    default:
        include('about.php');
        break;
}
?>
