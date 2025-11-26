<?php include __DIR__.'/partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Monitoring Follow-up Pelanggan</h1>
      <p class="text-gray-600">Pantau status jatuh tempo, janji bayar, dan aktivitas follow-up</p>
    </div>

    <!-- Filter & Button -->
    <div class="flex justify-between items-center mb-4">
      <div>
        <select id="filterStatus" class="border border-gray-300 rounded px-3 py-2 text-gray-700">
          <option value="">Semua Status</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <button id="btnAddFollowup" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Follow-up
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelurahan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal JthTempo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Janji Bayar</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody id="followupTable" class="divide-y divide-gray-200">
          <tr><td colspan="8" class="text-center py-4 text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="modalFollowup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">
    <h2 class="text-lg font-semibold mb-4" id="modalTitle">Tambah Follow-up</h2>
    <form id="followupForm" class="space-y-4">
      <input type="hidden" id="followup_id" name="id">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm">Kode User</label>
          <input type="text" id="kode_user" class="border w-full rounded px-3 py-2" readonly>
        </div>
        <div>
          <label class="block text-sm">Kelurahan</label>
          <input type="text" id="kelurahan" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Paket</label>
          <input type="text" id="paket" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Expiration</label>
          <input type="date" id="expiration" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Jumday</label>
          <input type="number" id="jumday" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status</label>
          <select id="status" class="border w-full rounded px-3 py-2">
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label class="block text-sm">Tanggal JthTempo</label>
          <input type="date" id="tanggal_jthtempo" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Janji Bayar</label>
          <input type="date" id="janji_bayar" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Keterangan</label>
          <input type="text" id="keterangan" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Keterangan 2</label>
          <input type="text" id="keterangan2" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Kode Layanan</label>
          <input type="text" id="kd_layanan" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">PIC</label>
          <input type="text" id="pic" class="border w-full rounded px-3 py-2">
        </div>

        <!-- ✅ Tambahan field baru -->
        <div>
          <label class="block text-sm">Status Log</label>
          <input type="text" id="status_log" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Follow-up</label>
          <input type="text" id="status_followup" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Post WO</label>
          <input type="text" id="status_postwo" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Tanggal Terakhir</label>
          <input type="date" id="tanggal_terakhir" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Tanggal Status</label>
          <input type="date" id="tanggal_status" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Status Reminder</label>
          <input type="text" id="status_reminder" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">Expected Date</label>
          <input type="date" id="expected_date" class="border w-full rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm">CW Conv</label>
          <input type="text" id="cw_conv" class="border w-full rounded px-3 py-2">
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
const API_URL = 'http://localhost:8000/api/followup';
const $ = id => document.getElementById(id);

function toDateOnly(iso) {
  if (!iso) return '';
  return iso.includes('T') ? iso.split('T')[0] : iso;
}

function buildPayloadFromForm() {
  return {
    kelurahan: $('kelurahan').value || '',
    paket: $('paket').value || '',
    expiration: $('expiration').value || null,
    jumday: $('jumday').value ? parseInt($('jumday').value,10) : null,
    status: $('status').value || '',
    tanggal_jthtempo: $('tanggal_jthtempo').value || null,
    janji_bayar: $('janji_bayar').value || null,
    keterangan: $('keterangan').value || '',
    keterangan2: $('keterangan2').value || '',
    kd_layanan: $('kd_layanan').value || '',
    pic: $('pic').value || '',
    status_log: $('status_log').value || '',
    status_followup: $('status_followup').value || '',
    status_postwo: $('status_postwo').value || '',
    tanggal_terakhir: $('tanggal_terakhir').value || null,
    tanggal_status: $('tanggal_status').value || null,
    status_reminder: $('status_reminder').value || '',
    expected_date: $('expected_date').value || null,
    cw_conv: $('cw_conv').value || ''
  };
}

async function loadFollowups(status='') {
  try {
    const res = await fetch(API_URL + (status ? '?status=' + encodeURIComponent(status) : ''));
    if(!res.ok) throw new Error('Gagal memuat data: ' + res.status);
    const json = await res.json();
    const tbody = $('followupTable');
    tbody.innerHTML = '';
    const rows = json.data || [];
    if(rows.length === 0){
      tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>';
      return;
    }

    rows.sort((a,b)=> (a.id||0) - (b.id||0));

    rows.forEach(item=>{ 
      let statusClass='';
      switch(item.status){
        case 'active': statusClass='bg-green-100 text-green-700'; break;
        case 'pending': statusClass='bg-yellow-100 text-yellow-700'; break;
        case 'inactive': statusClass='bg-red-100 text-red-700'; break;
        default: statusClass='bg-gray-100 text-gray-700';
      }

      const tr = document.createElement('tr');
      tr.innerHTML=`
        <td class="px-6 py-4">${item.id??'-'}</td>
        <td class="px-6 py-4">${item.kode_user??'-'}</td>
        <td class="px-6 py-4">${item.kelurahan??'-'}</td>
        <td class="px-6 py-4">${item.paket??'-'}</td>
        <td class="px-6 py-4">
          <span class="px-2 py-1 rounded text-xs ${statusClass}">${item.status??'-'}</span>
        </td>
        <td class="px-6 py-4">${toDateOnly(item.tanggal_jthtempo)||'-'}</td>
        <td class="px-6 py-4">${toDateOnly(item.janji_bayar)||'-'}</td>
        <td class="px-6 py-4 flex gap-2">
          <button class="text-blue-600" onclick="editFollowup(${item.id})">Edit</button>
          <button class="text-red-600" onclick="deleteFollowup(${item.id})">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch(err){
    console.error(err);
    $('followupTable').innerHTML = '<tr><td colspan="8" class="text-center py-4 text-red-500">Gagal memuat data</td></tr>';
  }
}

async function editFollowup(id) {
  try {
    const res = await fetch(`${API_URL}/${id}`);
    if(!res.ok) throw new Error('Gagal ambil data');
    const data = await res.json();
    const f = data.data;

    $('modalTitle').textContent='Edit Follow-up';
    $('followup_id').value=f.id;
    $('kode_user').value=f.kode_user||'';
    $('kode_user').setAttribute('readonly', true);
    $('kelurahan').value=f.kelurahan||'';
    $('paket').value=f.paket||'';
    $('expiration').value=toDateOnly(f.expiration);
    $('jumday').value=f.jumday||'';
    $('status').value=f.status||'active';
    $('tanggal_jthtempo').value=toDateOnly(f.tanggal_jthtempo);
    $('janji_bayar').value=toDateOnly(f.janji_bayar);
    $('keterangan').value=f.keterangan||'';
    $('keterangan2').value=f.keterangan2||'';
    $('kd_layanan').value=f.kd_layanan||'';
    $('pic').value=f.pic||'';
    $('status_log').value=f.status_log||'';
    $('status_followup').value=f.status_followup||'';
    $('status_postwo').value=f.status_postwo||'';
    $('tanggal_terakhir').value=toDateOnly(f.tanggal_terakhir);
    $('tanggal_status').value=toDateOnly(f.tanggal_status);
    $('status_reminder').value=f.status_reminder||'';
    $('expected_date').value=toDateOnly(f.expected_date);
    $('cw_conv').value=f.cw_conv||'';

    $('modalFollowup').classList.remove('hidden');
  } catch(err) {
    alert('Gagal mengambil data: ' + err.message);
  }
}

async function deleteFollowup(id){
  if(!confirm('Hapus data ini?')) return;
  try {
    const res = await fetch(`${API_URL}/${id}`, { method:'DELETE' });
    if(!res.ok) throw new Error('Gagal hapus data');
    loadFollowups();
  } catch(err) {
    alert('Error: ' + err.message);
  }
}

$('followupForm').addEventListener('submit', async e=>{
  e.preventDefault();
  const payload = buildPayloadFromForm();
  const id = $('followup_id').value;
  const method = id ? 'PUT' : 'POST';
  const url = id ? `${API_URL}/${id}` : API_URL;

  try {
    const res = await fetch(url, {
      method,
      headers:{ 'Content-Type':'application/json' },
      body: JSON.stringify(payload)
    });
    if(!res.ok) throw new Error('Gagal simpan data');
    $('modalFollowup').classList.add('hidden');
    loadFollowups();
  } catch(err) {
    alert('Error: ' + err.message);
  }
});

document.addEventListener('DOMContentLoaded', ()=>loadFollowups());

$('filterStatus').addEventListener('change', function(){ loadFollowups(this.value); });

$('btnAddFollowup').addEventListener('click', async ()=>{
  $('modalTitle').textContent='Tambah Follow-up';
  $('followupForm').reset();
  $('followup_id').value='';
  $('status').value='active';

  try {
    const res = await fetch(`${API_URL}/next-code`);
    if(!res.ok) throw new Error('Gagal ambil kode user');
    const data = await res.json();
    $('kode_user').value = data.data.next_code || '';
  } catch(err) {
    console.error('Gagal generate kode:', err.message);
    $('kode_user').value = 'Otomatis kode'; // fallback jika error
  }
  $('kode_user').setAttribute('readonly', true);

  $('modalFollowup').classList.remove('hidden');
});

$('closeModal').addEventListener('click', ()=> $('modalFollowup').classList.add('hidden'));
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
