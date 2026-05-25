const { html, render } = window.lit;
                    
// Lit-HTML template secara otomatis membaca data dari PHP (window.pageData)
const welcomeTemplate = (data) => html`
<div class="card border-0 shadow-sm bg-info bg-opacity-10 text-info p-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-lightning-charge-fill fs-3 me-3"></i>
        <div>
            <h5 class="mb-1">Lit-HTML Engine Active</h5>
                <p class="mb-0 small">
                Halo <strong>${data.user}</strong>, Anda menggunakan <strong>${data.title}</strong> versi ${data.version}.
                <br/>Server Time: <span class="badge bg-info">${data.server_time}</span>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 pb-2">
        <div class="card">
            <div class="card-body">
                <p class="card-text">sfrw adalah framework <font class="text-danger">indonesia</font> yang dikembangkan oleh indonesia untuk programer atau calon programer <font class="text-danger">indonesia</font></p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 pb-2">
        <div class="card">
            <div class="card-body">
                <p class="card-text">Pelajari lebih lanjut.</p>
                <a href="https://documentation.agunggum.id/" target="_blank" class="btn btn-outline-info">dokumentasi</a>
            </div>
        </div>
    </div>
</div>
`;

// Render ke elemen
render(welcomeTemplate(window.pageData), document.getElementById('lit-app'));