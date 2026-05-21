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
    /**
     * URL preview via Laravel (disarankan — /storage/ sering 403 di server produksi).
     * Route: GET /admin/assessment/{id}/view-pdf
     */
    window.adminAssessmentPdfUrl = function (penilaianId) {
        if (window.ADMIN_PDF_VIEW_ROUTE_TEMPLATE) {
            return window.ADMIN_PDF_VIEW_ROUTE_TEMPLATE.replace('__ID__', String(penilaianId));
        }
        return '/admin/assessment/' + penilaianId + '/view-pdf';
    };

    /** URL /storage/... — hanya untuk lingkungan yang mengizinkan akses file publik */
    window.adminStoragePdfUrl = function (relativePath) {
        if (!relativePath) {
            return '';
        }
        const base = (window.ADMIN_STORAGE_BASE || '/storage').replace(/\/$/, '');
        return base + '/' + String(relativePath).replace(/^\/+/, '');
    };

    function adminRevokePdfBlob(content) {
        if (!content || !content._adminPdfBlobUrl) {
            return;
        }
        try {
            URL.revokeObjectURL(content._adminPdfBlobUrl);
        } catch (e) { /* ignore */ }
        content._adminPdfBlobUrl = null;
    }

    /** Parameter PDF Open (fallback iframe — toolbar browser). */
    function adminPdfViewerSrc(url) {
        if (!url) {
            return '';
        }
        const base = String(url).split('#')[0];
        return base + '#toolbar=0&navpanes=0&scrollbar=1&statusbar=0';
    }

    function adminMountPdfIframe(content, src, isBlob) {
        content.querySelectorAll('iframe, embed, object, .admin-pdf-js-scroll').forEach(function (el) {
            el.remove();
        });
        const viewer = document.createElement('iframe');
        viewer.className = 'admin-pdf-preview-iframe';
        viewer.setAttribute('title', 'Preview PDF');
        viewer.src = adminPdfViewerSrc(src);
        content.appendChild(viewer);
        if (isBlob) {
            content._adminPdfBlobUrl = String(src).split('#')[0];
        }
    }

    /** Render PDF tanpa toolbar browser (PDF.js → canvas). */
    function adminMountPdfViewer(content, src, isBlob) {
        adminRevokePdfBlob(content);
        content.querySelectorAll('iframe, embed, object, .admin-pdf-js-scroll').forEach(function (el) {
            el.remove();
        });

        if (isBlob) {
            content._adminPdfBlobUrl = String(src).split('#')[0];
        }

        if (!window.pdfjsLib) {
            adminMountPdfIframe(content, src, isBlob);
            return;
        }

        const scroll = document.createElement('div');
        scroll.className = 'admin-pdf-js-scroll';
        scroll.setAttribute('aria-label', 'Preview PDF');
        content.appendChild(scroll);

        const task = pdfjsLib.getDocument(src);
        task.promise
            .then(function (pdf) {
                const containerWidth = content.clientWidth || scroll.clientWidth || 800;
                const chain = [];
                for (let n = 1; n <= pdf.numPages; n++) {
                    chain.push(
                        pdf.getPage(n).then(function (page) {
                            const base = page.getViewport({ scale: 1 });
                            const scale = Math.min(2, Math.max(0.5, (containerWidth - 24) / base.width));
                            const viewport = page.getViewport({ scale: scale });
                            const canvas = document.createElement('canvas');
                            canvas.className = 'admin-pdf-js-page';
                            const ctx = canvas.getContext('2d');
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;
                            scroll.appendChild(canvas);
                            return page.render({ canvasContext: ctx, viewport: viewport }).promise;
                        })
                    );
                }
                return Promise.all(chain);
            })
            .catch(function () {
                scroll.remove();
                adminMountPdfIframe(content, src, isBlob);
            });
    }

    /**
     * Tampilkan PDF di modal — fetch blob lalu iframe (andalan di produksi).
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
        if (!pdfUrl && opts.penilaianId) {
            pdfUrl = adminAssessmentPdfUrl(opts.penilaianId);
        }
        if (!pdfUrl && opts.storagePath) {
            pdfUrl = adminStoragePdfUrl(opts.storagePath);
        }

        if (!pdfUrl) {
            content.innerHTML = '<p class="admin-pdf-preview-error">URL PDF tidak tersedia.</p>';
            return;
        }

        if (openTab) {
            openTab.href = pdfUrl;
        }

        adminRevokePdfBlob(content);
        content.innerHTML = '';

        const loader = document.createElement('div');
        loader.className = 'admin-pdf-preview-loading';
        loader.textContent = 'Memuat PDF...';
        content.appendChild(loader);

        const hideLoader = function () {
            if (loader.parentNode) {
                loader.remove();
            }
        };

        const showError = function (message) {
            hideLoader();
            adminRevokePdfBlob(content);
            content.innerHTML = '<p class="admin-pdf-preview-error">' + message + '</p>';
        };

        if (modal.classList.contains('admin-modal') && typeof adminOpenModal === 'function') {
            adminOpenModal(modal);
        } else {
            modal.classList.remove('hidden');
            document.body.classList.add('admin-modal-open');
        }

        const loaderFallback = window.setTimeout(hideLoader, 3500);

        fetch(pdfUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { Accept: 'application/pdf' },
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('http-' + res.status);
                }
                return res.blob();
            })
            .then(function (blob) {
                window.clearTimeout(loaderFallback);
                if (!blob || blob.size < 32) {
                    throw new Error('empty');
                }
                if (blob.type && blob.type.indexOf('text/html') !== -1) {
                    throw new Error('html');
                }
                const blobUrl = URL.createObjectURL(blob);
                hideLoader();
                adminMountPdfViewer(content, blobUrl, true);
            })
            .catch(function (err) {
                window.clearTimeout(loaderFallback);
                hideLoader();
                if (err && String(err.message).indexOf('http-403') !== -1) {
                    showError('Akses PDF ditolak (403). Gunakan tombol &quot;Buka di Tab Baru&quot;.');
                    return;
                }
                if (err && String(err.message).indexOf('http-404') !== -1) {
                    showError('File PDF tidak ditemukan. Pastikan file sudah diunggah.');
                    return;
                }
                adminMountPdfViewer(content, pdfUrl, false);
            });
    };

    window.adminClosePdfPreview = function (modalId) {
        const modal = document.getElementById(modalId || 'pdfPreviewModal');
        const contentIds = ['pdfPreviewContent', 'pdfViewerContent'];
        contentIds.forEach(function (id) {
            const c = document.getElementById(id);
            if (c) {
                adminRevokePdfBlob(c);
            }
        });
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
