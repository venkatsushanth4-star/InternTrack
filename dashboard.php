<?php
require_once "config.php";
require_once "functions.php";
require_login();
$studentId = (int)$_SESSION["student_id"];
$stmt = $pdo->prepare("SELECT COUNT(*) total, SUM(status='Applied') applied, SUM(status='Interview') interviews, SUM(interview_date >= CURDATE() AND status NOT IN ('Rejected','Withdrawn','Completed')) upcoming_interviews, SUM(offer_received_date IS NOT NULL OR status IN ('Offer Received','Accepted','Selected')) offers, SUM(status IN ('Shortlisted','Interview','Offer Received','Accepted','Selected','In Progress')) moving FROM internships WHERE student_id=?");
$stmt->execute([$studentId]);
$stats = $stmt->fetch();
$total = (int)$stats["total"];
$applied = (int)$stats["applied"];
$interviews = (int)$stats["interviews"];
$upcomingInterviews = (int)$stats["upcoming_interviews"];
$offers = (int)$stats["offers"];
$moving = (int)$stats["moving"];
$pipelinePercent = $total ? (int)round($moving / $total * 100) : 0;
$stmt = $pdo->prepare("SELECT * FROM internships WHERE student_id=? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$studentId]);
$recent = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT id,company,role,interview_date,status FROM internships WHERE student_id=? AND interview_date >= CURDATE() AND status NOT IN ('Rejected','Withdrawn','Completed') ORDER BY interview_date ASC LIMIT 5");
$stmt->execute([$studentId]);
$upcoming = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Dashboard | InternTrack</title><link rel="stylesheet" href="assets/style.css" /><link rel="stylesheet" href="assets/inner.css" /><script defer="defer" src="assets/app.js"></script></head>
<body>
<div class="layout">
<?php include "sidebar.php"; ?>
<main class="main">
    <header class="topbar"><div><p class="eyebrow">STUDENT WORKSPACE</p><h1>Your journey</h1><p class="muted">Track applications, companies, interview dates, offers and internship status—all in one place.</p></div><a class="btn" href="add_internship.php">+ Add internship</a></header>
    <section class="welcome"><div><p class="eyebrow light">YOUR CAREER JOURNEY</p><h2>Hello, <?= e($_SESSION["student_name"]) ?> 👋</h2><p>Every application is a step closer to what’s next.</p></div>
        <?php if (!empty($_SESSION["profile_image"])): ?>
            <img class="welcome-profile" src="<?= e($_SESSION["profile_image"]) ?>" alt="Your profile picture" />
        <?php else: ?>
            <div class="welcome-mark">IT</div>
        <?php endif; ?>
    </section>
    <section class="stats-grid">
        <a class="stat-card stat-link stat-total" href="internships.php"><span class="stat-icon">▤</span><span class="stat-label">All applications</span><strong><?= $total ?></strong><span class="stat-note">Your full opportunity list <span aria-hidden="true">→</span></span></a>
        <a class="stat-card stat-link stat-applied" href="internships.php?status=Applied"><span class="stat-icon">↗</span><span class="stat-label">Applied</span><strong><?= $applied ?></strong><span class="stat-note">Waiting for a reply <span aria-hidden="true">→</span></span></a>
        <a class="stat-card stat-link stat-moving" href="internships.php?status=interviews"><span class="stat-icon">◷</span><span class="stat-label">Upcoming interviews</span><strong><?= $upcomingInterviews ?></strong><span class="stat-note">Scheduled next steps <span aria-hidden="true">→</span></span></a>
        <a class="stat-card stat-link stat-selected" href="internships.php?status=offers"><span class="stat-icon">★</span><span class="stat-label">Offers received</span><strong><?= $offers ?></strong><span class="stat-note">Review offer details <span aria-hidden="true">→</span></span></a>
    </section>
    <section class="panel journey-panel">
        <div class="panel-heading"><div><p class="eyebrow">YOUR APPLICATION PIPELINE</p><h2>Progress is a journey</h2><p class="muted">See where your opportunities are right now.</p></div><span class="pipeline-overview"><?= $pipelinePercent ?>% <small>moving forward</small></span></div>
        <div class="pipeline-track" aria-label="<?= $pipelinePercent ?> percent of applications have progressed beyond applied">
            <span class="pipeline-fill" style="width: <?= $pipelinePercent ?>%"></span>
        </div>
        <div class="pipeline-stages">
            <a href="internships.php?status=Applied"><span class="stage-dot stage-dot-applied"></span><span><strong>Applied</strong><small><?= $applied ?> <?= $applied === 1 ? "application" : "applications" ?></small></span></a>
            <a href="internships.php?status=Interview"><span class="stage-dot stage-dot-moving"></span><span><strong>Interview</strong><small><?= $interviews ?> <?= $interviews === 1 ? "interview" : "interviews" ?></small></span></a>
            <a href="internships.php?status=offers"><span class="stage-dot stage-dot-selected"></span><span><strong>Offers</strong><small><?= $offers ?> <?= $offers === 1 ? "offer" : "offers" ?></small></span></a>
        </div>
    </section>
    <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">NEXT STEPS</p><h2>Upcoming interviews</h2><p class="muted">Your scheduled conversations at a glance.</p></div><a href="internships.php?status=interviews">View all →</a></div>
        <?php if (!$upcoming): ?><div class="empty compact-empty"><h3>No upcoming interviews</h3><p>Add an interview date to an application and it will appear here.</p></div>
        <?php else: ?><div class="upcoming-list"><?php foreach ($upcoming as $item): ?><a href="edit_internship.php?id=<?= (int)$item["id"] ?>" class="upcoming-item"><span class="upcoming-date"><strong><?= e(date("j", strtotime($item["interview_date"]))) ?></strong><small><?= e(date("M", strtotime($item["interview_date"]))) ?></small></span><span class="upcoming-company"><strong><?= e($item["company"]) ?></strong><small><?= e($item["role"]) ?></small></span><span class="status <?= e(status_class($item["status"])) ?>"><?= e($item["status"]) ?></span></a><?php endforeach; ?></div><?php endif; ?>
    </section>
    <section class="panel">
        <div class="panel-heading"><div><h2>Recent applications</h2><p class="muted">Your latest application activity</p></div><a href="internships.php">View all →</a></div>
        <?php if (!$recent): ?><div class="empty"><h3>No internships added yet</h3><p>Add your first internship to start tracking your applications.</p><a class="btn" href="add_internship.php">Add internship</a></div>
        <?php else: ?>
        <div class="table-wrap"><table><thead><tr><th>Company / Role</th><th>Status</th><th>Applied</th><th>Interview</th><th>Offer</th></tr></thead><tbody>
        <?php foreach ($recent as $item): ?><tr><td><strong><?= e($item["company"]) ?></strong><small><?= e($item["role"]) ?></small></td><td><span class="status <?= e(status_class($item["status"])) ?>"><?= e($item["status"]) ?></span></td><td><?= e(display_date($item["applied_date"])) ?></td><td><?= e(display_date($item["interview_date"])) ?></td><td><?= $item["stipend"] ? e($item["stipend"]) : ($item["offer_received_date"] ? "Received " . e(display_date($item["offer_received_date"])) : "—") ?></td></tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</main></div>
</body></html>
