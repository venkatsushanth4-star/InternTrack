<?php
require_once "config.php";
require_once "functions.php";
require_login();
$id = (int)($_GET["id"] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM internships WHERE id=? AND student_id=?");
$stmt->execute([$id,$_SESSION["student_id"]]);
$item = $stmt->fetch();
if (!$item) { http_response_code(404); exit("Internship record not found."); }
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $company = trim($_POST["company"] ?? "");
    $role = trim($_POST["role"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $type = $_POST["type"] ?? "Internship";
    $appliedDate = $_POST["applied_date"] ?: null;
    $deadline = $_POST["deadline"] ?: null;
    $interviewDate = $_POST["interview_date"] ?: null;
    $interviewNotes = trim($_POST["interview_notes"] ?? "");
    $offerReceivedDate = $_POST["offer_received_date"] ?: null;
    $offerDeadline = $_POST["offer_deadline"] ?: null;
    $stipend = trim($_POST["stipend"] ?? "");
    $internshipStartDate = $_POST["internship_start_date"] ?: null;
    $offerNotes = trim($_POST["offer_notes"] ?? "");
    $status = $_POST["status"] ?? "Applied";
    $notes = trim($_POST["notes"] ?? "");
    if ($company === "" || $role === "" || !in_array($status, internship_statuses(), true)) {
        $error = "Please enter a company, role and valid status.";
    } elseif (strlen($stipend) > 100) {
        $error = "Stipend details must be 100 characters or fewer.";
    } else {
        $stmt = $pdo->prepare("UPDATE internships SET company=?,role=?,location=?,type=?,applied_date=?,deadline=?,interview_date=?,interview_notes=?,offer_received_date=?,offer_deadline=?,stipend=?,internship_start_date=?,offer_notes=?,status=?,notes=? WHERE id=? AND student_id=?");
        $stmt->execute([$company,$role,$location,$type,$appliedDate,$deadline,$interviewDate,$interviewNotes,$offerReceivedDate,$offerDeadline,$stipend ?: null,$internshipStartDate,$offerNotes,$status,$notes,$id,$_SESSION["student_id"]]);
        header("Location: internships.php?message=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Edit Internship | InternTrack</title><link rel="stylesheet" href="assets/style.css" /><link rel="stylesheet" href="assets/inner.css" /><script defer="defer" src="assets/app.js"></script></head>
<body><div class="layout"><?php include "sidebar.php"; ?><main class="main">
<header class="topbar"><div><p class="eyebrow">APPLICATION MANAGEMENT</p><h1>Edit opportunity</h1><p class="muted">Update your application, interview and offer details.</p></div><a class="btn btn-outline" href="internships.php">← Back</a></header>
<div class="form-layout"><section class="panel form-panel"><h2>Application details</h2><p class="muted">Keep your opportunity details current.</p><?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-grid" action="edit_internship.php?id=<?= $id ?>" data-internship-form><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" />
<div><label>Company name *</label><input name="company" data-preview="company" required value="<?= e($item["company"]) ?>" /></div><div><label>Job role *</label><input name="role" data-preview="role" required value="<?= e($item["role"]) ?>" /></div>
<div><label>Location</label><input name="location" data-preview="location" value="<?= e($item["location"]) ?>" /></div><div><label>Opportunity type</label><select name="type"><?php foreach (["Internship","Summer Internship","Winter Internship","Remote Internship"] as $t): ?><option <?= $item["type"]===$t?"selected":"" ?>><?= e($t) ?></option><?php endforeach; ?></select></div>
<div class="span-2 form-section-heading"><h3>Application timeline</h3><p>Record when you applied and any important deadlines.</p></div>
<div><label>Applied date</label><input type="date" name="applied_date" value="<?= e($item["applied_date"]) ?>" /></div><div><label>Application deadline</label><input type="date" name="deadline" value="<?= e($item["deadline"]) ?>" /></div>
<div><label>Current status</label><select name="status" data-preview="status"><?php foreach (internship_statuses() as $s): ?><option <?= $item["status"]===$s?"selected":"" ?>><?= e($s) ?></option><?php endforeach; ?></select></div>
<div class="span-2 form-section-heading"><h3>Interview details</h3><p>Keep your next interview date and preparation notes handy.</p></div>
<div><label>Interview date</label><input type="date" name="interview_date" value="<?= e($item["interview_date"]) ?>" /></div>
<div class="span-2"><label>Interview notes</label><textarea name="interview_notes" rows="3" maxlength="2000"><?= e($item["interview_notes"]) ?></textarea></div>
<div class="span-2 form-section-heading"><h3>Offer details</h3><p>Track offer timing, compensation and your planned start date.</p></div>
<div><label>Offer received date</label><input type="date" name="offer_received_date" value="<?= e($item["offer_received_date"]) ?>" /></div>
<div><label>Offer response deadline</label><input type="date" name="offer_deadline" value="<?= e($item["offer_deadline"]) ?>" /></div>
<div><label>Stipend / compensation</label><input name="stipend" maxlength="100" value="<?= e($item["stipend"]) ?>" placeholder="e.g. INR 15,000 / month" /></div>
<div><label>Internship start date</label><input type="date" name="internship_start_date" value="<?= e($item["internship_start_date"]) ?>" /></div>
<div class="span-2"><label>Offer notes</label><textarea name="offer_notes" rows="3" maxlength="2000"><?= e($item["offer_notes"]) ?></textarea></div>
<div class="span-2 form-section-heading"><h3>Additional notes</h3><p>Save any other information about this opportunity.</p></div>
<div class="span-2"><label>Application notes</label><textarea name="notes" rows="3" maxlength="2000"><?= e($item["notes"]) ?></textarea></div>
<div class="span-2 form-actions"><button class="btn" type="submit">Save changes</button><a class="btn btn-outline" href="internships.php">Cancel</a></div>
</form></section>
<aside class="form-preview-card" aria-live="polite"><p class="eyebrow">LIVE PREVIEW</p><h2>Your application card</h2><div class="preview-company"><span class="company-avatar" data-form-preview="initial"><?= e(strtoupper(substr($item["company"],0,1))) ?></span><div><strong data-form-preview="company"><?= e($item["company"]) ?></strong><small data-form-preview="role"><?= e($item["role"]) ?></small></div></div><div class="form-preview-details"><span>LOCATION</span><strong data-form-preview="location"><?= e($item["location"] ?: "Location not added") ?></strong><span>STATUS</span><span class="status <?= e(status_class($item["status"])) ?>" data-form-preview="status"><?= e($item["status"]) ?></span></div><p class="preview-tip"><span>✦</span> Your changes are reflected here as you edit.</p></aside>
</div></main></div></body></html>
