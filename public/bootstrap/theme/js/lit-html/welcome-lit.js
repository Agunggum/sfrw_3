const { html, render } = window.lit;
                    
// Lit-HTML template secara otomatis membaca data dari PHP (window.pageData)
const welcomeTemplate = (data) => html`
<div class="card border-0 shadow-sm bg-info bg-opacity-10 text-info p-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-lightning-charge-fill fs-3 me-3"></i>
        <div>
            <h5 class="mb-1"><span id="en-lit-html-engine-active" class="title-class" data-lang-id="en-lit-html-engine-active">Lit-HTML Engine Aktif</span></h5>
                <p class="mb-0 small">
                <span id="id-halo" class="title-class" data-lang-id="id-halo">Halo</span> <strong>${data.user}</strong>, <span id="id-anda-menggunakan" class="title-class" data-lang-id="id-anda-menggunakan">Anda menggunakan</span> <strong>${data.title}</strong> <span id="id-versi" class="title-class" data-lang-id="id-versi">versi</span> ${data.version}.
                <br/>Server Time: <span class="badge bg-info">${data.server_time}</span>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 pb-2">
        <div class="card">
            <div class="card-body">
                <p class="card-text"><span id="id-sfrw-adalah-framework-dari-indonesia" class="title-class" data-lang-id="id-sfrw-adalah-framework-dari-indonesia">sfrw adalah framework dari</span> <font class="text-danger">indonesia</font> <span id="id-yang-dikembangkan-oleh-indonesia-untuk-programer-atau-calon-programer" class="title-class" data-lang-id="id-yang-dikembangkan-oleh-indonesia-untuk-programer-atau-calon-programer">yang dikembangkan oleh indonesia untuk programer atau calon programer</span> <font class="text-danger">indonesia</font></p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 pb-2">
        <div class="card">
            <div class="card-body">
                <p class="card-text"><span id="id-pelajari-lebih-lebih-lanjut" class="title-class" data-lang-id="id-pelajari-lebih-lebih-lanjut">Pelajari lebih lanjut.</span></p>
                <a href="https://documentation.agunggum.id/" target="_blank" class="btn btn-outline-info rounded-3"><span id="id-dokumentasi" class="title-class" data-lang-id="id-dokumentasi">dokumentasi</span></a>
            </div>
        </div>
    </div>
</div>
`;

// Render ke elemen
render(welcomeTemplate(window.pageData), document.getElementById('lit-app'));