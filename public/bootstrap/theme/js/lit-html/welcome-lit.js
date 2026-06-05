const { html, render } = window.lit;
const __ = window.__ || ((id, def) => def || id);

// Lit-HTML template secara otomatis membaca data dari PHP (window.pageData)
const welcomeTemplate = (data) => html`
<div class="card border-0 shadow-sm bg-danger bg-opacity-10 p-1 mb-4">
    <div class="d-flex align-items-center">
        <img src="${data.image}" alt="Gambar Login" class="img-fluid w-50 rounded-3">
        <div class="m-4">
            <h5 class="mb-1">
                <i class="bi bi-lightning-charge-fill fs-3 me-3"></i>
                <span class="title-class">${__('en-lit-html-engine-active', 'Lit-HTML Engine Aktif')}</span></h5>
                <p class="mb-0 small">
                <span>${__('id-halo', 'Halo')}</span> <strong>${data.user}</strong>, <span>${__('id-anda-menggunakan', 'Anda menggunakan')}</span> <strong>${data.title}</strong> <span>${__('id-versi', 'versi')}</span> ${data.version}.
                <br/>Server Time: <span class="badge bg-light text-dark">${data.server_time}</span>
            </p>
            <div class="row mt-3">
                <div class="col-lg-6 pb-2">
                    <div>
                        <span>${__('id-sfrw-adalah-framework-dari-indonesia', 'sfrw adalah framework dari')}</span> <font class="text-danger">indonesia</font> <span>${__('id-yang-dikembangkan-oleh-indonesia-untuk-programer-atau-calon-programer', 'yang dikembangkan oleh indonesia untuk programer atau calon programer')}</span> <font class="text-danger">indonesia</font></p>
                    </div>
                </div>
                <div class="col-lg-6 pb-2">
                    <div>
                        <p class="card-text"><span>${__('id-pelajari-lebih-lebih-lanjut', 'Pelajari lebih lanjut.')}</span></p>
                        <a href="https://documentation.agunggum.id/" target="_blank" class="btn btn-light rounded-3"><span>${__('id-dokumentasi', 'dokumentasi')}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`;

// Fungsi render utama
const renderApp = () => {
    const container = document.getElementById('lit-app');
    if (container) {
        render(welcomeTemplate(window.pageData || {}), container);
    }
};

// Jalankan render awal
renderApp();

// Dengarkan perubahan bahasa untuk re-render otomatis
document.addEventListener('lang:changed', renderApp);
document.addEventListener('spa:content-loaded', renderApp);