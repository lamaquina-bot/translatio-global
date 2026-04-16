# 🖥️ Frontend Agent Run — Translatio Global

**VERSIÓN:** 1.0 | **Fecha:** 16 Abril 2026 | **Agente:** Frontend Agent de MOLINO

---

## 1. TEMA WORDPRESS

```
Nombre:       Translatio Theme
Base:         Custom theme (no page builder dependency)
Templates:    6 (home, servicios, proceso, quienes-somos, casos, contacto)
Header:       Logo + nav + language selector
Footer:       Legal links + contacto + idioma + copyright
```

---

## 2. ESTRUCTURA DE ARCHIVOS

```
wp-content/themes/translatio/
├── style.css
├── functions.php
├── header.php
├── footer.php
├── front-page.php          (Home)
├── page-servicios.php
├── page-proceso.php
├── page-quienes-somos.php
├── page-casos.php
├── page-contacto.php
├── template-parts/
│   ├── hero.php
│   ├── services-cards.php
│   ├── process-timeline.php
│   ├── testimonials.php
│   ├── cta-section.php
│   └── chatbot-widget.php
├── assets/
│   ├── css/
│   │   ├── base.css         (reset, variables, typography)
│   │   ├── components.css   (cards, buttons, forms)
│   │   ├── layouts.css      (grid, header, footer)
│   │   └── chatbot.css
│   ├── js/
│   │   ├── main.js
│   │   ├── chatbot.js
│   │   └── language-switcher.js
│   └── images/
│       ├── logo.svg
│       ├── icons/
│       └── heroes/           (webp, lazy load)
├── languages/
│   ├── es_ES.po
│   ├── en_US.po
│   ├── pt_BR.po
│   ├── zh_CN.po
│   └── fr_FR.po
└── inc/
    ├── setup.php             (theme setup, menus, widgets)
    ├── enqueue.php           (scripts y styles)
    ├── chatbot.php           (chatbot shortcode + REST)
    ├── customizer.php        (theme options)
    └── security.php          (disable XML-RPC, REST restrict)
```

---

## 3. CSS ARCHITECTURE

```css
/* Variables */
:root {
  --color-primary: #4A7C6F;
  --color-secondary: #D4A574;
  --color-accent: #2C5F8A;
  --color-bg: #FAFAF7;
  --color-text: #2D2D2D;
  --color-success: #6B9E7D;
  --color-warning: #E8A54B;
  --color-error: #C75C5C;
  
  --font-heading: 'Playfair Display', Georgia, serif;
  --font-body: 'Inter', -apple-system, sans-serif;
  --font-chinese: 'Noto Sans SC', sans-serif;
  
  --spacing-xs: 0.5rem;
  --spacing-sm: 1rem;
  --spacing-md: 2rem;
  --spacing-lg: 4rem;
  --spacing-xl: 6rem;
  
  --radius: 8px;
  --shadow: 0 2px 8px rgba(0,0,0,0.08);
  --transition: all 0.3s ease;
}

/* Chinese language override */
[lang="zh-CN"] {
  --font-heading: 'Noto Sans SC', sans-serif;
  --font-body: 'Noto Sans SC', 'Inter', sans-serif;
}
```

---

## 4. COMPONENTES CLAVE

### Language Selector
```html
<div class="lang-selector">
  <button class="lang-current" aria-expanded="false">
    <span class="lang-flag">🇪🇸</span> ES <span class="arrow">▼</span>
  </button>
  <ul class="lang-dropdown" role="listbox">
    <li><a hreflang="es" href="/es/">🇪🇸 Español</a></li>
    <li><a hreflang="en" href="/en/">🇬🇧 English</a></li>
    <li><a hreflang="pt" href="/pt/">🇧🇷 Português</a></li>
    <li><a hreflang="zh" href="/zh/">🇨🇳 中文</a></li>
    <li><a hreflang="fr" href="/fr/">🇫🇷 Français</a></li>
  </ul>
</div>
```

### Contact Form
```html
<form class="contact-form" id="translatio-contact" novalidate>
  <div class="form-group">
    <label for="contact-name">Nombre *</label>
    <input type="text" id="contact-name" name="name" required 
           minlength="2" maxlength="100" autocomplete="name">
    <span class="error-msg" role="alert"></span>
  </div>
  
  <div class="form-group">
    <label for="contact-email">Email *</label>
    <input type="email" id="contact-email" name="email" required
           autocomplete="email">
    <span class="error-msg" role="alert"></span>
  </div>
  
  <div class="form-group">
    <label for="contact-country">País *</label>
    <select id="contact-country" name="country" required>
      <option value="">Selecciona tu país</option>
      <!-- Lista dinámica por idioma -->
    </select>
  </div>
  
  <div class="form-group">
    <label for="contact-message">Mensaje</label>
    <textarea id="contact-message" name="message" rows="4"
              maxlength="2000"></textarea>
  </div>
  
  <div class="form-group gdpr-consent">
    <input type="checkbox" id="contact-gdpr" name="gdpr_consent" required>
    <label for="contact-gdpr">
      Acepto la <a href="/politica-privacidad/">política de privacidad</a> *
    </label>
  </div>
  
  <!-- Honeypot -->
  <div class="form-group hp" aria-hidden="true">
    <input type="text" name="website" tabindex="-1" autocomplete="off">
  </div>
  
  <button type="submit" class="btn btn-primary">
    <span class="btn-text">Enviar</span>
    <span class="btn-loading" hidden>Enviando...</span>
  </button>
</form>
```

### Chatbot Widget
```html
<div class="chatbot-widget" id="chatbot" role="complementary"
     aria-label="Asistente virtual">
  <button class="chatbot-toggle" aria-expanded="false"
          aria-controls="chatbot-window">
    <span class="chatbot-icon">💬</span>
    <span class="chatbot-pulse"></span>
  </button>
  
  <div class="chatbot-window" id="chatbot-window" hidden>
    <div class="chatbot-header">
      <span>Translatio Asistente</span>
      <button class="chatbot-close" aria-label="Cerrar">✕</button>
    </div>
    <div class="chatbot-messages" role="log" aria-live="polite"></div>
    <div class="chatbot-input">
      <input type="text" placeholder="Escribe tu pregunta..."
             aria-label="Escribe tu mensaje">
      <button class="chatbot-send" aria-label="Enviar">→</button>
    </div>
  </div>
</div>
```

---

## 5. JAVASCRIPT

```javascript
// main.js - Core interactions
document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initSmoothScroll();
  initLazyLoad();
  initLanguageSelector();
  initFormValidation();
  initChatbot();
});

// Form validation (client-side before REST API)
function initFormValidation() {
  const form = document.getElementById('translatio-contact');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Honeypot check
    if (form.querySelector('[name="website"]').value) return;
    
    // Validate
    const data = {
      name: form.querySelector('[name="name"]').value.trim(),
      email: form.querySelector('[name="email"]').value.trim(),
      country: form.querySelector('[name="country"]').value,
      message: form.querySelector('[name="message"]').value.trim(),
      gdpr_consent: form.querySelector('[name="gdpr_consent"]').checked,
      language: document.documentElement.lang.split('-')[0]
    };
    
    // Submit to REST API
    try {
      const res = await fetch('/wp-json/translatio/v1/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      showSuccess();
    } catch (err) {
      showError();
    }
  });
}
```

---

## 6. RESPONSIVE BREAKPOINTS

```css
/* Mobile first */
@media (min-width: 768px) {
  /* Tablet: 2 columnas, hero más grande */
}
@media (min-width: 1024px) {
  /* Desktop: 3 columnas, max-width 1200px */
}
@media (min-width: 1440px) {
  /* Large: más espaciado */
}
```

---

## 7. PERFORMANCE TARGETS

```
LCP:    < 2.5s (hero image WebP + preload)
FID:    < 100ms (minimal JS)
CLS:    < 0.1 (dimensiones explícitas en imágenes)
TTI:    < 3.5s
Bundle: < 100KB JS total (gzipped)
CSS:    < 50KB (gzipped)
```

---

**FIN DE FRONTEND AGENT RUN**
