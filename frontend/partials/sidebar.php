<aside class="w-64 bg-white h-screen shadow-lg fixed flex flex-col">
   <div class="flex items-center gap-3 p-6 border-b">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 text-purple-600">
      <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
      <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
      <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
      <path d="M10 6h4"></path>
      <path d="M10 10h4"></path>
      <path d="M10 14h4"></path>
      <path d="M10 18h4"></path>
    </svg>
    <div>
      <h2 class="text-lg font-bold text-gray-800 tracking-tight">Naratel</h2>
      <p class="text-xs text-gray-500">Work Order System</p>
    </div>
  </div>
  <nav class="p-4 space-y-2 flex-1">
    <a href="/dashboard.php" class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='dashboard.php'?'active-menu':''; ?> hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3z M3 9h18 M9 21V9" />
      </svg>
      <span>Dashboard</span>
    </a>
    <a href="/followup.php" class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='followup.php'?'active-menu':''; ?> hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M5 8h14M5 4h14M5 20h14" />
      </svg>
      <span>Follow-up Pelanggan</span>
    </a>
    <a href="/mitra.php" class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='mitra.php'?'active-menu':''; ?> hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4z M6 20v-2a6 6 0 0 1 12 0v2" />
      </svg>
      <span>Data Mitra</span>
    </a>
    <a href="/invoice.php" class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='invoice.php'?'active-menu':''; ?> hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6 M12 3v4 M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
      </svg>
      <span>Tagihan & Pembayaran (Invoice)</span>
    </a>
    <a href="/requestextend.php" class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='requestextend.php.php'?'active-menu':''; ?> hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3 M21 12a9 9 0 1 0-9 9 9 9 0 0 0 9-9z" />
      </svg>
      <span>Perpanjangan Layanan(Extend)</span>
    </a>
  </nav>
</aside>
