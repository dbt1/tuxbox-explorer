<?php
/**
 * privacy.php
 *
 * Privacy Policy page for My File Explorer.
 * This page displays the privacy policy in a style consistent with the main application.
 */

require_once __DIR__ . '/config/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Privacy Policy - My File Explorer</title>
  
  <!-- Local CSS links similar to main design -->
  <link rel="stylesheet" href="default-style.css" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/all.min.css" />

  <style>
    body {
      background-color: #2b2e38;
      color: #dce1e7;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #242731;
      color: #fff;
      padding: 20px;
      text-align: center;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    .content-wrapper {
      padding: 20px;
      max-width: 800px;
      margin: 120px auto 80px auto; /* Abstand oben für fixierten Header und unten für Footer */
      background-color: #2f3240;
      border: 1px solid #3e414d;
      border-radius: 4px;
    }
    h1, h2, h3 {
      color: #fff;
    }
    p {
      line-height: 1.6;
    }
    ul {
      color: #dce1e7;
    }
    footer {
      background-color: #242731;
      color: #999;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 0.9rem;
    }
    footer a {
      color: #53a7fd;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
      color: #53fd7c;
    }
  </style>
</head>
<body>

  <header>
    <h1>Privacy Policy</h1>
  </header>

  <div class="container content-wrapper">
    <h2>General Information</h2>
    <p>
      Protecting your personal data is our priority. This Privacy Policy explains what types of personal data our File Explorer collects, how it is used, and how we protect it.
    </p>

    <h2>1. Data Controller</h2>
    <p>
      The data controller responsible for this website is:<br>
      <?php echo htmlspecialchars($DATA_CONTROLLER_NAME); ?><br>
      <?php echo nl2br(htmlspecialchars($DATA_CONTROLLER_ADDRESS)); ?><br>
      Email: <a href="mailto:<?php echo htmlspecialchars($DATA_CONTROLLER_EMAIL); ?>">
      <?php echo htmlspecialchars($DATA_CONTROLLER_EMAIL_ALIAS ?? $DATA_CONTROLLER_EMAIL); ?></a>
    </p>

    <h2>2. Collection and Storage of Personal Data</h2>
    <p>
      Our File Explorer does not collect or store any personal data from users. Only technical data for debugging and service improvement purposes may be processed.
    </p>

    <h2>3. Server Log Files</h2>
    <p>
      When accessing our website, certain information is automatically stored in server log files, including:
      <ul>
        <li>IP address</li>
        <li>Date and time of access</li>
        <li>HTTP headers</li>
        <li>Browser type and version</li>
      </ul>
      These data are used solely for technical optimization and security purposes and are not linked with personal data.
    </p>

    <h2>4. Security</h2>
    <p>
      We implement technical and organizational measures to protect your data against unauthorized access, loss, destruction, or alteration.
    </p>

    <h2>5. Rights of Data Subjects</h2>
    <p>
      You have the right to access, rectify, delete, restrict processing, and transfer your personal data. Please contact us using the above contact details to exercise your rights.
    </p>

    <h2>6. Changes to this Privacy Policy</h2>
    <p>
      We reserve the right to modify this Privacy Policy as needed to comply with legal requirements or changes to our services. Please review this policy periodically.
    </p>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> <?php echo htmlspecialchars($DATA_CONTROLLER_NAME ?? 'Your Company'); ?>. All rights reserved. 
    &bull; <a href="index.php">Home</a>
  </footer>

</body>
</html>
