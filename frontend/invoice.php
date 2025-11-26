<?php include __DIR__.'/partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Invoice</h1>
      <p class="text-gray-600">Kelola invoice, jumlah tagihan, status, dan tanggal</p>
    </div>

    <!-- Filter & Button -->
    <div class="flex justify-between items-center mb-4">
      <div>
        <select id="filterStatus" class="border border-gray-300 rounded px-3 py-2 text-gray-700">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="success">Success</option>
        </select>
      </div>
      <button id="btnAddInvoice" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Invoice
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Invoice</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Invoice</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody id="invoiceTable" class="divide-y divide-gray-200">
          <tr><td colspan="5" class="text-center py-4 text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="modalInvoice" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">
    <h2 class="text-lg font-semibold mb-4" id="modalTitle">Tambah Invoice</h2>
    <form id="invoiceForm" class="space-y-4">
      <input type="hidden" id="old_code_invoice">
      <div>
        <label class="block mb-1">Kode Invoice</label>
        <input type="text" id="code_invoice" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">Jumlah</label>
        <input type="number" id="amount" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">Status</label>
        <select id="status" class="w-full border border-gray-300 rounded px-3 py-2">
          <option value="pending">Pending</option>
          <option value="success">Success</option>
        </select>
      </div>
      <div>
        <label class="block mb-1">Tanggal Invoice</label>
        <input type="date" id="tgl_invoice" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const API_URL = 'http://localhost:8000/api/invoice';
const $ = id => document.getElementById(id);

// Format tanggal ISO
function toDateOnly(iso){
  if(!iso) return '';
  return iso.includes('T') ? iso.split('T')[0] : iso;
}

// Load invoice
async function loadInvoices(status=''){
  try{
    const res = await fetch(API_URL);
    if(!res.ok) throw new Error('Gagal memuat data: '+res.status);
    const json = await res.json();
    const tbody = $('invoiceTable');
    tbody.innerHTML='';

    const rows = (json.data||[]).filter(i => !status || i.status===status);

    if(rows.length===0){
      tbody.innerHTML='<tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>';
      return;
    }

    rows.sort((a,b)=> (a.code_invoice||'').localeCompare(b.code_invoice||''));

    rows.forEach(inv=>{
      const tr = document.createElement('tr');
      let statusClass='';
      switch(inv.status){
        case 'pending': statusClass='bg-yellow-100 text-yellow-700'; break;
        case 'success': statusClass='bg-green-100 text-green-700'; break;
        default: statusClass='bg-gray-100 text-gray-700';
      }

      tr.innerHTML=`
        <td class="px-6 py-4">${inv.code_invoice??'-'}</td>
        <td class="px-6 py-4">Rp ${Number(inv.amount).toLocaleString()}</td>
        <td class="px-6 py-4">
          <span class="px-2 py-1 rounded text-xs ${statusClass}">${inv.status??'-'}</span>
        </td>
        <td class="px-6 py-4">${toDateOnly(inv.tgl_invoice)||'-'}</td>
        <td class="px-6 py-4 flex gap-2">
          <button class="text-blue-600" onclick="editInvoice('${inv.code_invoice}')">Edit</button>
          <button class="text-red-600" onclick="deleteInvoice('${inv.code_invoice}')">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }catch(err){
    console.error(err);
    $('invoiceTable').innerHTML='<tr><td colspan="5" class="text-center py-4 text-red-500">Gagal memuat data</td></tr>';
  }
}

// Edit invoice
async function editInvoice(code){
  try{
    const res = await fetch(`${API_URL}/${code}`);
    if(!res.ok) throw new Error('Gagal memuat data');
    const json = await res.json();
    const inv = json.data;
    if(inv){
      $('modalTitle').textContent='Edit Invoice';
      $('old_code_invoice').value=inv.code_invoice;
      $('code_invoice').value=inv.code_invoice;
      $('amount').value=inv.amount;
      $('status').value=inv.status;
      $('tgl_invoice').value=toDateOnly(inv.tgl_invoice);
      $('modalInvoice').classList.remove('hidden');
    }
  }catch(err){
    alert('Gagal memuat invoice');
  }
}

// Delete invoice
async function deleteInvoice(code){
  if(!confirm('Yakin hapus invoice ini?')) return;
  try{
    await fetch(`${API_URL}/${code}`, { method:'DELETE' });
    loadInvoices();
  }catch(err){
    alert('Gagal menghapus invoice');
  }
}

// Events
document.addEventListener('DOMContentLoaded', ()=>loadInvoices());

$('filterStatus').addEventListener('change', ()=>loadInvoices($('filterStatus').value));

$('btnAddInvoice').addEventListener('click', ()=>{
  $('modalTitle').textContent='Tambah Invoice';
  $('invoiceForm').reset();
  $('old_code_invoice').value='';
  $('status').value='pending';
  $('modalInvoice').classList.remove('hidden');
});

$('closeModal').addEventListener('click', ()=> $('modalInvoice').classList.add('hidden'));

// Submit form
$('invoiceForm').addEventListener('submit', async e=>{
  e.preventDefault();
  const oldCode = $('old_code_invoice').value;
  const data = {
    code_invoice: $('code_invoice').value,
    amount: parseFloat($('amount').value),
    status: $('status').value,
    tgl_invoice: $('tgl_invoice').value
  };

  try{
    let res;
    if(oldCode){ // update
      res = await fetch(`${API_URL}/${oldCode}`, {
        method:'PUT',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify(data)
      });
    }else{ // create
      res = await fetch(API_URL, {
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify(data)
      });
    }
    const result = await res.json();
    if(result.success){
      $('modalInvoice').classList.add('hidden');
      loadInvoices();
    }else{
      alert(result.message||'Gagal menyimpan invoice');
    }
  }catch(err){
    alert('Gagal menyimpan invoice');
  }
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
