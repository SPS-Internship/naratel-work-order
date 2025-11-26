<?php include __DIR__.'/partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Mitra</h1>
      <p class="text-gray-600">Kelola data mitra dan informasi kontak mereka</p>
    </div>

    <!-- Filter & Button -->
    <div class="flex justify-between items-center mb-4">
      <div>
        <input id="filterNama" type="text" placeholder="Cari Nama Mitra..." class="border border-gray-300 rounded px-3 py-2 text-gray-700">
      </div>
      <button id="btnAddMitra" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Mitra
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Mitra</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mitra</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody id="mitraTable" class="divide-y divide-gray-200">
          <tr><td colspan="5" class="text-center py-4 text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="modalMitra" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">
    <h2 class="text-lg font-semibold mb-4" id="modalTitle">Tambah Mitra</h2>
    <form id="mitraForm" class="space-y-4">
      <input type="hidden" id="old_kode_mitra">
      <div>
        <label class="block mb-1">Kode Mitra</label>
        <input type="text" id="kode_mitra" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">Nama Mitra</label>
        <input type="text" id="nama_mitra" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">Kontak</label>
        <input type="text" id="kontak" class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">Alamat</label>
        <textarea id="alamat" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const API_URL = 'http://localhost:8000/api/mitra';
const $ = id => document.getElementById(id);

// Load data mitra
async function loadMitras(filter='') {
  try {
    const res = await    fetch(API_URL);
    if(!res.ok) throw new Error('Gagal memuat data: ' + res.status);
    const json = await res.json();
    const tbody = $('mitraTable');
    tbody.innerHTML = '';

    const rows = (json.data || []).filter(m => m.nama_mitra.toLowerCase().includes(filter.toLowerCase()));

    if(rows.length === 0){
      tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>';
      return;
    }

    rows.sort((a,b)=> (a.kode_mitra||'').localeCompare(b.kode_mitra||''));

    rows.forEach(m=>{
      const tr = document.createElement('tr');
      tr.innerHTML=`
        <td class="px-6 py-4">${m.kode_mitra??'-'}</td>
        <td class="px-6 py-4">${m.nama_mitra??'-'}</td>
        <td class="px-6 py-4">${m.kontak??'-'}</td>
        <td class="px-6 py-4">${m.alamat??'-'}</td>
        <td class="px-6 py-4 flex gap-2">
          <button class="text-blue-600" onclick="editMitra('${m.kode_mitra}')">Edit</button>
          <button class="text-red-600" onclick="deleteMitra('${m.kode_mitra}')">Hapus</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch(err){
    console.error(err);
    $('mitraTable').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-500">Gagal memuat data</td></tr>';
  }
}

// Edit mitra
async function editMitra(kode){
  try {
    const res = await fetch(`${API_URL}/${kode}`);
    if(!res.ok) throw new Error('Gagal memuat data');
    const json = await res.json();
    const m = json.data;
    if(m){
      $('modalTitle').textContent='Edit Mitra';
      $('old_kode_mitra').value=m.kode_mitra;
      $('kode_mitra').value=m.kode_mitra;
      $('nama_mitra').value=m.nama_mitra;
      $('kontak').value=m.kontak;
      $('alamat').value=m.alamat;
      $('modalMitra').classList.remove('hidden');
    }
  } catch(err){
    alert('Gagal memuat data mitra');
  }
}

// Delete mitra
async function deleteMitra(kode){
  if(!confirm('Yakin hapus mitra ini?')) return;
  try {
    await fetch(`${API_URL}/${kode}`, { method:'DELETE' });
    loadMitras();
  } catch(err){
    alert('Gagal menghapus mitra');
  }
}

// Events
document.addEventListener('DOMContentLoaded', ()=>loadMitras());

$('filterNama').addEventListener('input', ()=>loadMitras($('filterNama').value));

$('btnAddMitra').addEventListener('click', ()=>{
  $('modalTitle').textContent='Tambah Mitra';
  $('mitraForm').reset();
  $('old_kode_mitra').value='';
  $('modalMitra').classList.remove('hidden');
});

$('closeModal').addEventListener('click', ()=> $('modalMitra').classList.add('hidden'));

// Submit form
$('mitraForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const kodeOld = $('old_kode_mitra').value;
  const data = {
    kode_mitra: $('kode_mitra').value,
    nama_mitra: $('nama_mitra').value,
    kontak: $('kontak').value,
    alamat: $('alamat').value
  };

  try {
    let res;
    if(kodeOld){ // update
      res = await fetch(`${API_URL}/${kodeOld}`, {
        method: 'PUT',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify(data)
      });
    } else { // create
      res = await fetch(API_URL, {
        method: 'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify(data)
      });
    }
    const result = await res.json();
    if(result.success){
      $('modalMitra').classList.add('hidden');
      loadMitras();
    } else {
      alert(result.message || 'Gagal menyimpan data');
    }
  } catch(err){
    alert('Gagal menyimpan data');
  }
});
</script>

<?php include __DIR__.'/partials/footer.php'; ?>
