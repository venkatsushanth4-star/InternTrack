<aside class="sidebar">
    <a class="brand" href="dashboard.php"><span class="brand-icon">I</span> InternTrack</a>
    <p class="nav-label">MENU</p>
    <nav>
        <a class="<?= basename($_SERVER["PHP_SELF"]) === "dashboard.php" ? "active" : "" ?>" href="dashboard.php">▦ &nbsp; Dashboard</a>
        <a class="<?= in_array(basename($_SERVER["PHP_SELF"]), ["internships.php","add_internship.php","edit_internship.php"]) ? "active" : "" ?>" href="internships.php">▤ &nbsp; My internships</a>
        <a href="add_internship.php">＋ &nbsp; Add internship</a>
    </nav>
    <div class="sidebar-bottom"><div class="user-mini">
        <?php if (!empty($_SESSION["profile_image"])): ?>
            <img class="avatar avatar-image" src="<?= e($_SESSION["profile_image"]) ?>" alt="Profile picture" />
        <?php else: ?>
            <span class="avatar"><?= e(strtoupper(substr($_SESSION["student_name"] ?? "S",0,1))) ?></span>
        <?php endif; ?>
        <div><strong><?= e($_SESSION["student_name"] ?? "Student") ?></strong><small>Student account</small></div>
    </div><a class="logout" href="logout.php">↪ &nbsp; Logout</a></div>
</aside>
