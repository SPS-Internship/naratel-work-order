<aside class="w-64 bg-white h-screen shadow-lg fixed flex flex-col">
  <div class="flex items-center gap-3 p-6 border-b">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 text-[#FF9642]">
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

    <a href="/dashboard.php"
      class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='dashboard.php'?'active-menu':''; ?> hover:bg-[#FF9642] hover:text-black transition">
      <span>Dashboard</span>
    </a>

    <a href="/followup.php"
      class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='followup.php'?'active-menu':''; ?> hover:bg-[#FF9642] hover:text-black transition">
      <span>Follow-up Pelanggan</span>
    </a>

    <a href="/mitra.php"
      class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='mitra.php'?'active-menu':''; ?> hover:bg-[#FF9642] hover:text-black transition">
      <span>Data Mitra</span>
    </a>

    <a href="/invoice.php"
      class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='invoice.php'?'active-menu':''; ?> hover:bg-[#FF9642] hover:text-black transition">
      <span>Tagihan & Pembayaran (Invoice)</span>
    </a>

    <a href="/requestextend.php"
      class="flex items-center gap-3 py-2 px-4 rounded-lg text-gray-700 <?php echo basename($_SERVER['PHP_SELF'])==='requestextend.php'?'active-menu':''; ?> hover:bg-[#FF9642] hover:text-black transition">
      <span>Perpanjangan Layanan (Extend)</span>
    </a>

  </nav>
</aside>
