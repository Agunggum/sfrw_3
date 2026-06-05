const { html, render } = window.lit;
const __ = window.__ || ((id, def) => def || id);

const welcomeTemplate = (data) => html`
<div class="d-flex justify-content-center">
                        
    <div class="col-md-8 col-lg-8 col-xl-8">
        <p class="h3 text-left font-weight-bold">${__('id-masuk', 'Masuk')}.</p>
                                
            <form class="m-t" role="form" method="post" action="${data.baseurl}authlogin">
                <input type="hidden" name="login" value="MASUK">
                <!-- Email input -->
                ${data.username}

                <!-- Password input -->
                ${data.password}

                <!-- 2 column grid layout for inline styling -->
                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        ${data.formcheck}
                    </div>

                    <div class="col">
                        <!-- Simple link -->
                        <a href="${data.linkforgot}">${__('id-lupa-password', 'Lupa kata sandi?')}</a>
                    </div>
                </div>

                <!-- Submit button -->
                <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-primary btn-block mb-4">${__('id-masuk', 'Masuk')}</button>

                <!-- Register buttons -->
                <div class="text-center">
                    <p>${__('id-bukan-anggota', 'Bukan anggota?')} <a href="${data.linkregister}">${__('id-daftar', 'Daftar')}</a></p>
                    <p>${__('id-atau-daftar-dengan', 'atau masuk dengan')}:</p>
                    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-google"></i>
                    </button>
                </div>
            </form>
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