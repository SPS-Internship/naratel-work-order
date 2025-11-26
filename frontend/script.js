
(() => {
  // ===== API =====
  const API_REQUEST_EXTEND = "http://localhost:8000/api/request-extend";
  const API_INVOICE = "http://localhost:8000/api/invoice";
  const API_MITRA = "http://localhost:8000/api/mitra";

  // ===== UTILS =====
  function renderStatusBadge(status) {
    const s = (status || '').toLowerCase();
    let cls = 'bg-gray-200 text-gray-800';
    if (s.includes('selesai')) cls = 'bg-green-200 text-green-800';
    if (s.includes('pending')) cls = 'bg-yellow-200 text-yellow-800';
    if (s.includes('tertunda')) cls = 'bg-red-200 text-red-800';
    return `<span class="px-2 py-0.5 rounded text-xs font-medium ${cls}">${status || ''}</span>`;
  }

  function formatDate(d) {
    if (!d) return '';
    const dt = new Date(d);
    if (isNaN(dt)) return d;
    return dt.toLocaleDateString('id-ID');
  }

  function isTrueFlag(v) {
    return v === true || v === 1 || v === '1' || v === 't' || v === 'T' || v === 'true' || v === 'True';
  }

  function flagIcon(on) {
    return on
      ? `<svg class="w-4 h-4 text-emerald-600" viewBox="0 0 490 490" fill="currentColor"><polygon points="490,42.789 477.409,34.056 200.447,433.604 9.69,277.553 0,289.396 203.587,455.944"/></svg>`
      : `<svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13.41,12l6.3-6.29a1,1,0,1,0-1.42-1.42L12,10.59,5.71,4.29A1,1,0,0,0,4.29,5.71L10.59,12l-6.3,6.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l6.29,6.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"/></svg>`;
  }

  function renderActionButtons(id, type) {
    return `
      <div class="flex gap-2">
        <button onclick="edit${type}(${id})" class="px-2 py-1 bg-white border rounded text-sm hover:bg-gray-100">Edit</button>
        <button onclick="hapus${type}(${id})" class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-500">Hapus</button>
      </div>`;
  }

  // ===== DASHBOARD =====
  async function loadDashboard() {
    try {
      // Fetch Request Extend
      const resReq = await fetch(API_REQUEST_EXTEND);
      const jsonReq = await resReq.json();
      const reqData = jsonReq.success ? jsonReq.data : [];

      // Fetch Invoice
      const resInv = await fetch(API_INVOICE);
      const jsonInv = await resInv.json();
      const invData = jsonInv.success ? jsonInv.data : [];

      // Fetch Mitra
      const resMitra = await fetch(API_MITRA);
      const jsonMitra = await resMitra.json();
      const mitraData = jsonMitra.success ? jsonMitra.data : [];

      // --- Update cards ---
      const totalEl = document.getElementById('total-wo-count');
      const selesaiEl = document.getElementById('selesai-count');
      const pendingEl = document.getElementById('doc-count');
      const mitraEl = document.getElementById('pic-count');

      if (totalEl) totalEl.textContent = reqData.length;
      if (selesaiEl) selesaiEl.textContent = reqData.filter(d => d.status_request === 'selesai').length;

      // Tagihan tertunda: invoice.status !== 'success'
      if (pendingEl) {
        const pendingInvoices = invData.filter(inv => inv.status !== 'success');
        pendingEl.textContent = pendingInvoices.length;
      }

      if (mitraEl) mitraEl.textContent = mitraData.length;

      // --- Table Work Order ---
      const table = document.getElementById('woTable');
      if (table) {
        table.innerHTML = '';
        reqData.forEach(wo => {
          table.innerHTML += `
            <tr>
              <td class="px-4 py-2">${wo.kode_user}</td>
              <td class="px-4 py-2">${wo.paket_inet}</td>
              <td class="px-4 py-2">${wo.nominal_paket}</td>
              <td class="px-4 py-2">${wo.kode_mitra}</td>
              <td class="px-4 py-2">${formatDate(wo.tgl_request)}</td>
              <td class="px-4 py-2">${renderStatusBadge(wo.status_request)}</td>
            </tr>`;
        });
      }

      // --- Latest WO ---
      const latestContainer = document.getElementById('latest-wo-list');
      if (latestContainer) {
        latestContainer.innerHTML = '';
        reqData.slice(-3).reverse().forEach(wo => {
          latestContainer.innerHTML += `
            <div class="flex items-center justify-between p-3 bg-gray-100 rounded mb-2">
              <div>
                <p class="font-medium">${wo.kode_user}</p>
                <p class="text-sm text-gray-600">${wo.mitra?.nama_mitra || ''}</p>
              </div>
              ${renderStatusBadge(wo.status_request)}
            </div>`;
        });
      }

      // --- Overdue / Pending ---
      const overdueContainer = document.getElementById('latest-doc-list');
      if (overdueContainer) {
        const today = new Date();
        const overdue = reqData.filter(d => d.status_request !== 'selesai' && new Date(d.tgl_request) < today);
        overdueContainer.innerHTML = '';
        overdue.forEach(wo => {
          overdueContainer.innerHTML += `
            <div class="flex items-center justify-between p-3 bg-red-100 rounded mb-2">
              <div>
                <p class="font-medium">${wo.kode_user}</p>
                <p class="text-sm text-gray-600">${wo.mitra?.nama_mitra || ''}</p>
              </div>
              ${renderStatusBadge(wo.status_request)}
            </div>`;
        });
      }

    } catch (err) {
      console.error("Gagal load dashboard:", err);
    }
  }

  // ===== SWITCH MENU =====
  function showMenu(id) {
    document.querySelectorAll(".menu-section").forEach(s => s.classList.add("hidden"));
    document.getElementById(id)?.classList.remove("hidden");
  }

  // ===== SET ACTIVE SIDEBAR =====
  function setActiveMenu(el) {
    document.querySelectorAll('aside nav a').forEach(a => a.classList.remove('active-menu'));
    el.classList.add('active-menu');
  }

  // ===== INIT =====
  window.onload = () => {
    loadDashboard();
  };

})();
