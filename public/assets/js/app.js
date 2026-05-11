// ===== Hospital Ticketing System - Common JS =====

const App = {
    baseUrl: $('meta[name="base-url"]').attr('content') || '/',

    // Show toast notification
    toast: function(message, type = 'success') {
        const icons = { success: 'bi-check-circle', error: 'bi-exclamation-circle', warning: 'bi-exclamation-triangle', info: 'bi-info-circle' };
        const colors = { success: '#10b981', error: '#ef4444', warning: '#f59e0b', info: '#0ea5e9' };
        const id = 'toast-' + Date.now();
        const html = `
            <div id="${id}" class="alert-toast" style="border-left: 4px solid ${colors[type]}; background: #1e293b; color: #f1f5f9; padding: 14px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                <i class="bi ${icons[type]}" style="font-size: 20px; color: ${colors[type]}"></i>
                <span style="flex: 1; font-size: 14px;">${message}</span>
                <button type="button" class="btn-close btn-close-white" style="font-size: 10px;" onclick="$('#${id}').fadeOut(300, function(){ $(this).remove(); })"></button>
            </div>`;
        $('body').append(html);
        setTimeout(() => { $('#' + id).fadeOut(300, function() { $(this).remove(); }); }, 4000);
    },

    // Confirm dialog
    confirm: function(message, callback) {
        if (confirm(message)) { callback(); }
    },

    // AJAX helper
    ajax: function(url, options = {}) {
        const defaults = {
            url: url,
            type: options.method || 'GET',
            dataType: 'json',
            data: options.data || {},
            beforeSend: function() {
                if (options.loading) { $(options.loading).prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Loading...'); }
            },
            success: function(response) {
                if (options.success) options.success(response);
            },
            error: function(xhr) {
                App.toast('An error occurred. Please try again.', 'error');
                if (options.error) options.error(xhr);
            },
            complete: function() {
                if (options.loading) { $(options.loading).prop('disabled', false).html(options.loadingText || 'Submit'); }
                if (options.complete) options.complete();
            }
        };
        $.ajax(defaults);
    },

    // Format time
    formatTime: function(datetime) {
        if (!datetime) return '-';
        const d = new Date(datetime);
        return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    },

    // Animated counter
    animateCounter: function(element, target) {
        const $el = $(element);
        const start = parseInt($el.text()) || 0;
        const duration = 1000;
        const startTime = Date.now();
        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(start + (target - start) * eased);
            $el.text(current);
            if (progress < 1) requestAnimationFrame(animate);
        };
        animate();
    }
};

// Spinning animation for loading
$('<style>.spin{animation:spin 1s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}</style>').appendTo('head');
