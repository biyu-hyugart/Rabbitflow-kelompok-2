import './bootstrap';
import 'bootstrap';
import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;

function applyOldInput() {
    const old = window.rfOldInput || {};
    if (!Object.keys(old).length) {
        return;
    }

    document.querySelectorAll('.rf-modal [name]').forEach((field) => {
        if (field.name.startsWith('_') || !(field.name in old)) {
            return;
        }

        if (field.tagName === 'SELECT') {
            const found = Array.from(field.options).some((option) => option.value === old[field.name]);
            if (found) {
                field.value = old[field.name];
            }

            return;
        }

        if (field.type === 'file') {
            return;
        }

        field.value = old[field.name];
    });
}

function markInvalidFields() {
    const errors = window.rfValidationErrors || {};

    document.querySelectorAll('.rf-modal input[name], .rf-modal select[name], .rf-modal textarea[name]').forEach((field) => {
        const wrapper = field.closest('.rf-field');
        if (!wrapper) {
            return;
        }

        wrapper.querySelectorAll('.rf-error').forEach((node) => node.remove());
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');

        const message = errors[field.name];
        if (!message) {
            return;
        }

        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');

        const hint = document.createElement('span');
        hint.className = 'rf-error';
        hint.setAttribute('role', 'alert');
        hint.textContent = message;
        wrapper.appendChild(hint);
    });
}

function restoreModalAfterValidationError() {
    const meta = document.querySelector('meta[name="rf-open-modal"]');
    if (!meta) {
        return;
    }

    applyOldInput();
    markInvalidFields();

    const modalElement = document.getElementById(meta.content);
    if (!modalElement) {
        return;
    }

    bootstrap.Modal.getOrCreateInstance(modalElement).show();
}

$(function () {
    $('[data-rf-auto-submit]').on('change', function () {
        $(this).closest('form').trigger('submit');
    });

    $('[data-rf-password-toggle]').on('click', function () {
        const input = $($(this).data('rf-password-toggle'));
        if (!input.length) {
            return;
        }

        const reveal = input.attr('type') === 'password';
        input.attr('type', reveal ? 'text' : 'password');
        $(this).find('.material-symbols-outlined').text(reveal ? 'visibility_off' : 'visibility');
    });

    $('[data-rf-demo-username]').on('click', function () {
        $('#username').val($(this).data('rf-demo-username') || '');
        $('#password').val($(this).data('rf-demo-password') || '');
        $('#username').closest('form').trigger('submit');
    });

    $('.rf-modal form').on('submit', function () {
        const actionInput = this.querySelector('[name="_action"]');
        if (actionInput) {
            actionInput.value = this.action;
        }
    });

    $('.rf-modal').on('show.bs.modal', function (event) {
        if (!event.relatedTarget) {
            return;
        }

        const modal = $(this);
        modal.find('.rf-field > .rf-error[role="alert"]').remove();
        modal.find('.is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        const trigger = $(event.relatedTarget);
        if (!trigger.length) {
            return;
        }

        $('#deleteForm').attr('action', trigger.data('delete-action') || '#');
        $('#deleteTarget').text(trigger.data('delete-name') || 'data ini');
        $('#deleteMessage').text(
            trigger.data('delete-message')
            || 'Data yang dihapus tidak dapat dikembalikan. Pastikan tidak ada catatan lain yang bergantung pada data ini.'
        );
    });

    restoreModalAfterValidationError();
});
