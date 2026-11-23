<?php
require_once "config.php";
require_once "functions.php";
require_login();
$studentId = (int)$_SESSION["student_id"];
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
    verify_csrf();
    $stmt = $pdo->prepare("DELETE FROM internships WHERE id=? AND student_id=?");
    $stmt->execute([(int)($_POST["id"] ?? 0),$studentId]);
    header("Location: internships.php?message=deleted");
    exit;
}
$filter = $_GET["status"] ?? "";
$sql = "SELECT * FROM internships WHERE student_id=?";
$params = [$studentId];
if ($filter === "interviews") {
    $sql .= " AND interview_date IS NOT NULL";
} elseif ($filter === "offers") {
    $sql .= " AND (offer_received_date IS NOT NULL OR status IN ('Offer Received','Accepted','Selected'))";
} elseif (in_array($filter, internship_statuses(), true)) {
    $sql .= " AND status=?";
    $params[] = $filter;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>My Internships | InternTrack</title><link rel="stylesheet" href="assets/style.css" /><link rel="stylesheet" href="assets/inner.css" /><script defer="defer" src="assets/app.js"></script></head>
<body><div class="layout"><?php include "sidebar.php"; ?><main class="main">
<header class="topbar"><div><p class="eyebrow">APPLICATION MANAGEMENT</p><h1>My applications</h1><p class="muted">Manage companies, interviews, deadlines and offers in one place.</p></div><a class="btn" href="add_internship.php">+ Add opportunity</a></header>
<?php $messages = ["added" => "Opportunity added successfully.", "updated" => "Opportunity updated successfully.", "deleted" => "Opportunity deleted successfully."]; if (isset($messages[$_GET["message"] ?? ""])): ?><div class="success"><?= e($messages[$_GET["message"]]) ?></div><?php endif; ?>
<section class="panel">
<div class="panel-heading applications-heading"><div><p class="eyebrow">YOUR PIPELINE</p><h2>All applications</h2><p class="muted" data-live-count><?= count($items) ?> <?= count($items) === 1 ? "application" : "applications" ?></p></div>
<div class="application-tools">
<label class="application-search"><span aria-hidden="true">⌕</span><input type="search" data-live-search placeholder="Search company or role" aria-label="Search applications" /></label>
<form method="get" class="filter-form"><label class="sr-only" for="status-filter">Filter applications</label><select id="status-filter" name="status" onchange="this.form.submit()"><option value="">All applications</option><option value="interviews" <?= $filter==="interviews"?"selected":"" ?>>Interview scheduled</option><option value="offers" <?= $filter==="offers"?"selected":"" ?>>Offers</option><?php foreach (internship_statuses() as $s): ?><option value="<?= e($s) ?>" <?= $filter===$s?"selected":"" ?>><?= e($s) ?></option><?php endforeach; ?></select></form></div></div>
<?php if (!$items): ?><div class="empty"><h3>No matching internships</h3><p>Add an internship or choose another filter.</p></div><?php else: ?>
<div class="table-wrap"><table class="application-table"><thead><tr><th>Company / Role</th><th>Status</th><th>Applied</th><th>Deadline</th><th>Interview</th><th>Offer details</th><th>Actions</th></tr></thead><tbody data-search-results>
<?php foreach ($items as $item): ?><tr data-searchable><td><div class="company-cell"><span class="company-avatar"><?= e(strtoupper(substr($item["company"],0,1))) ?></span><span><strong><?= e($item["company"]) ?></strong><small><?= e($item["role"]) ?><?= $item["location"] ? " · " . e($item["location"]) : "" ?></small></span></div></td><td><span class="status <?= e(status_class($item["status"])) ?>"><?= e($item["status"]) ?></span></td><td><?= e(display_date($item["applied_date"])) ?></td><td><?= e(display_date($item["deadline"])) ?></td><td><div class="interview-summary"><strong><?= e(display_date($item["interview_date"])) ?></strong><?php if ($item["interview_notes"]): ?><small><?= e($item["interview_notes"]) ?></small><?php endif; ?></div></td><td><div class="offer-summary"><?php if ($item["offer_received_date"] || $item["offer_deadline"] || $item["stipend"] || $item["internship_start_date"] || $item["offer_notes"]): ?><strong><?= $item["stipend"] ? e($item["stipend"]) : "Offer details" ?></strong><?php if ($item["offer_received_date"]): ?><small>Received <?= e(display_date($item["offer_received_date"])) ?></small><?php endif; ?><?php if ($item["offer_deadline"]): ?><small>Respond by <?= e(display_date($item["offer_deadline"])) ?></small><?php endif; ?><?php if ($item["internship_start_date"]): ?><small>Starts <?= e(display_date($item["internship_start_date"])) ?></small><?php endif; ?><?php if ($item["offer_notes"]): ?><small class="offer-notes"><?= e($item["offer_notes"]) ?></small><?php endif; ?><?php else: ?>—<?php endif; ?></div></td><td class="actions"><a href="edit_internship.php?id=<?= (int)$item["id"] ?>">Edit</a><form method="post" action="internships.php" class="inline-form" data-confirm="Delete this application record?"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" /><input type="hidden" name="action" value="delete" /><input type="hidden" name="id" value="<?= (int)$item["id"] ?>" /><button class="link-danger" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
<tr data-search-empty hidden><td colspan="7"><div class="search-empty"><span>⌕</span><strong>No applications match that search</strong><small>Try another company or role.</small></div></td></tr>
</tbody></table></div><?php endif; ?></section>
</main></div></body></html>
