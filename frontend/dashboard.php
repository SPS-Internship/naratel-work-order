<?php include __DIR__.'/partials/header.php'; ?>

<?php
// --- Ambil data dari API / database ---
function fetchData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($res, true);
    return isset($json['data']) && is_array($json['data']) ? $json['data'] : [];
}

$requestExtend = fetchData("http://localhost:8000/api/request-extend");
$invoices = fetchData("http://localhost:8000/api/invoice");
$mitras = fetchData("http://localhost:8000/api/mitra");

// --- Hitung total ---
$totalReq = count($requestExtend);

// --- Hitung Selesai / Approved ---
$selesaiCount = 0;
foreach($requestExtend as $r){
    $status = strtolower(trim($r['status_request'] ?? ''));
    if($status === 'approved' || $status === 'selesai') $selesaiCount++;
}

// --- Tagihan tertunda (status != success) ---
$pendingCount = 0;
foreach($invoices as $inv){
    $status = strtolower(trim($inv['status'] ?? ''));
    if($status !== 'success') $pendingCount++;
}

// --- Jumlah Mitra ---
$mitraCount = count($mitras);

// --- Work Order terbaru 3 terakhir berdasarkan tgl_request ---
usort($requestExtend, function($a,$b){
    return strtotime($b['tgl_request'] ?? 0) - strtotime($a['tgl_request'] ?? 0);
});
$latest3 = array_slice($requestExtend,0,3);

// --- Overdue ---
$today = time();
$overdue = array_filter($requestExtend, function($r) use($today){
    $status = strtolower(trim($r['status_request'] ?? ''));
    $tgl = strtotime($r['tgl_request'] ?? '0');
    return !in_array($status,['approved','selesai']) && $tgl < $today;
});

function renderStatusBadge($status){
    $s = strtolower(trim($status ?? ''));
    $cls = 'bg-gray-200 text-gray-800';
    if(in_array($s,['approved','selesai'])) $cls = 'bg-green-200 text-green-800';
    else if($s === 'pending') $cls = 'bg-yellow-200 text-yellow-800';
    else if(in_array($s,['rejected','tertunda'])) $cls = 'bg-red-200 text-red-800';
    return "<span class='px-2 py-0.5 rounded text-xs font-medium $cls'>$status</span>";
}
?>

<section class="p-6 bg-gray-50 min-h-screen">
  <h1 class="text-3xl font-bold mb-2">Dashboard Work Order</h1>
  <p class="mb-6 text-gray-600">Selamat datang di sistem manajemen work order Naratel</p>

  <div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
      <div class="flex items-center text-sm font-medium text-gray-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6h4v2H5v-2h4z" />
        </svg>
        Total Request Extend
      </div>
      <div class="text-2xl font-bold"><?= $totalReq ?></div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
      <div class="flex items-center text-sm font-medium text-gray-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Request Perpanjangan Selesai
      </div>
      <div class="text-2xl font-bold"><?= $selesaiCount ?></div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
      <div class="flex items-center text-sm font-medium text-gray-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Tagihan Tertunda
      </div>
      <div class="text-2xl font-bold"><?= $pendingCount ?></div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
      <div class="flex items-center text-sm font-medium text-gray-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
        </svg>
        Jumlah Mitra
      </div>
      <div class="text-2xl font-bold"><?= $mitraCount ?></div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm mb-8">
    <h2 class="font-semibold mb-2">Work Order Terbaru</h2>
    <?php foreach($latest3 as $wo): ?>
      <div class="flex items-center justify-between p-3 bg-gray-100 rounded mb-2">
        <div>
          <p class="font-medium"><?= htmlspecialchars($wo['kode_user'] ?? '') ?></p>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($wo['nama_mitra'] ?? $wo['kode_mitra'] ?? '') ?></p>
        </div>
        <?= renderStatusBadge($wo['status_request'] ?? '') ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm">
    <h2 class="font-semibold mb-2">Follow-up Overdue</h2>
    <?php foreach($overdue as $wo): ?>
      <div class="flex items-center justify-between p-3 bg-red-100 rounded mb-2">
        <div>
          <p class="font-medium"><?= htmlspecialchars($wo['kode_user'] ?? '') ?></p>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($wo['nama_mitra'] ?? $wo['kode_mitra'] ?? '') ?></p>
        </div>
        <?= renderStatusBadge($wo['status_request'] ?? '') ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__.'/partials/footer.php'; ?>
