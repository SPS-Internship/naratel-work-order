<?php include __DIR__.'/partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Request Extend Pelanggan</h1>
      <p class="text-gray-600">Kelola request extend paket, status, invoice, dan catatan mitra</p>
    </div>

    <!-- Filter & Button -->
    <div class="flex justify-between items-center mb-4">
      <div>
        <select id="filterStatus" class="border border-gray-300 rounded px-3 py-2 text-gray-700">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
      <button id="btnAddRequest" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Request
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Request</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After Tgl</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Before Tgl</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody id="requestTable" class="divide-y divide-gray-200">
          <tr><td colspan="8" class="text-center py-4 text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="modalRequest" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">
    <h2 class="text-lg font-semibold mb-4" id="modalTitle">Tambah Request Extend</h2>
    <form id="requestForm" class="space-y-4">
      <input type="hidden" id="old_kode_user">

      <!-- Kode User tampil hanya untuk edit -->
      <div id="kodeUserWrapper" class="hidden">
        <label class="block text-sm">Kode User</label>
        <input type="text" id="kode_user" class="border w-full rounded px-3 py-2 bg-gray-100" readonly>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm">Paket Inet</label>
          <input type="text" id="paket_inet" class="border w-full rounded px-3 py-2" required>
        </div>
        <div>
          <label class="block text-sm">Nominal Paket</label>
          <input type="number" id="nominal_paket" class="border w-full rounded px-3 py-2" required>
        </div>
        <div>
          <label class="block text-sm">Kode Mitra</label>
          <select id="kode_mitra" class="border w-full rounded px-3 py-2">
            <option value="">Pilih Kode Mitra</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Log Update</label>
          <input type="text" id="log_update" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Tanggal Request</label>
          <input type="date" id="tgl_request" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Request</label>
          <select id="status_request" class="border w-full rounded px-3 py-2">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Status Nominal</label>
          <input type="text" id="status_nominal" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Code Invoice</label>
          <select id="code_invoice" class="border w-full rounded px-3 py-2">
            <option value="">Pilih Code Invoice</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Code Invoice Mitra</label>
          <input type="text" id="code_invoice_mitra" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Mitra Depart</label>
          <input type="text" id="mitra_depart" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Piutang</label>
          <input type="text" id="status_piutang" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status WA</label>
          <input type="text" id="status_wa" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Type Proses</label>
          <input type="text" id="type_proses" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">After Tgl</label>
          <input type="date" id="after_tgl" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Before Tgl</label>
          <input type="date" id="before_tgl" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Log Mitra</label>
          <input type="text" id="log_mitra" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Telegram</label>
          <input type="text" id="status_telegram" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Bayar</label>
          <input type="text" id="status_bayar" class="border w-full rounded px-3 py-2">
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const API_URL = 'http://localhost:8000/api/request-extend';
const API_MITRA = 'http://localhost:8000/api/mitra';
const API_INVOICE = 'http://localhost:8000/api/invoice';
const $ = id => document.getElementById(id);

function toDateOnly(iso) {
  if (!iso) return '';
  return iso.includes('T') ? iso.split('T')[0] : iso;
}

function buildPayloadFromForm() {
  return {
    paket_inet: $('paket_inet').value,
    nominal_paket: parseFloat($('nominal_paket').value),
    kode_mitra: $('kode_mitra').value,
    log_update: $('log_update').value,
    tgl_request: $('tgl_request').value || null,
    status_request: $('status_request').value,
    status_nominal: $('status_nominal').value,
    code_invoice: $('code_invoice').value,
    code_invoice_mitra: $('code_invoice_mitra').value,
    mitra_depart: $('mitra_depart').value,
    status_piutang: $('status_piutang').value,
    status_wa: $('status_wa').value,
    type_proses: $('type_proses').value,
    after_tgl: $('after_tgl').value || null,
    before_tgl: $('before_tgl').value || null,
    log_mitra: $('log_mitra').value,
    status_telegram: $('status_telegram').value,
    status_bayar: $('status_bayar').value
  };
}

async function loadMitraOptions() {
  try {
    const res = await fetch(API_MITRA);
    if(!res.ok) throw new Error('Gagal ambil mitra');
    const json = await res.json();
    const select = $('kode_mitra');
    select.innerHTML = '<option value="">Pilih Kode Mitra</option>';
    (json.data || []).forEach(mitra=>{
      const opt = document.createElement('option');
      opt.value = mitra.kode_mitra;
      opt.textContent = `${mitra.kode_mitra} - ${mitra.nama_mitra}`;
      select.appendChild(opt);
    });
  } catch(err) {
    console.error('Error load mitra:', err.message);
  }
}

async function loadInvoiceOptions() {
  try {
    const res = await fetch(API_INVOICE);
    if(!res.ok) throw new Error('Gagal ambil invoice');
    const json = await res.json();
    const select = $('code_invoice');
    select.innerHTML = '<option value="">Pilih Code Invoice</option>';
    (json.data || []).forEach(inv=>{
      const opt = document.createElement('option');
      opt.value = inv.code_invoice;
      opt.textContent = `${inv.code_invoice} - Rp ${Number(inv.nominal).toLocaleString()}`;
      select.appendChild(opt);
    });
  } catch(err) {
    console.error('Error load invoice:', err.message);
  }
}

async function loadRequests(status='') {
  try {
    const res = await fetch(API_URL);
    if(!res.ok) throw new Error('Gagal memuat data: ' + res.status);
    const json = await res.json();
    const tbody = $('requestTable');
    tbody.innerHTML = '';
    const rows = (json.data || []).filter(i => !status || i.status_request === status);
    if(rows.length === 0) {
      tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>';
      return;
    }
    rows.sort((a,b)=> (a.kode_user||'').localeCompare(b.kode_user||''));
    rows.forEach(req => {
      let statusClass = '';
      switch(req.status_request) {
        case 'pending': statusClass='bg-yellow-100 text-yellow-700'; break;
        case 'approved': statusClass='bg-green-100 text-green-700'; break;
        case 'rejected': statusClass='bg-red-100 text-red-700'; break;
        default: statusClass='bg-gray-100 text-gray-700';
      }
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-6 py-4">${req.kode_user ?? '-'}</td>
        <td class="px-6 py-4">${req.paket_inet ?? '-'}</td>
        <td class="px-6 py-4">Rp ${Number(req.nominal_paket).toLocaleString()}</td>
        <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs ${statusClass}">${req.status_request ?? '-'}</span></td>
        <td class="px-6 py-4">${req.code_invoice ?? '-'}</td>
        <td class="px-6 py-4">${toDateOnly(req.after_tgl) || '-'}</td>
        <td class="px-6 py-4">${toDateOnly(req.before_tgl) || '-'}</td>
        <td class="px-6 py-4 flex gap-2">
          <button class="text-blue-600" onclick="editRequest('${req.kode_user}')">Edit</button>
          <button class="text-red-600" onclick="deleteRequest('${req.kode_user}')">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch(err) {
    console.error(err);
    $('requestTable').innerHTML = '<tr><td colspan="8" class="text-center py-4 text-red-500">Gagal memuat data</td></tr>';
  }
}

async function editRequest(kode) {
  try {
    const res = await fetch(`${API_URL}/${kode}`);
    if(!res.ok) throw new Error('Gagal ambil data');
    const data = await res.json();
    const req = data.data;
    $('modalTitle').textContent='Edit Request Extend';
    $('old_kode_user').value=req.kode_user;
    $('kodeUserWrapper').classList.remove('hidden');
    $('kode_user').value=req.kode_user;
    $('paket_inet').value=req.paket_inet;
    $('nominal_paket').value=req.nominal_paket;
    await loadMitraOptions();
    await loadInvoiceOptions();
    $('kode_mitra').value=req.kode_mitra;
    $('log_update').value=req.log_update;
    $('tgl_request').value=toDateOnly(req.tgl_request);
    $('status_request').value=req.status_request;
    $('status_nominal').value=req.status_nominal;
    $('code_invoice').value=req.code_invoice;
    $('code_invoice_mitra').value=req.code_invoice_mitra;
    $('mitra_depart').value=req.mitra_depart;
    $('status_piutang').value=req.status_piutang;
    $('status_wa').value=req.status_wa;
    $('type_proses').value=req.type_proses;
    $('after_tgl').value=toDateOnly(req.after_tgl);
    $('before_tgl').value=toDateOnly(req.before_tgl);
    $('log_mitra').value=req.log_mitra;
    $('status_telegram').value=req.status_telegram;
    $('status_bayar').value=req.status_bayar;
    $('modalRequest').classList.remove('hidden');
  } catch(err) {
    alert('Gagal mengambil data');
  }
}

async function deleteRequest(kode) {
  if(!confirm('Yakin hapus request ini?')) return;
  try {
    const res = await fetch(`${API_URL}/${kode}`, { method:'DELETE' });
    if(!res.ok) throw new Error('Gagal hapus data');
    loadRequests();
  } catch(err) {
    alert('Error: ' + err.message);
  }
}

document.addEventListener('DOMContentLoaded', ()=>loadRequests());

$('filterStatus').addEventListener('change', ()=>loadRequests($('filterStatus').value));

$('btnAddRequest').addEventListener('click', ()=>{
  $('modalTitle').textContent='Tambah Request Extend';
  $('requestForm').reset();
  $('old_kode_user').value='';
  $('kodeUserWrapper').classList.add('hidden'); 
  $('status_request').value='pending';
  loadMitraOptions();
  loadInvoiceOptions();
  $('modalRequest').classList.remove('hidden');
});

$('closeModal').addEventListener('click', ()=> $('modalRequest').classList.add('hidden'));

$('requestForm').addEventListener('submit', async e=>{
  e.preventDefault();
  const payload = buildPayloadFromForm();
  const oldKode = $('old_kode_user').value;
  const method = oldKode ? 'PUT' : 'POST';
  const url = oldKode ? `${API_URL}/${oldKode}` : API_URL;
  try {
    const res = await fetch(url, {
      method,
      headers:{ 'Content-Type':'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if(!res.ok || !result.success) throw new Error(result.message || 'Gagal simpan data');
    $('modalRequest').classList.add('hidden');
    loadRequests();
  } catch(err) {
    alert('Error: ' + err.message);
  }
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
