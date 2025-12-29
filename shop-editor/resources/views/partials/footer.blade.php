    <div id="toast-container">
        @if (Session::get('success'))
            <div class="app-toast app-toast--success app-toast--visible">
                <i class="fas fa-check-circle app-toast__icon app-toast--success"></i>
                <div>{{ Session::get('success') }}</div>
                <button type="button" class="app-toast__close">&times;</button>
            </div>
        @endif

        @if (Session::get('danger'))
            <div class="app-toast app-toast--danger app-toast--visible">
                <i class="fas fa-times-circle app-toast__icon app-toast--danger"></i>
                <div style="color: rgb(255, 4, 4)">{{ Session::get('danger') }}</div>
                <button type="button" class="app-toast__close">&times;</button>
            </div>
        @endif
    </div>

    <script>
        (function() {
            const toasts = document.querySelectorAll('.app-toast');

            toasts.forEach(toast => {
                setTimeout(() => toast.classList.add('app-toast--visible'), 80);

                const timer = setTimeout(() => {
                    toast.classList.remove('app-toast--visible');
                    setTimeout(() => toast.remove(), 300);
                }, 3500);

                const close = toast.querySelector('.app-toast__close');
                close.addEventListener('click', () => {
                    clearTimeout(timer);
                    toast.classList.remove('app-toast--visible');
                    setTimeout(() => toast.remove(), 200);
                });
            });
        })();
    </script>

    <footer class="footer has-background-light" style="padding: 2rem 1.5rem; margin-top: 3rem;">
        <div class="content has-text-centered">
            <p>
                <strong>MyTee Studio</strong> - Nền tảng thiết kế áo thun trực tuyến.<br>
                Project Laravel Demo.
            </p>
        </div>
    </footer>
