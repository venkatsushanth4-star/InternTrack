<?php
require_once "functions.php";
if (!empty($_SESSION["student_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Keep every internship application organized and make your next career move with InternTrack." />
    <title>InternTrack | Your next opportunity, in view</title>
    <link rel="stylesheet" href="assets/style.css" />
    <link rel="stylesheet" href="assets/landing.css" />
    <script defer="defer" src="assets/app.js"></script>
</head>
<body class="landing-page">
    <header class="landing-header">
        <a class="landing-brand" href="index.php" aria-label="InternTrack home"><span class="landing-brand-icon">I</span> InternTrack</a>
        <button class="landing-menu-toggle" type="button" aria-expanded="false" aria-controls="landing-nav" aria-label="Open navigation"><span></span><span></span></button>
        <nav class="landing-nav" id="landing-nav">
            <a href="#features">Features</a>
            <a href="#how-it-works">How it works</a>
            <a class="landing-login" href="login.php">Sign in</a>
            <a class="landing-cta" href="register.php">Get started <span aria-hidden="true">↗</span></a>
        </nav>
    </header>

    <main>
        <section class="landing-hero">
            <div class="hero-copy">
                <div class="hero-kicker"><span class="live-dot"></span> YOUR CAREER, WITH A PLAN</div>
                <h1>Big goals.<br /><span>Clear next steps.</span></h1>
                <p class="hero-description">The search for an internship can get messy. Keep every application, deadline and little win together in one calm, clear space.</p>
                <div class="hero-actions">
                    <a class="landing-cta hero-primary" href="register.php">Build your tracker <span aria-hidden="true">↗</span></a>
                    <a class="hero-secondary" href="#how-it-works"><span class="play-icon" aria-hidden="true">▶</span> See how it works</a>
                </div>
                <div class="hero-proof"><div class="proof-avatars" aria-hidden="true"><span>J</span><span>M</span><span>A</span></div><p><strong>Your next chapter starts here.</strong><br />One application at a time.</p></div>
            </div>

            <div class="hero-visual" aria-label="Interactive preview of the internship tracker">
                <div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div>
                <div class="floating-note note-top"><span class="note-icon note-spark">✦</span><span><strong>Small steps add up</strong><small>Keep your momentum</small></span></div>
                <div class="tracker-preview">
                    <div class="preview-top"><div><span class="preview-eyebrow">YOUR APPLICATIONS</span><h2>A little progress, every day.</h2></div><span class="preview-menu" aria-hidden="true">•••</span></div>
                    <div class="preview-summary"><div class="preview-ring"><span>04</span></div><div><strong>Keep your momentum</strong><small>Every opportunity has a next step.</small></div></div>
                    <div class="preview-divider"></div>
                    <div class="preview-list-head"><strong>Application snapshot</strong><span>Tap a stage</span></div>
                    <div class="preview-tabs" role="tablist" aria-label="Application stages">
                        <button class="preview-tab is-active" type="button" role="tab" aria-selected="true" data-stage="applied">Applied</button>
                        <button class="preview-tab" type="button" role="tab" aria-selected="false" data-stage="interview">Interview</button>
                        <button class="preview-tab" type="button" role="tab" aria-selected="false" data-stage="offer">Offer</button>
                    </div>
                    <article class="preview-application" aria-live="polite">
                        <div class="company-mark" data-preview-mark>n</div>
                        <div class="application-info"><strong data-preview-company>Northstar Labs</strong><small data-preview-role>Product Design Intern</small></div>
                        <span class="preview-status status-applied" data-preview-status>Applied</span>
                    </article>
                    <div class="preview-progress"><span data-preview-progress style="width: 35%"></span></div>
                    <div class="preview-footer"><span data-preview-next>Next up: follow up next week</span><span data-preview-count>1 of 4</span></div>
                </div>
                <div class="floating-note note-bottom"><span class="note-icon note-check">✓</span><span><strong>Deadline in view</strong><small>Nothing slips through</small></span></div>
            </div>
            <a class="scroll-hint" href="#features"><span></span> SCROLL TO EXPLORE</a>
        </section>

        <section class="landing-features" id="features">
            <div class="section-heading"><span class="section-kicker">LESS SPREADSHEET, MORE YOU</span><h2>Make room for the exciting part.</h2><p>Spend less energy keeping track, and more energy moving forward.</p></div>
            <div class="feature-grid">
                <article class="feature-card"><span class="feature-icon icon-purple">↗</span><span class="feature-number">01 / ORGANIZE</span><h3>One home for every opportunity</h3><p>Save company details, role, location and notes in a tidy application list that's easy to scan.</p><span class="feature-decoration deco-stack"></span></article>
                <article class="feature-card"><span class="feature-icon icon-peach">◷</span><span class="feature-number">02 / STAY ON TRACK</span><h3>Keep the next step in sight</h3><p>Track application stages and important dates, so you know what needs your attention next.</p><span class="feature-decoration deco-rings"></span></article>
                <article class="feature-card"><span class="feature-icon icon-mint">✳</span><span class="feature-number">03 / NOTICE PROGRESS</span><h3>Celebrate every little win</h3><p>See your applications at a glance and appreciate the progress you're making along the way.</p><span class="feature-decoration deco-spark"></span></article>
            </div>
        </section>

        <section class="landing-how" id="how-it-works">
            <div class="how-copy"><span class="section-kicker">A SIMPLE PLACE TO START</span><h2>Your internship search,<br />a little less overwhelming.</h2><p>Whether you're sending your first application or juggling a few interviews, InternTrack helps you stay organized without getting in your way.</p><a class="text-link" href="register.php">Start your tracker <span aria-hidden="true">→</span></a></div>
            <div class="how-steps">
                <article class="how-step"><span>01</span><div><h3>Add an opportunity</h3><p>Save the roles you're excited about as they come up.</p></div><span class="step-symbol">＋</span></article>
                <article class="how-step"><span>02</span><div><h3>Keep it up to date</h3><p>Update status and notes whenever something changes.</p></div><span class="step-symbol">↗</span></article>
                <article class="how-step"><span>03</span><div><h3>Focus on what's next</h3><p>See your progress and keep your next move in view.</p></div><span class="step-symbol">✦</span></article>
            </div>
        </section>
        <section class="landing-final-cta"><div class="cta-orb"></div><span class="section-kicker">YOUR NEXT CHAPTER IS YOURS</span><h2>Ready to make your move?</h2><p>Start a clearer, calmer internship search today.</p><a class="landing-cta hero-primary" href="register.php">Create your free account <span aria-hidden="true">↗</span></a><span class="cta-scribble" aria-hidden="true">you've got this!</span></section>
    </main>
    <footer class="landing-footer"><a class="landing-brand" href="index.php"><span class="landing-brand-icon">I</span> InternTrack</a><p>Made for the big things you're working toward.</p><a href="login.php">Already have an account? Sign in</a></footer>
</body>
</html>
