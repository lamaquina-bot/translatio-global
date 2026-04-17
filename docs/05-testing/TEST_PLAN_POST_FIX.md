# 🧪 TEST PLAN POST-FIX — Translatio Global

**Project:** TG-FIX-2026-001
**Date:** 17 Abril 2026
**Prepared by:** Technical Project Lead (MOLINO)
**For:** Super Test Lead

---

## Shift-Left Testing Protocol

For EVERY fix applied:
1. Developer (Project Lead) created fix → this handoff to Test Lead
2. Test Lead verifies fix → reports pass/fail with evidence
3. If fail → back to developer with specific feedback
4. If pass → handoff accepted, move to deployment

---

## Test Cases by Defect

### CRIT-001: Navigation Links (Anchor Links)

| # | Test Case | Steps | Expected Result | Language |
|---|-----------|-------|-----------------|----------|
| TC-001.1 | Desktop nav - Services | Click "Servicios" in header | Page scrolls to #services section | ES |
| TC-001.2 | Desktop nav - Process | Click "Proceso" in header | Page scrolls to #process section | ES |
| TC-001.3 | Desktop nav - Contact | Click "Contacto" button in header | Page scrolls to #contact section | ES |
| TC-001.4 | Desktop nav - About | Click "Quiénes Somos" in header | Page scrolls to top | ES |
| TC-001.5 | Mobile nav - All links | Open mobile menu, tap each link | Each scrolls to correct section | ES |
| TC-001.6 | No 404 errors | Click all nav links | No 404 responses | All 5 |
| TC-001.7 | Cross-language | Verify nav in EN, PT, ZH, FR | All links use anchors, not routes | All 5 |

### CRIT-002: Client-side Chatbot FAQ

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-002.1 | FAQ keyword match | Type "subrogación" or "surrogacy" | Bot responds with FAQ answer |
| TC-002.2 | FAQ legal question | Type "legal" or "Colombia" | Bot responds about legality |
| TC-002.3 | FAQ info request | Type "información" or "information" | Bot responds with info |
| TC-002.4 | No API call | Open DevTools Network, send message | No /api/chat request |
| TC-002.5 | Quick reply buttons | Click each quick reply button | Bot responds with appropriate FAQ |
| TC-002.6 | Unknown question | Type "asdfghjkl" | Bot escalates to lead form |
| TC-002.7 | All languages | Test FAQ in EN, PT, ZH, FR | Answers in correct language |
| TC-002.8 | ZH keywords | Type "代孕" or "合法" | Bot matches and responds |

### CRIT-003: XSS Fix

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-003.1 | Script injection | Type `<script>alert(1)</script>` | Text displayed literally, no alert |
| TC-003.2 | Image onerror | Type `<img src=x onerror=alert(1)>` | Text displayed literally |
| TC-003.3 | HTML injection | Type `<b>bold</b>` | Text shows as literal, not bold |
| TC-003.4 | All languages | Test XSS in EN, PT, ZH, FR | No execution in any language |
| TC-003.5 | Code review | Verify addMessage uses textContent | No innerHTML with user text |

### CRIT-004: GDPR Consent

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-004.1 | Checkbox present | Open chatbot, trigger lead form | GDPR checkbox visible |
| TC-004.2 | Submit without consent | Fill name/email, leave checkbox unchecked | Error message shown, no submission |
| TC-004.3 | Submit with consent | Fill name/email, check checkbox | Submission succeeds |
| TC-004.4 | Privacy link | Check GDPR label text | Link to privacy policy present |
| TC-004.5 | All languages | Verify in all 5 languages | Checkbox text translated |
| TC-004.6 | Contact form GDPR | Submit contact form without checking GDPR | Form blocked |
| TC-004.7 | Contact form GDPR | Submit with GDPR checked | Form submits successfully |

### MAY-001: Language Selector Mobile

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-001.1 | Tap to open | Tap language selector on mobile | Dropdown opens |
| TC-001.2 | Tap to select | Tap another language | Navigates to that language page |
| TC-001.3 | Tap outside | Tap outside dropdown | Dropdown closes |
| TC-001.4 | Desktop hover | Hover on desktop | Dropdown shows (still works) |
| TC-001.5 | iOS Safari | Test on iOS Safari | Works correctly |
| TC-001.6 | Android Chrome | Test on Android Chrome | Works correctly |

### MAY-002: Meta Description Translation

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-002.1 | ES meta | View page source of ES | Description in Spanish |
| TC-002.2 | EN meta | View page source of EN | Description in English |
| TC-002.3 | PT meta | View page source of PT | Description in Portuguese |
| TC-002.4 | ZH meta | View page source of ZH | Description in Chinese |
| TC-002.5 | FR meta | View page source of FR | Description in French |
| TC-002.6 | Length | Check meta length each language | 120-160 characters |

### MAY-005: Conditional Font Loading

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-005.1 | ES page fonts | Check Google Fonts URL in ES | No Noto Sans SC |
| TC-005.2 | EN page fonts | Check Google Fonts URL in EN | No Noto Sans SC |
| TC-005.3 | ZH page fonts | Check Google Fonts URL in ZH | Noto Sans SC present |
| TC-005.4 | Network tab | Check loaded fonts in ES | No Noto Sans SC request |
| TC-005.5 | ZH rendering | Verify Chinese text renders | Correct CJK characters |

### MIN-002: Generator Tag

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-002.1 | No generator | View page source | No `<meta name="generator">` tag |
| TC-002.2 | All languages | Check all 5 pages | No generator tag on any |

### MIN-003: Disclaimer Translation

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-003.1 | PT disclaimer | View chatbot disclaimer on PT page | Text in Portuguese |
| TC-003.2 | FR disclaimer | View chatbot disclaimer on FR page | Text in French |

### MIN-005: Email Validation

| # | Test Case | Steps | Expected Result |
|---|-----------|-------|-----------------|
| TC-005.1 | Invalid email | Enter "notanemail" in lead form | Error message shown |
| TC-005.2 | Valid email | Enter "test@example.com" | Accepts input |
| TC-005.3 | Empty email | Submit with empty email | No submission |

---

## Cross-cutting Test Requirements

### Security Re-test
- [ ] No XSS vectors in chatbot (textContent verified)
- [ ] GDPR consent functional (not hardcoded)
- [ ] No innerHTML with user input anywhere
- [ ] Honeypot field present in contact form
- [ ] No exposed API endpoints

### Performance
- [ ] Noto Sans SC not loaded on non-ZH pages (saves ~150KB)
- [ ] Generator tag removed (minor weight reduction)
- [ ] Total page weight compared to pre-fix

### Accessibility
- [ ] Language selector accessible by keyboard
- [ ] Chatbot has aria-labels
- [ ] Form labels present
- [ ] Focus management works

### Visual Consistency
- [ ] No visual regressions from fixes
- [ ] All 5 languages render correctly
- [ ] Mobile layout unchanged
- [ ] Chatbot UI works in all languages

---

## Test Execution Matrix

| Test Area | ES | EN | PT | ZH | FR |
|-----------|----|----|----|----|----|
| Navigation | ☐ | ☐ | ☐ | ☐ | ☐ |
| Chatbot FAQ | ☐ | ☐ | ☐ | ☐ | ☐ |
| XSS | ☐ | ☐ | ☐ | ☐ | ☐ |
| GDPR | ☐ | ☐ | ☐ | ☐ | ☐ |
| Language Selector | ☐ | ☐ | ☐ | ☐ | ☐ |
| Meta Description | ☐ | ☐ | ☐ | ☐ | ☐ |
| Font Loading | ☐ | ☐ | ☐ | ☐ | ☐ |
| Contact Form | ☐ | ☐ | ☐ | ☐ | ☐ |

**Total test cases:** ~60+
**Critical path:** CRIT-001 through CRIT-004 must ALL pass before deployment
