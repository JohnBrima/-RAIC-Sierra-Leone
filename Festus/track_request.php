<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

$tracking = sanitize($_GET['tracking'] ?? '');
$request = null;
if ($tracking !== '') {
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM information_requests WHERE tracking_number = ?');
    $stmt->execute([$tracking]);
    $request = $stmt->fetch();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Request | RAIC Open Data Platform</title>
    <meta name="description" content="Track the status of an information request.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="assets/ChatGPT%20Image%20Jun%2013%2C%202026%2C%2010_24_34%20PM.png">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body>
    <header class="header-top">
      <div class="container-lg d-flex flex-wrap align-items-center justify-content-between gap-3"><div class="navbar-brand"><div class="brand-mark">RAIC</div><div class="site-title"><strong>Information Request</strong><span>Track request</span></div></div><div class="d-flex align-items-center gap-2"><a href="login.html" class="btn btn-outline-secondary">Logout</a></div></div>
    </header>
    <main class="py-5">
      <div class="container-lg">
        <div class="page-title"><div><h1>Track Your Request</h1><div class="page-breadcrumb"><a href="index.html">Home</a><span class="breadcrumb-divider"></span><span>Track Request</span></div></div></div>
        <div class="row g-4">
          <div class="col-lg-5">
            <div class="form-card"><h5>Enter tracking number</h5><form action="track_request.php" method="get"><div class="mb-3"><label class="form-label" for="trackingNumber">Tracking number</label><input id="trackingNumber" name="tracking" type="text" class="form-control" placeholder="RAIC-2026-0001" value="<?php echo htmlspecialchars($tracking, ENT_QUOTES, 'UTF-8'); ?>"></div><button class="btn btn-primary" type="submit">Track</button></form></div>
            <?php if ($tracking !== ''): ?>
              <?php if ($request): ?>
                <div class="alert alert-success mt-4">Tracking record found for <strong><?php echo htmlspecialchars($tracking, ENT_QUOTES, 'UTF-8'); ?></strong>.</div>
              <?php else: ?>
                <div class="alert alert-warning mt-4">No request was found for this tracking number.</div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
          <div class="col-lg-7">
            <?php if ($request): ?>
              <div class="dashboard-card p-4">
                <h5>Request status</h5>
                <dl class="row mt-4">
                  <dt class="col-sm-4">Tracking number</dt><dd class="col-sm-8"><?php echo htmlspecialchars($request['tracking_number'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  <dt class="col-sm-4">Title</dt><dd class="col-sm-8"><?php echo htmlspecialchars($request['title'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  <dt class="col-sm-4">Ministry</dt><dd class="col-sm-8"><?php echo htmlspecialchars($request['ministry'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  <dt class="col-sm-4">Category</dt><dd class="col-sm-8"><?php echo htmlspecialchars($request['category'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  <dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge bg-info text-dark"><?php echo htmlspecialchars($request['status'], ENT_QUOTES, 'UTF-8'); ?></span></dd>
                </dl>
                <h6 class="mt-4">Description</h6>
                <p><?php echo nl2br(htmlspecialchars($request['description'], ENT_QUOTES, 'UTF-8')); ?></p>
              </div>
            <?php else: ?>
              <div class="dashboard-card p-4"><h5>Status timeline</h5><div class="mt-4"><div class="d-flex align-items-start mb-4"><div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">✓</div><div><strong>Pending</strong><p class="text-muted mb-0">Request received and logged in the system.</p></div></div><div class="d-flex align-items-start mb-4"><div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">⧗</div><div><strong>In Review</strong><p class="text-muted mb-0">Ministry evaluators are reviewing the information request.</p></div></div><div class="d-flex align-items-start mb-4"><div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">✔</div><div><strong>Approved</strong><p class="text-muted mb-0">Information is approved for release and will be shared.</p></div></div><div class="d-flex align-items-start"><div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">✕</div><div><strong>Rejected</strong><p class="text-muted mb-0">Request may be rejected if it does not meet criteria.</p></div></div></div></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
