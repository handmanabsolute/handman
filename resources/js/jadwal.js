function openJadwalModal(dateString, formattedDate) {
    document.getElementById('modal-date-title').innerText = `Detail Jadwal - ${formattedDate}`;
    document.getElementById('note-date').value = dateString;
    resetNoteForm();
    const cell = document.getElementById(`cell-${dateString}`);
    if (!cell) return;
    const tasks = JSON.parse(cell.getAttribute('data-tasks') || '[]');
    const notes = JSON.parse(cell.getAttribute('data-notes') || '[]');
    let tasksHtml = '';
    tasks.forEach(t => {
        const badgeClass = t.prioritas === 'Tinggi'
            ? 'bg-red-50 text-red-700 border-red-100'
            : (t.prioritas === 'Sedang' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-green-50 text-green-700 border-green-100');

        tasksHtml += `
            <div class="p-3 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition-all flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase border ${badgeClass}">${t.prioritas}</span>
                    <h5 class="text-xs font-bold text-gray-800 mt-1 truncate" title="${t.nama_tugas}">${t.nama_tugas}</h5>
                    <p class="text-[10px] text-gray-400 mt-0.5">Deadline: ${formatDateTime(t.deadline_tugas)}</p>
                </div>
                <a href="/tugas/${t.id}" class="px-2.5 py-1 text-[10px] font-bold text-[#3B28CC] border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-50 rounded-lg transition-all whitespace-nowrap">Detail</a>
            </div>
        `;
    });
    if (tasks.length === 0) {
        tasksHtml = `<p class="text-xs text-gray-400 italic text-center py-6">Tidak ada tugas berjalan pada tanggal ini.</p>`;
    }
    document.getElementById('modal-tasks-list').innerHTML = tasksHtml;
    document.getElementById('modal-tasks-count').innerText = tasks.length;
    renderNotesList(notes);
    const modal = document.getElementById('jadwal-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function renderNotesList(notes) {
    let notesHtml = '';
    notes.forEach(n => {
        const taskBadge = n.tugas
            ? `<span class="mt-1 inline-flex text-[9px] px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded-md border border-blue-100 truncate max-w-full"><i class="fa-solid fa-link text-[8px] mr-1"></i>${n.tugas.nama_tugas}</span>`
            : '';

        notesHtml += `
            <div class="p-3 border border-gray-100 rounded-xl bg-amber-50/20 space-y-1.5" id="note-item-${n.id}">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs text-gray-700 whitespace-pre-line break-words leading-relaxed flex-1 min-w-0">${escapeHtml(n.catatan)}</p>
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="editNote('${n.id}', \`${escapeJs(n.catatan)}\`)" class="p-1.5 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition-colors" title="Edit">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                        <button type="button" onclick="deleteNote('${n.id}')" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                        </button>
                    </div>
                </div>
                ${taskBadge}
            </div>
        `;
    });
    if (notes.length === 0) {
        notesHtml = `<p class="text-xs text-gray-400 italic text-center py-6">Belum ada catatan.</p>`;
    }
    document.getElementById('modal-notes-list').innerHTML = notesHtml;
}

function closeJadwalModal() {
    const modal = document.getElementById('jadwal-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function editNote(id, catatan) {
    document.getElementById('note-id').value = id;
    document.getElementById('note-content').value = catatan;
    document.getElementById('btn-submit-note').innerText = 'Simpan Perubahan';
    document.getElementById('btn-cancel-edit').classList.remove('hidden');
    document.getElementById('note-content').focus();
}

function resetNoteForm() {
    document.getElementById('note-id').value = '';
    document.getElementById('note-content').value = '';
    document.getElementById('btn-submit-note').innerText = 'Simpan Catatan';
    document.getElementById('btn-cancel-edit').classList.add('hidden');
}

function submitNoteForm(event) {
    event.preventDefault();
    const tanggal = document.getElementById('note-date').value;
    const noteId = document.getElementById('note-id').value;
    const catatan = document.getElementById('note-content').value;
    const token = document.querySelector('input[name="_token"]').value;
    const btnSubmit = document.getElementById('btn-submit-note');
    btnSubmit.disabled = true;
    btnSubmit.innerText = 'Memproses...';
    const url = noteId ? `/jadwal/notes/${noteId}` : '/jadwal/notes';
    const method = noteId ? 'PUT' : 'POST';
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tanggal: tanggal,
            catatan: catatan,
            tugas_id: null
        })
    })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const cell = document.getElementById(`cell-${tanggal}`);
                let notesList = JSON.parse(cell.getAttribute('data-notes') || '[]');
                if (method === 'POST') {
                    notesList.push(data.note);
                } else {
                    notesList = notesList.map(n => n.id === noteId ? data.note : n);
                }
                cell.setAttribute('data-notes', JSON.stringify(notesList));
                updateCellIndicator(cell, notesList.length);
                renderNotesList(notesList);
                resetNoteForm();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menyimpan catatan. Silakan coba lagi.');
        })
        .finally(() => {
            btnSubmit.disabled = false;
            btnSubmit.innerText = noteId ? 'Simpan Perubahan' : 'Simpan Catatan';
        });
}

function deleteNote(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus catatan ini?')) return;
    const tanggal = document.getElementById('note-date').value;
    const token = document.querySelector('input[name="_token"]').value;
    fetch(`/jadwal/notes/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const cell = document.getElementById(`cell-${tanggal}`);
                let notesList = JSON.parse(cell.getAttribute('data-notes') || '[]');
                notesList = notesList.filter(n => n.id !== id);
                cell.setAttribute('data-notes', JSON.stringify(notesList));
                updateCellIndicator(cell, notesList.length);
                renderNotesList(notesList);
                resetNoteForm();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghapus catatan.');
        });
}

function updateCellIndicator(cell, count) {
    let indicator = cell.querySelector('.note-count-indicator');
    if (count > 0) {
        if (indicator) {
            indicator.querySelector('.note-count').innerText = count;
        } else {
            const topDiv = cell.querySelector('.flex');
            const newInd = document.createElement('span');
            newInd.className = 'note-count-indicator flex items-center gap-0.5 text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md border border-amber-200/50';
            newInd.innerHTML = `<i class="fa-solid fa-sticky-note"></i><span class="note-count">${count}</span>`;
            topDiv.appendChild(newInd);
        }
    } else {
        if (indicator) {
            indicator.remove();
        }
    }
}

function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '-';
    const date = new Date(dateTimeStr);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function escapeJs(text) {
    if (!text) return '';
    return text.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"').replace(/\n/g, '\\n').replace(/\r/g, '\\r');
}

window.openJadwalModal = openJadwalModal;
window.renderNotesList = renderNotesList;
window.closeJadwalModal = closeJadwalModal;
window.editNote = editNote;
window.resetNoteForm = resetNoteForm;
window.submitNoteForm = submitNoteForm;
window.deleteNote = deleteNote;
window.updateCellIndicator = updateCellIndicator;
