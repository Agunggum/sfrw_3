const { html, render } = window.lit;

const welcomeTemplate = (data) => html`
<div class="d-flex justify-content-center">
                        
    <div class="col-md-8 col-lg-8 col-xl-8">
        <p class="h3 text-left font-weight-bold">Sign In.</p>
                                
            <form class="m-t" role="form" method="post" action="<?php echo BASEURL.'authlogin'; ?>">
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
                        <a href="${data.linkforgot}">Forgot password?</a>
                    </div>
                </div>

                <!-- Submit button -->
                <button type="submit" data-mdb-button-init data-mdb-ripple-init data-bs-theme="light" class="btn btn-primary btn-block mb-4">Sign in</button>

                <!-- Register buttons -->
                <div class="text-center">
                    <p>Not a member? <a href="${data.linkregister}">Register</a></p>
                    <p>or sign up with:</p>
                    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-google"></i>
                    </button>
                </div>
            </form>
    </div>
</div>
`;

// Render ke elemen
render(welcomeTemplate(window.pageData), document.getElementById('lit-app'));