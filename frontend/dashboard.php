<?php include __DIR__.'/partials/header.php'; ?>

<?php
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

$totalReq = count($requestExtend);

$selesaiCount = 0;
foreach($requestExtend as $r){
    $status = strtolower(trim($r['status_request'] ?? ''));
    if($status === 'approved' || $status === 'selesai') $selesaiCount++;
}

$pendingCount = 0;
foreach($invoices as $inv){
    $status = strtolower(trim($inv['status'] ?? ''));
    if($status !== 'success') $pendingCount++;
}

$mitraCount = count($mitras);

usort($requestExtend, function($a,$b){
    return strtotime($b['tgl_request'] ?? 0) - strtotime($a['tgl_request'] ?? 0);
});
$latest3 = array_slice($requestExtend,0,3);

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

<section class="p-6 bg-white min-h-screen">
  <h1 class="text-3xl font-bold mb-1">Dashboard Work Order</h1>
  <p class="mb-8 text-gray-600">Selamat datang di sistem manajemen work order Naratel</p>

  <!-- Summary Cards -->
  <div class="grid grid-cols-4 gap-6 mb-10">
    <div class="bg-[#FFE277] p-6 rounded-2xl shadow hover:shadow-md transition">
      <p class="flex items-center text-sm font-medium text-gray-800 mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6h4v2H5v-2h4z"/>
        </svg>
        Total Request Extend
      </p>
      <div class="text-3xl font-bold"><?= $totalReq ?></div>
    </div>

    <div class="bg-[#FFE277] p-6 rounded-2xl shadow hover:shadow-md transition">
      <p class="flex items-center text-sm font-medium text-gray-800 mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Request Perpanjangan Selesai
      </p>
      <div class="text-3xl font-bold"><?= $selesaiCount ?></div>
    </div>

    <div class="bg-[#FFE277] p-6 rounded-2xl shadow hover:shadow-md transition">
      <p class="flex items-center text-sm font-medium text-gray-800 mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Tagihan Tertunda
      </p>
      <div class="text-3xl font-bold"><?= $pendingCount ?></div>
    </div>

    <div class="bg-[#FFE277] p-6 rounded-2xl shadow hover:shadow-md transition">
      <p class="flex items-center text-sm font-medium text-gray-800 mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
        </svg>
        Jumlah Mitra
      </p>
      <div class="text-3xl font-bold"><?= $mitraCount ?></div>
    </div>
  </div>

  <!-- Work Order Terbaru -->
  <!-- Work Order Terbaru & Follow-up Overdue -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  
  <!-- Work Order Terbaru -->
  <div class="bg-[#FFFDE8] p-6 rounded-2xl shadow">
    <h2 class="font-semibold text-lg mb-4">Work Order Terbaru</h2>
    <?php foreach($latest3 as $wo): ?>
      <div class="flex items-center justify-between p-3 bg-[#B6AE9F]/50 rounded-xl mb-3 hover:bg-[#FF9642] transition">
        <div>
          <p class="font-semibold text-gray-900"><?= htmlspecialchars($wo['kode_user'] ?? '') ?></p>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($wo['nama_mitra'] ?? $wo['kode_mitra'] ?? '') ?></p>
        </div>
        <?= renderStatusBadge($wo['status_request'] ?? '') ?>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Follow-up Overdue -->
  <div class="bg-[#FF9642] p-6 rounded-2xl shadow">
    <h2 class="font-semibold text-lg mb-4">Follow-up Overdue</h2>
    <?php foreach($overdue as $wo): ?>
      <div class="flex items-center justify-between p-3 bg-[#FFE277] rounded-xl mb-3 hover:bg-white transition">
        <div>
          <p class="font-semibold text-gray-900"><?= htmlspecialchars($wo['kode_user'] ?? '') ?></p>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($wo['nama_mitra'] ?? $wo['kode_mitra'] ?? '') ?></p>
        </div>
        <?= renderStatusBadge($wo['status_request'] ?? '') ?>
      </div>
    <?php endforeach; ?>
  </div>

</div>


  
</section>

<?php include __DIR__.'/partials/footer.php'; ?>
