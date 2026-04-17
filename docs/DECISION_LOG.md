# 📋 DECISION LOG — Translatio Global Defect Fix

**Project:** TG-FIX-2026-001
**Decision Maker:** Technical Project Lead (MOLINO)
**Date:** 17 Abril 2026

---

## DEC-001: Chatbot Architecture — Client-side FAQ vs Backend API

**Decision:** Client-side FAQ matching (no backend API)
**Date:** 2026-04-17
**Status:** ✅ Implemented

### Options Considered
| Option | Pros | Cons |
|--------|------|------|
| A) Client-side FAQ | No backend, no cost, instant response, works offline | Limited to pre-defined answers, no NLP |
| B) Backend API (SSR) | Full NLP, dynamic responses | Requires server, costs money, complexity |

### Rationale
- Site is static (`output: 'static'`). Adding SSR would require significant architecture change.
- The FAQ content is well-defined (what is surrogacy, is it legal, I want info).
- Keyword matching covers the main user intents effectively.
- No cost increase. Maintains zero-server architecture.

---

## DEC-002: Contact Form — External Service vs Custom API

**Decision:** Local storage (temporary) + external service recommended for production
**Date:** 2026-04-17
**Status:** ✅ Temporary fix implemented

### Options Considered
| Option | Pros | Cons |
|--------|------|------|
| A) Formspree/Getform | Free tier, no backend, proven service | External dependency, data on third-party |
| B) Custom API (Coolify) | Full control, data stays local | Requires backend, maintenance |
| C) Local storage (temp) | Zero dependency, works immediately | Data lost on clear, not production-ready |

### Rationale
- Local storage is the immediate fix to remove the 404 error.
- For production, Gabriel should choose between Formspree (quickest) or custom API (most control).
- The form already has GDPR consent checkbox and honeypot anti-spam.

### Action Required
**Gabriel must decide:** Formspree (recommended for speed) or custom API?

---

## DEC-003: Font Loading Strategy — Conditional Noto Sans SC

**Decision:** Only load Noto Sans SC on /zh/ page
**Date:** 2026-04-17
**Status:** ✅ Implemented

### Rationale
- Noto Sans SC is a CJK font (~150KB). Only needed for Chinese content.
- Removing it from ES, EN, PT, FR saves ~150KB per page load.
- Google Fonts URL modified per language.

---

## DEC-004: GDPR Consent Implementation

**Decision:** Add explicit checkbox to both contact form AND chatbot lead form
**Date:** 2026-04-17
**Status:** ✅ Implemented

### Rationale
- Previous implementation had `gdpr_consent: true` hardcoded.
- Contact form already had a checkbox (good).
- Chatbot lead form now has its own checkbox.
- Form submission blocked if checkbox not checked.

---

## DEC-005: Navigation Architecture — Anchor Links vs SPA Routing

**Decision:** Anchor links (#services, #process, #contact) instead of route-based navigation
**Date:** 2026-04-17
**Status:** ✅ Implemented

### Rationale
- Site is single-page per language. No separate pages exist.
- Anchor links work correctly with existing section IDs.
- "About" section doesn't exist as a separate section; linked to top (#).

---

## DEC-006: Language Selector Mobile Fix

**Decision:** Click/touch-based toggle replacing hover-only dropdown
**Date:** 2026-04-17
**Status:** ✅ Implemented

### Rationale
- CSS `group-hover:block` doesn't work on touch devices.
- Added JS click handler that toggles dropdown visibility.
- Added document-level click/touchstart listener to close on outside tap.
