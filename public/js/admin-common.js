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
})();
