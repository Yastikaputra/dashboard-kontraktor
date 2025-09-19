import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    // --- UI ELEMENTS ---
    const toast = document.getElementById('toast');
    let toastTimeout;
    
    // --- CHART INSTANCES ---
    let expenseChartInstance = null;
    let supplierChartInstance = null;
    
    // --- SIDEBAR TOGGLE ---
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menu-btn');
    const overlay = document.getElementById('sidebar-overlay');

    const toggleSidebar = () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    };

    if (menuBtn) menuBtn.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    // --- GEMINI API CALLER ---
    async function callGeminiAPI(prompt) {
        const apiKey = ""; // API Key will be injected by the environment.
        const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${apiKey}`;

        const payload = {
            contents: [{ parts: [{ text: prompt }] }],
        };

        for (let i = 0; i < 3; i++) {
            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    const result = await response.json();
                    const candidate = result.candidates?.[0];
                    if (candidate && candidate.content?.parts?.[0]?.text) {
                        return candidate.content.parts[0].text;
                    } else {
                        throw new Error('Invalid response structure from API.');
                    }
                }
                await new Promise(resolve => setTimeout(resolve, 1000 * Math.pow(2, i)));
            } catch (error) {
                console.error(`API call attempt ${i + 1} failed:`, error);
                if (i === 2) throw new Error('Gagal mendapatkan respons dari AI setelah beberapa kali percobaan.');
            }
        }
    }

    // --- STATE MANAGEMENT ---
    let projects = JSON.parse(localStorage.getItem('projects')) || [];
    let suppliers = JSON.parse(localStorage.getItem('suppliers')) || [];
    let expenses = JSON.parse(localStorage.getItem('expenses')) || [];
    let bills = JSON.parse(localStorage.getItem('bills')) || [];
    let currentModalType = null;

    // --- SAVE TO LOCALSTORAGE ---
    const saveData = () => {
        localStorage.setItem('projects', JSON.stringify(projects));
        localStorage.setItem('suppliers', JSON.stringify(suppliers));
        localStorage.setItem('expenses', JSON.stringify(expenses));
        localStorage.setItem('bills', JSON.stringify(bills));
    };
    
    // --- NOTIFICATION ---
    const showToast = (message, isSuccess = false) => {
        if (!toast) return;
        if(toastTimeout) clearTimeout(toastTimeout);
        toast.textContent = message;
        toast.classList.remove('bg-red-500', 'bg-yellow-500', 'translate-x-[150%]');
        toast.classList.add(isSuccess ? 'bg-yellow-500' : 'bg-red-500');
        toast.classList.remove('text-white', 'text-black');
        toast.classList.add(isSuccess ? 'text-black' : 'text-white');
        toastTimeout = setTimeout(() => {
            toast.classList.add('translate-x-[150%]');
        }, 3000);
    }

    // --- RENDER CHARTS ---
    const renderExpenseChart = () => {
        const ctxEl = document.getElementById('expenseChart');
        if (!ctxEl) return;
        const ctx = ctxEl.getContext('2d');
        const monthlyExpenses = {};
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];

        expenses.forEach(exp => {
            const month = new Date(exp.date).getMonth();
            const monthName = monthNames[month];
            monthlyExpenses[monthName] = (monthlyExpenses[monthName] || 0) + parseFloat(exp.amount);
        });
        
        if(expenseChartInstance) expenseChartInstance.destroy();

        expenseChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyExpenses),
                datasets: [{
                    label: 'Total Pengeluaran (Rp)',
                    data: Object.values(monthlyExpenses),
                    backgroundColor: 'rgba(250, 204, 21, 0.6)',
                    borderColor: 'rgba(234, 179, 8, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
    };

    const renderSupplierChart = () => {
        const ctxEl = document.getElementById('supplierChart');
        if (!ctxEl) return;
        const ctx = ctxEl.getContext('2d');
        const totalUnpaid = bills.filter(b => b.status === 'unpaid').reduce((sum, bill) => sum + parseFloat(bill.amount), 0);
        const totalPaid = bills.filter(b => b.status === 'paid').reduce((sum, bill) => sum + parseFloat(bill.amount), 0);
        
        if(supplierChartInstance) supplierChartInstance.destroy();

        supplierChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Belum Dibayar', 'Sudah Dibayar'],
                datasets: [{
                    label: 'Jumlah (Rp)',
                    data: [totalUnpaid, totalPaid],
                    backgroundColor: ['rgba(239, 68, 68, 0.7)', 'rgba(17, 24, 39, 0.7)'],
                    borderColor: ['rgba(239, 68, 68, 1)', 'rgba(17, 24, 39, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    };

    // --- RENDER DASHBOARD ---
    const renderDashboard = () => {
        const ongoingProjects = projects.filter(p => p.status === 'ongoing');
        const completedProjects = projects.filter(p => p.status === 'completed');
        const unpaidBills = bills.filter(b => b.status === 'unpaid');
        
        document.getElementById('proyek-berjalan-count').textContent = ongoingProjects.length;
        document.getElementById('proyek-selesai-count').textContent = completedProjects.length;
        document.getElementById('tagihan-belum-bayar-sum').textContent = `Rp. ${unpaidBills.reduce((s, b) => s + parseFloat(b.amount), 0).toLocaleString('id-ID')}`;
        document.getElementById('pengeluaran-bulan-ini-sum').textContent = `Rp. ${expenses.filter(e => new Date(e.date).getMonth() === new Date().getMonth()).reduce((s, e) => s + parseFloat(e.amount), 0).toLocaleString('id-ID')}`;

        const projectListContainer = document.getElementById('proyek-aktif-list');
        projectListContainer.innerHTML = ongoingProjects.length ? ongoingProjects.map(proj => `
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-zinc-50 rounded-lg gap-2">
                <div class="flex-grow min-w-0"><p class="font-semibold text-zinc-800 break-words">${proj.name}</p><p class="text-sm text-zinc-500">PM: ${proj.manager} | Lokasi: ${proj.location}</p></div>
                <div class="flex items-center gap-2 flex-shrink-0"><p class="font-semibold text-zinc-600 text-right">Rp. ${parseFloat(proj.value).toLocaleString('id-ID')}</p><button class="generate-tasks-btn text-xs bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-3 py-1 rounded-md flex items-center gap-1" data-id="${proj.id}"><span>✨</span> Rincian</button><button class="complete-project-btn text-xs bg-black hover:bg-zinc-800 text-white px-3 py-1 rounded-md" data-id="${proj.id}">Selesai</button></div>
            </div>`).join('') : `<p class="text-center text-zinc-400">Belum ada proyek aktif.</p>`;

        const billListContainer = document.getElementById('tagihan-reminder-list');
        billListContainer.innerHTML = unpaidBills.length ? unpaidBills.map(bill => {
            const ui = bill.type === 'supplier' ? {bg: 'bg-red-50', text: 'text-red-800', sub: 'text-red-500', amount: 'text-red-600', badge: '<span class="text-xs font-semibold bg-red-200 text-red-800 px-2 py-0.5 rounded-full">Supplier</span>'} : {bg: 'bg-yellow-50', text: 'text-yellow-800', sub: 'text-yellow-500', amount: 'text-yellow-600', badge: '<span class="text-xs font-semibold bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full">Tukang</span>'};
            return `<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 ${ui.bg} rounded-lg gap-2"><div class="flex-grow min-w-0"><div class="flex items-center gap-2 mb-1">${ui.badge}<p class="font-semibold ${ui.text} break-words">${bill.description}</p></div><p class="text-sm ${ui.sub} ml-2">Jatuh Tempo: ${bill.dueDate}</p></div><div class="flex items-center gap-4 flex-shrink-0"><p class="font-semibold ${ui.amount} text-right">Rp. ${parseFloat(bill.amount).toLocaleString('id-ID')}</p><button class="pay-bill-btn text-xs bg-black hover:bg-zinc-800 text-white px-3 py-1 rounded-md" data-id="${bill.id}">Bayar</button></div></div>`;
        }).join('') : `<p class="text-center text-zinc-400">Tidak ada tagihan jatuh tempo.</p>`;

        const supplierListContainer = document.getElementById('supplier-list');
        supplierListContainer.innerHTML = suppliers.length ? suppliers.map(sup => `<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-zinc-50 rounded-lg gap-2"><div class="flex-grow min-w-0"><p class="font-semibold text-zinc-800 break-words">${sup.name}</p></div><div class="flex-shrink-0"><p class="text-sm text-zinc-600 w-full sm:w-auto text-left sm:text-right">${sup.contact}</p></div></div>`).join('') : `<p class="text-center text-zinc-400">Belum ada data supplier.</p>`;

        const laporanContainer = document.getElementById('laporan-keuangan-list');
        const totalRevenue = completedProjects.reduce((s, p) => s + parseFloat(p.value), 0);
        const totalSpending = expenses.reduce((s, e) => s + parseFloat(e.amount), 0) + bills.filter(b => b.status === 'paid').reduce((s, b) => s + parseFloat(b.amount), 0);
        const profit = totalRevenue - totalSpending;
        laporanContainer.innerHTML = `<div class="flex justify-between items-center p-3 bg-green-50 rounded-lg"><p class="font-medium text-green-800">Total Pemasukan</p><p class="font-bold text-green-600">Rp. ${totalRevenue.toLocaleString('id-ID')}</p></div><div class="flex justify-between items-center p-3 bg-red-50 rounded-lg"><p class="font-medium text-red-800">Total Pengeluaran</p><p class="font-bold text-red-600">Rp. ${totalSpending.toLocaleString('id-ID')}</p></div><div class="flex justify-between items-center p-3 ${profit >= 0 ? 'bg-blue-50' : 'bg-gray-50'} rounded-lg"><p class="font-medium ${profit >= 0 ? 'text-blue-800' : 'text-gray-800'}">Profit/Loss</p><p class="font-bold ${profit >= 0 ? 'text-blue-600' : 'text-gray-600'}">Rp. ${profit.toLocaleString('id-ID')}</p></div>`;

        renderExpenseChart();
        renderSupplierChart();
    };
    
    // --- MODAL & ACTION LOGIC ---
    const modal = document.getElementById('modal-template');
    const modalTitle = modal.querySelector('.modal-title');
    const modalBody = modal.querySelector('.modal-body');
    const modalFooter = modal.querySelector('.modal-footer');
    const loadingSpinner = `<div class="flex justify-center items-center h-24"><div class="spinner w-12 h-12 rounded-full border-4 border-gray-200"></div></div>`;

    const openModal = () => modal.classList.remove('pointer-events-none', 'opacity-0');
    const closeModal = () => modal.classList.add('pointer-events-none', 'opacity-0');

    modal.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-overlay') || e.target.closest('.modal-close') || e.target.closest('.modal-close-btn')) {
            closeModal();
        }
    });

    const setupModalForAI = (title) => {
        modalTitle.textContent = title;
        modalBody.innerHTML = loadingSpinner;
        modalFooter.innerHTML = `<button class="modal-close-btn px-4 bg-gray-200 p-3 rounded-lg text-black hover:bg-gray-300">Tutup</button>`;
        openModal();
    };

    const updateModalWithAIResponse = (htmlContent) => modalBody.innerHTML = htmlContent;

    const setupFormModal = (title, form, type) => {
        currentModalType = type;
        modalTitle.textContent = title;
        modalBody.innerHTML = form;
        modalFooter.innerHTML = `<button class="modal-close-btn px-4 bg-transparent p-3 rounded-lg text-zinc-500 hover:bg-zinc-100 mr-2">Batal</button><button id="save-data-btn" class="px-4 bg-yellow-400 p-3 rounded-lg text-black hover:bg-yellow-500 font-semibold">Simpan</button>`;
        openModal();
        if (window.innerWidth < 768) toggleSidebar();
    };

    const formTemplates = {
        project: `<form><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="namaProyek">Nama Proyek</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="namaProyek" type="text" placeholder="e.g. Pembangunan Gedung A" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="nilaiTender">Nilai Tender (Rp)</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nilaiTender" type="number" placeholder="200000000" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="tanggalMulai">Tanggal Mulai</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tanggalMulai" type="date" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="projectManager">Project Manager</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="projectManager" type="text" placeholder="e.g. Budi" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="lokasi" type="text" placeholder="e.g. Jakarta" required></div></form>`,
        supplier: `<form><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="namaSupplier">Nama Supplier</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="namaSupplier" type="text" placeholder="e.g. PT Baja Kuat" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="kontak">No. Telepon</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="kontak" type="text" placeholder="08123456789" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="jumlahTagihan">Jumlah Tagihan Awal (Rp)</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jumlahTagihan" type="number" placeholder="50000000" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="jatuhTempo">Jatuh Tempo</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jatuhTempo" type="date" required></div></form>`,
        expense: `<form><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="namaToko">Nama Toko</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="namaToko" type="text" placeholder="e.g. Toko Bangunan Jaya" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="jumlahJenisBarang">Jumlah dan Jenis Barang</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jumlahJenisBarang" type="text" placeholder="10 sak semen, 5m pipa" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="totalHarga">Total Harga (Rp)</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="totalHarga" type="number" placeholder="1500000" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="kota">Masukan Kota</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="kota" type="text" placeholder="e.g. Bandung" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="tglPengeluaran">Tanggal</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tglPengeluaran" type="date" required></div></form>`,
        tukang: `<form><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="namaTukang">Nama Tukang</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="namaTukang" type="text" placeholder="e.g. Budi (Tukang Cat)" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="jumlahTagihan">Jumlah (Rp)</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jumlahTagihan" type="number" placeholder="3000000" required></div><div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2" for="jatuhTempo">Jatuh Tempo</label><input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jatuhTempo" type="date" required></div></form>`
    };

    document.getElementById('tambahProyekBtn')?.addEventListener('click', e => { e.preventDefault(); setupFormModal("Tambah Proyek Baru", formTemplates.project, 'project'); });
    document.getElementById('tambahSupplierBtn')?.addEventListener('click', e => { e.preventDefault(); setupFormModal("Tambah Supplier & Tagihan", formTemplates.supplier, 'supplier'); });
    document.getElementById('tambahPengeluaranBtn')?.addEventListener('click', e => { e.preventDefault(); setupFormModal("Tambah Pengeluaran", formTemplates.expense, 'expense'); });
    document.getElementById('tambahTukangBtn')?.addEventListener('click', e => { e.preventDefault(); setupFormModal("Pembayaran Tukang", formTemplates.tukang, 'tukang'); });
    document.getElementById('resetDataBtn')?.addEventListener('click', e => {
        e.preventDefault();
        currentModalType = 'reset';
        modalTitle.textContent = 'Konfirmasi Reset Data';
        modalBody.innerHTML = '<p>Anda yakin ingin menghapus semua data? Tindakan ini tidak dapat diurungkan.</p>';
        modalFooter.innerHTML = `<button class="modal-close-btn px-4 bg-transparent p-3 rounded-lg text-zinc-500 hover:bg-zinc-100 mr-2">Batal</button><button id="save-data-btn" class="px-4 bg-red-500 p-3 rounded-lg text-white hover:bg-red-600 font-semibold">Ya, Reset</button>`;
        openModal();
    });

    modal.addEventListener('click', (e) => {
        if (e.target.id !== 'save-data-btn') return;
        if (currentModalType === 'reset') {
            localStorage.clear();
            [projects, suppliers, expenses, bills] = [[], [], [], []];
            renderDashboard();
            closeModal();
            showToast('Semua data telah direset.', true);
            return;
        }

        const formInputs = modalBody.querySelectorAll('input');
        let isValid = Array.from(formInputs).every(input => {
            const valid = !!input.value;
            input.classList.toggle('border-red-500', !valid);
            return valid;
        });
        if (!isValid) return showToast('Harap isi semua kolom.');

        const data = Object.fromEntries(new FormData(modalBody.querySelector('form')).entries());

        if (currentModalType === 'project') projects.push({ id: Date.now(), name: data.namaProyek, value: data.nilaiTender, startDate: data.tanggalMulai, manager: data.projectManager, location: data.lokasi, status: 'ongoing' });
        else if (currentModalType === 'supplier') {
            suppliers.push({ name: data.namaSupplier, contact: data.kontak });
            bills.push({ id: Date.now(), description: `Tagihan dari ${data.namaSupplier}`, amount: data.jumlahTagihan, dueDate: data.jatuhTempo, status: 'unpaid', type: 'supplier' });
        } else if (currentModalType === 'expense') expenses.push({ store: data.namaToko, items: data.jumlahJenisBarang, amount: data.totalHarga, city: data.kota, date: data.tglPengeluaran });
        else if (currentModalType === 'tukang') bills.push({ id: Date.now(), description: data.namaTukang, amount: data.jumlahTagihan, dueDate: data.jatuhTempo, status: 'unpaid', type: 'tukang' });

        saveData();
        renderDashboard();
        closeModal();
    });
    
    document.body.addEventListener('click', async (e) => {
        const target = e.target.closest('button');
        if (!target) return;
    
        const projectId = target.dataset.id;
    
        if (target.classList.contains('complete-project-btn')) {
            const project = projects.find(p => p.id == projectId);
            if (project) {
                project.status = 'completed';
                saveData();
                renderDashboard();
                showToast('Proyek ditandai selesai.', true);
            }
        } else if (target.classList.contains('pay-bill-btn')) {
            const bill = bills.find(b => b.id == projectId);
            if (bill) {
                bill.status = 'paid';
                saveData();
                renderDashboard();
                showToast('Tagihan telah dibayar.', true);
            }
        } else if (target.classList.contains('generate-tasks-btn')) {
            const project = projects.find(p => p.id == projectId);
            if (project) {
                setupModalForAI(`Rincian Tugas: ${project.name}`);
                const prompt = `Anda adalah seorang manajer proyek konstruksi. Berikan daftar tugas umum dalam format bullet points (gunakan tanda *) untuk proyek: "${project.name}".`;
                try {
                    const result = await callGeminiAPI(prompt);
                    updateModalWithAIResponse('<ul>' + result.split('\n').filter(l => l.trim().startsWith('*')).map(l => `<li class="ml-4 list-disc py-1">${l.trim().substring(1).trim()}</li>`).join('') + '</ul>');
                } catch (error) {
                    updateModalWithAIResponse(`<p class="text-red-500">${error.message}</p>`);
                }
            }
        } else if (target.id === 'financial-analysis-btn') {
            const totalRevenue = projects.filter(p => p.status === 'completed').reduce((s, p) => s + parseFloat(p.value), 0);
            const totalSpending = expenses.reduce((s, e) => s + parseFloat(e.amount), 0) + bills.filter(b => b.status === 'paid').reduce((s, b) => s + parseFloat(b.amount), 0);
            
            setupModalForAI("Analisis Keuangan Cerdas");
            const prompt = `Anda adalah seorang penasihat keuangan. Berdasarkan data: Pemasukan: Rp ${totalRevenue.toLocaleString('id-ID')}, Pengeluaran: Rp ${totalSpending.toLocaleString('id-ID')}, berikan analisis singkat dan satu saran praktis dalam satu paragraf.`;
            try {
                const result = await callGeminiAPI(prompt);
                updateModalWithAIResponse(`<p>${result.replace(/\n/g, '<br>')}</p>`);
            } catch (error) {
                updateModalWithAIResponse(`<p class="text-red-500">${error.message}</p>`);
            }
        }
    });

    renderDashboard();
});
