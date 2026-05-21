/**
 * Utilitas JS admin — delegasi klik aman & inisialisasi setelah DOM siap.
 */
(function () {
    'use strict';

    window.adminOnReady = function (fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    };

    /** Delegasi klik: cocokkan elemen atau anak di dalamnya (pakai closest). */
    window.adminDelegateClick = function (selector, callback) {
        document.addEventListener('click', function (e) {
            const el = e.target.closest(selector);
            if (el) {
                callback(el, e);
            }
        });
    };

    window.adminDelegateChange = function (selector, callback) {
        document.addEventListener('change', function (e) {
            const el = e.target.closest(selector);
            if (el) {
                callback(el, e);
            }
        });
    };

    window.adminCsrfToken = function () {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    /** Konfirmasi dengan SweetAlert2, fallback ke confirm() native. */
    window.adminConfirm = function (options) {
        if (window.Swal) {
            return Swal.fire(options);
        }
        const message = typeof options === 'string'
            ? options
            : (options.title || 'Konfirmasi') + (options.text ? '\n' + options.text : '');
        const ok = window.confirm(message);
        return Promise.resolve({ isConfirmed: ok, isDismissed: !ok });
    };

    window.adminBindClick = function (selector, handler) {
        adminOnReady(function () {
            document.querySelectorAll(selector).forEach(function (el) {
                el.addEventListener('click', handler);
            });
        });
    };

    window.adminSafeBind = function (id, event, handler) {
        adminOnReady(function () {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener(event, handler);
            }
        });
    };

    /** Buka/tutup modal admin (di luar elemen hidden parent). */
    window.adminOpenModal = function (id) {
        const el = typeof id === 'string' ? document.getElementById(id) : id;
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('is-open');
        el.setAttribute('aria-hidden', 'false');
        document.body.classList.add('admin-modal-open');
    };

    window.adminCloseModal = function (id) {
        const el = typeof id === 'string' ? document.getElementById(id) : id;
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('is-open');
        el.setAttribute('aria-hidden', 'true');
        if (!document.querySelector('.admin-modal.is-open')) {
            document.body.classList.remove('admin-modal-open');
        }
    };

    /**
     * URL preview PDF assessment (by penilaian ID — tanpa encode path).
     * Template diset dari layout admin: ADMIN_PDF_VIEW_ROUTE_TEMPLATE
     */
    /** URL file PDF langsung dari folder storage (disarankan untuk iframe). */
    window.adminStoragePdfUrl = function (relativePath) {
        if (!relativePath) {
            return '';
        }
        const base = (window.ADMIN_STORAGE_BASE || '/storage').replace(/\/$/, '');
        return base + '/' + String(relativePath).replace(/^\/+/, '');
    };

    /** Fallback: route controller jika storage URL tidak dipakai */
    window.adminAssessmentPdfUrl = function (penilaianId) {
        if (window.ADMIN_PDF_VIEW_ROUTE_TEMPLATE) {
            return window.ADMIN_PDF_VIEW_ROUTE_TEMPLATE.replace('__ID__', String(penilaianId));
        }
        return '/admin/assessment/' + penilaianId + '/view-pdf';
    };

    /**
     * Tampilkan PDF di modal (satu iframe, mengisi container preview).
     */
    window.adminShowPdfPreview = function (options) {
        const opts = options || {};
        const modal = document.getElementById(opts.modalId || 'pdfPreviewModal');
        const content = document.getElementById(opts.contentId || 'pdfPreviewContent');
        const openTab = opts.openTabId ? document.getElementById(opts.openTabId) : null;

        if (!modal || !content) {
            return;
        }

        let pdfUrl = opts.pdfUrl || '';
        if (!pdfUrl && opts.storagePath) {
            pdfUrl = adminStoragePdfUrl(opts.storagePath);
        }
        if (!pdfUrl && opts.penilaianId) {
            pdfUrl = adminAssessmentPdfUrl(opts.penilaianId);
        }

        if (!pdfUrl) {
            content.innerHTML = '<p class="admin-pdf-preview-error">URL PDF tidak tersedia.</p>';
            return;
        }

        // Tab baru: gunakan URL yang sama (file storage atau route)
        if (openTab) {
            openTab.href = pdfUrl;
        }

        if (modal.classList.contains('admin-modal') && typeof adminOpenModal === 'function') {
            adminOpenModal(modal);
        } else {
            modal.classList.remove('hidden');
            document.body.classList.add('admin-modal-open');
        }

        content.innerHTML = '';

        const loader = document.createElement('div');
        loader.className = 'admin-pdf-preview-loading';
        loader.setAttribute('data-pdf-loader', '1');
        loader.textContent = 'Memuat PDF...';

        const iframe = document.createElement('iframe');
        iframe.className = 'admin-pdf-preview-iframe';
        iframe.setAttribute('title', 'Preview PDF');

        let finished = false;
        const hideLoader = function () {
            if (finished) {
                return;
            }
            finished = true;
            const el = content.querySelector('[data-pdf-loader]');
            if (el) {
                el.remove();
            }
        };

        iframe.addEventListener('load', hideLoader);
        iframe.addEventListener('error', function () {
            finished = true;
            content.innerHTML = '<p class="admin-pdf-preview-error">Gagal memuat PDF. Pastikan <code>php artisan storage:link</code> sudah dijalankan, atau gunakan &quot;Buka di Tab Baru&quot;.</p>';
        });

        content.appendChild(iframe);
        content.appendChild(loader);

        // URL file langsung (/storage/...) — tanpa hash agar browser tidak bingung
        iframe.src = pdfUrl;

        // PDF di iframe sering tidak memicu load; sembunyikan loader setelah delay singkat
        setTimeout(hideLoader, 600);
    };

    window.adminClosePdfPreview = function (modalId) {
        const modal = document.getElementById(modalId || 'pdfPreviewModal');
        if (!modal) {
            return;
        }
        if (modal.classList.contains('admin-modal') && typeof adminCloseModal === 'function') {
            adminCloseModal(modal);
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('admin-modal-open');
        }
    };
})();
