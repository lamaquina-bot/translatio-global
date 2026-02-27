/**
 * Forms Module - Translatio Global Theme
 * Handles form validation and AJAX submission for Contact Form 7
 */

(function() {
    'use strict';

    class Forms {
        constructor() {
            this.forms = document.querySelectorAll('.wpcf7-form, .contact-form');
            this.validationRules = {
                required: (value) => value.trim() !== '',
                email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                phone: (value) => /^[\d\s\-\+\(\)]{7,20}$/.test(value),
                minLength: (value, length) => value.length >= length,
                maxLength: (value, length) => value.length <= length,
                name: (value) => /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/.test(value)
            };
            
            this.errorMessages = {
                required: 'Este campo es obligatorio',
                email: 'Por favor ingresa un email válido',
                phone: 'Por favor ingresa un teléfono válido',
                minLength: 'Mínimo {min} caracteres',
                maxLength: 'Máximo {max} caracteres',
                name: 'Por favor ingresa un nombre válido'
            };

            this.init();
        }

        init() {
            this.forms.forEach(form => {
                this.setupForm(form);
            });

            // Setup Contact Form 7 events
            this.setupCF7Events();
        }

        setupForm(form) {
            const inputs = form.querySelectorAll('input, textarea, select');
            
            // Real-time validation
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('error')) {
                        this.validateField(input);
                    }
                });
            });

            // Form submission
            form.addEventListener('submit', (e) => {
                if (!form.classList.contains('wpcf7-form')) {
                    e.preventDefault();
                    this.handleSubmit(form);
                }
            });
        }

        /**
         * Validate a single field
         */
        validateField(field) {
            const rules = field.dataset.validate ? field.dataset.validate.split('|') : [];
            const value = field.value;
            let isValid = true;
            let errorMessage = '';

            // Check required
            if (field.required || field.hasAttribute('required')) {
                if (!this.validationRules.required(value)) {
                    isValid = false;
                    errorMessage = this.errorMessages.required;
                }
            }

            // Check other validation rules
            if (isValid && value.trim() !== '') {
                rules.forEach(rule => {
                    if (rule.startsWith('minLength:')) {
                        const length = parseInt(rule.split(':')[1]);
                        if (!this.validationRules.minLength(value, length)) {
                            isValid = false;
                            errorMessage = this.errorMessages.minLength.replace('{min}', length);
                        }
                    } else if (rule.startsWith('maxLength:')) {
                        const length = parseInt(rule.split(':')[1]);
                        if (!this.validationRules.maxLength(value, length)) {
                            isValid = false;
                            errorMessage = this.errorMessages.maxLength.replace('{max}', length);
                        }
                    } else if (rule === 'email') {
                        if (!this.validationRules.email(value)) {
                            isValid = false;
                            errorMessage = this.errorMessages.email;
                        }
                    } else if (rule === 'phone') {
                        if (!this.validationRules.phone(value)) {
                            isValid = false;
                            errorMessage = this.errorMessages.phone;
                        }
                    } else if (rule === 'name') {
                        if (!this.validationRules.name(value)) {
                            isValid = false;
                            errorMessage = this.errorMessages.name;
                        }
                    }
                });
            }

            // Update UI
            this.updateFieldUI(field, isValid, errorMessage);

            return isValid;
        }

        updateFieldUI(field, isValid, errorMessage = '') {
            const errorElement = field.parentElement.querySelector('.form-error');
            
            if (isValid) {
                field.classList.remove('error');
                field.classList.add('valid');
                field.setAttribute('aria-invalid', 'false');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            } else {
                field.classList.add('error');
                field.classList.remove('valid');
                field.setAttribute('aria-invalid', 'true');
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                } else {
                    // Create error element if it doesn't exist
                    const newErrorElement = document.createElement('span');
                    newErrorElement.className = 'form-error';
                    newErrorElement.textContent = errorMessage;
                    newErrorElement.setAttribute('role', 'alert');
                    field.parentElement.appendChild(newErrorElement);
                }
            }
        }

        /**
         * Validate entire form
         */
        validateForm(form) {
            const inputs = form.querySelectorAll('input[required], textarea[required], select[required], [data-validate]');
            let isValid = true;
            let firstInvalidField = null;

            inputs.forEach(input => {
                if (!this.validateField(input)) {
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = input;
                    }
                }
            });

            if (firstInvalidField) {
                firstInvalidField.focus();
            }

            return isValid;
        }

        /**
         * Handle form submission (for non-CF7 forms)
         */
        async handleSubmit(form) {
            if (!this.validateForm(form)) {
                return;
            }

            const submitButton = form.querySelector('[type="submit"]');
            const originalText = submitButton.textContent;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
            submitButton.classList.add('loading');

            try {
                const formData = new FormData(form);
                const action = form.action || window.location.href;

                const response = await fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    this.showSuccessMessage(form, result.message || '¡Mensaje enviado con éxito!');
                    form.reset();
                    
                    // Trigger custom event
                    form.dispatchEvent(new CustomEvent('formSuccess', { detail: result }));
                } else {
                    throw new Error(result.message || 'Error al enviar el formulario');
                }
            } catch (error) {
                this.showErrorMessage(form, error.message);
                
                // Trigger custom event
                form.dispatchEvent(new CustomEvent('formError', { detail: error }));
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                submitButton.classList.remove('loading');
            }
        }

        /**
         * Contact Form 7 Events
         */
        setupCF7Events() {
            // Listen for CF7 events
            document.addEventListener('wpcf7invalid', (event) => {
                this.handleCF7Error(event);
            });

            document.addEventListener('wpcf7spam', (event) => {
                this.showErrorMessage(event.target, 'El mensaje fue marcado como spam. Por favor intenta de nuevo.');
            });

            document.addEventListener('wpcf7mailsent', (event) => {
                this.showSuccessMessage(event.target, '¡Gracias! Tu mensaje ha sido enviado exitosamente.');
                
                // Track conversion if analytics available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submit', {
                        'event_category': 'Contact Form',
                        'event_label': 'Contact Request'
                    });
                }
            });

            document.addEventListener('wpcf7mailfailed', (event) => {
                this.showErrorMessage(event.target, 'Hubo un error al enviar el mensaje. Por favor intenta de nuevo.');
            });
        }

        handleCF7Error(event) {
            const invalidFields = event.detail.apiResponse.invalid_fields;
            
            invalidFields.forEach(field => {
                const input = event.target.querySelector(`[name="${field.field}"]`);
                if (input) {
                    this.updateFieldUI(input, false, field.message);
                }
            });
        }

        showSuccessMessage(form, message) {
            let successDiv = form.querySelector('.form-success');
            
            if (!successDiv) {
                successDiv = document.createElement('div');
                successDiv.className = 'form-success';
                form.insertBefore(successDiv, form.firstChild);
            }

            successDiv.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="display:inline;vertical-align:middle;margin-right:8px;">
                    <circle cx="10" cy="10" r="10" fill="#10b981"/>
                    <path d="M6 10l3 3 5-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                ${message}
            `;
            successDiv.style.display = 'block';
            successDiv.setAttribute('role', 'status');

            // Auto-hide after 5 seconds
            setTimeout(() => {
                successDiv.style.opacity = '0';
                setTimeout(() => {
                    successDiv.style.display = 'none';
                    successDiv.style.opacity = '1';
                }, 300);
            }, 5000);
        }

        showErrorMessage(form, message) {
            let errorDiv = form.querySelector('.form-global-error');
            
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'form-global-error';
                errorDiv.style.cssText = 'background:#fee2e2;color:#dc2626;padding:1rem;border-radius:0.5rem;margin-bottom:1rem;';
                form.insertBefore(errorDiv, form.firstChild);
            }

            errorDiv.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="display:inline;vertical-align:middle;margin-right:8px;">
                    <circle cx="10" cy="10" r="10" fill="#dc2626"/>
                    <path d="M7 7l6 6M13 7l-6 6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                ${message}
            `;
            errorDiv.style.display = 'block';
            errorDiv.setAttribute('role', 'alert');

            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.opacity = '0';
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                    errorDiv.style.opacity = '1';
                }, 300);
            }, 5000);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new Forms());
    } else {
        new Forms();
    }
})();
