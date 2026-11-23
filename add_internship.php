<?php
require_once "config.php";
require_once "functions.php";
require_login();
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
    if ($company === "" || $role === "") {
        $error = "Company name and role are required.";
    } elseif (!in_array($status, internship_statuses(), true)) {
        $error = "Choose a valid status.";
    } elseif (strlen($stipend) > 100) {
        $error = "Stipend details must be 100 characters or fewer.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO internships (student_id,company,role,location,type,applied_date,deadline,interview_date,interview_notes,offer_received_date,offer_deadline,stipend,internship_start_date,offer_notes,status,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$_SESSION["student_id"],$company,$role,$location,$type,$appliedDate,$deadline,$interviewDate,$interviewNotes,$offerReceivedDate,$offerDeadline,$stipend ?: null,$internshipStartDate,$offerNotes,$status,$notes]);
        header("Location: internships.php?message=added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Add Internship | InternTrack</title><link rel="stylesheet" href="assets/style.css" /><link rel="stylesheet" href="assets/inner.css" /><script defer="defer" src="assets/app.js"></script></head>
<body><div class="layout"><?php include "sidebar.php"; ?><main class="main">
<header class="topbar"><div><p class="eyebrow">APPLICATION MANAGEMENT</p><h1>Add opportunity</h1><p class="muted">Keep your company, interview and offer details together.</p></div><a class="btn btn-outline" href="internships.php">← Back to list</a></header>
<div class="form-layout"><section class="panel form-panel"><h2>Application details</h2><p class="muted">Capture the essentials and update them as you progress.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-grid" action="add_internship.php" data-internship-form>
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" />
<div><label>Company name *</label><input name="company" data-preview="company" required maxlength="150" placeholder="e.g. Infosys" /></div>
<div><label>Job role *</label><input name="role" data-preview="role" required maxlength="150" placeholder="e.g. Web Development Intern" /></div>
<div><label>Location</label><input name="location" data-preview="location" maxlength="150" placeholder="e.g. Bengaluru / Remote" /></div>
<div><label>Opportunity type</label><select name="type"><option>Internship</option><option>Summer Internship</option><option>Winter Internship</option><option>Remote Internship</option></select></div>
<div class="span-2 form-section-heading"><h3>Application timeline</h3><p>Record when you applied and any important deadlines.</p></div>
<div><label>Applied date</label><input type="date" name="applied_date" /></div>
<div><label>Application deadline</label><input type="date" name="deadline" /></div>
<div><label>Current status</label><select name="status" data-preview="status"><?php foreach (internship_statuses() as $s): ?><option><?= e($s) ?></option><?php endforeach; ?></select></div>
<div class="span-2 form-section-heading"><h3>Interview details</h3><p>Keep your next interview date and preparation notes handy.</p></div>
<div><label>Interview date</label><input type="date" name="interview_date" /></div>
<div class="span-2"><label>Interview notes</label><textarea name="interview_notes" rows="3" maxlength="2000" placeholder="Interview round, contact person, preparation notes..."></textarea></div>
<div class="span-2 form-section-heading"><h3>Offer details</h3><p>Track offer timing, compensation and your planned start date.</p></div>
<div><label>Offer received date</label><input type="date" name="offer_received_date" /></div>
<div><label>Offer response deadline</label><input type="date" name="offer_deadline" /></div>
<div><label>Stipend / compensation</label><input name="stipend" maxlength="100" placeholder="e.g. INR 15,000 / month" /></div>
<div><label>Internship start date</label><input type="date" name="internship_start_date" /></div>
<div class="span-2"><label>Offer notes</label><textarea name="offer_notes" rows="3" maxlength="2000" placeholder="Benefits, work arrangement, terms to confirm..."></textarea></div>
<div class="span-2 form-section-heading"><h3>Additional notes</h3><p>Save any other information about this opportunity.</p></div>
<div class="span-2"><label>Application notes</label><textarea name="notes" rows="3" maxlength="2000" placeholder="Referral, job link, follow-up reminders..."></textarea></div>
<div class="span-2 form-actions"><button class="btn" type="submit">Save internship</button><a class="btn btn-outline" href="internships.php">Cancel</a></div>
</form></section>
<aside class="form-preview-card" aria-live="polite"><p class="eyebrow">LIVE PREVIEW</p><h2>Your application card</h2><div class="preview-company"><span class="company-avatar" data-form-preview="initial">?</span><div><strong data-form-preview="company">Your company</strong><small data-form-preview="role">Role title</small></div></div><div class="form-preview-details"><span>LOCATION</span><strong data-form-preview="location">Location not added</strong><span>STATUS</span><span class="status applied" data-form-preview="status">Applied</span></div><p class="preview-tip"><span>✦</span> You can update these details anytime as your application progresses.</p></aside>
</div></main></div></body></html>
