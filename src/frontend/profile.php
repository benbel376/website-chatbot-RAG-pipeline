<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug output
error_log("Loading profile.php");

// Load required loaders
require_once 'loaders/SummaryLoader.php';
require_once 'loaders/ExperienceLoader.php';
require_once 'loaders/EducationLoader.php';
require_once 'loaders/SkillsLoader.php';
require_once 'loaders/CertificationsLoader.php';
require_once 'loaders/TestimonialsLoader.php';
require_once 'loaders/HeroLoader.php';

// Initialize loaders
$summaryLoader = new SummaryLoader();
$experienceLoader = new ExperienceLoader();
$educationLoader = new EducationLoader();
$skillsLoader = new SkillsLoader();
$certificationsLoader = new CertificationsLoader();
$testimonialsLoader = new TestimonialsLoader();
$heroLoader = new HeroLoader();

// Debug output
error_log("Loaders instantiated");

// Capture the outputs
$summaryContent = $summaryLoader->render();
$experienceContent = $experienceLoader->render();
$educationContent = $educationLoader->render();

// Debug output
error_log("Content loaded - Summary length: " . strlen($summaryContent) . ", Experience length: " . strlen($experienceContent) . ", Education length: " . strlen($educationContent));
?>

<!--
        - #ABOUT
      -->

<article class="about  active" data-page="profile">

  <header class="profile-header">
    <h2 class="h2 article-title">Profile</h2>
    <a href="./assets/data/resume.pdf" download="resume.pdf" class="resume-button">Download Resume</a>
  </header>

  <!-- Hero Section -->
  <?php
  echo $heroLoader->render();
  ?>

  <!-- Summary Section -->
  <?php
  if (empty($summaryContent)) {
      error_log("Summary content is empty");
      echo "<!-- Summary content could not be loaded -->";
  } else {
      echo $summaryContent;
  }
  ?>

  <!-- Experience Section -->
  <?php
  if (empty($experienceContent)) {
      error_log("Experience content is empty");
      echo "<!-- Experience content could not be loaded -->";
  } else {
      echo $experienceContent;
  }
  ?>

  <!-- Education Section -->
  <?php
  echo $educationContent;
  ?>

  <!-- Skills Section -->
  <?php
  echo $skillsLoader->render();
  ?>

  <!-- Certifications Section -->
  <?php
  echo $certificationsLoader->render();
  ?>

  <!-- Testimonials Section -->
  <?php
  echo $testimonialsLoader->render();
  ?>

</article>