# ⚠️ RISK REGISTER — Translatio Global Defect Fix

**Project:** TG-FIX-2026-001
**Date:** 17 Abril 2026

---

## Active Risks

| ID | Risk | Probability | Impact | Mitigation | Owner |
|----|------|-------------|--------|------------|-------|
| RSK-001 | Legal pages (Privacy Policy, Terms) still link to # | HIGH | HIGH (legal/compliance) | Gabriel must provide legal content before production | Gabriel |
| RSK-002 | Security headers not configured (CSP, HSTS) | HIGH | HIGH (security) | DevOps to configure on Cloudflare/origin server | DevOps |
| RSK-003 | Contact form stores data in localStorage only | HIGH | MEDIUM (data loss) | Integrate Formspree or similar before production | Gabriel |
| RSK-004 | Cloudflare Analytics token = "pending" | MEDIUM | LOW (analytics gap) | Gabriel to provide real token | Gabriel |
| RSK-005 | No "About" section exists for nav link | LOW | LOW (UX) | Currently links to # (top of page). Consider adding section | Future iteration |
| RSK-006 | Client-side FAQ matching may not cover all user queries | MEDIUM | LOW (UX) | Fallback to lead capture for unmatched queries | Acceptable |
| RSK-007 | Google Fonts blocked in China (CHAOS-001) | MEDIUM | MEDIUM (ZH users) | Consider self-hosting fonts for ZH version | Future iteration |

## Mitigated Risks

| ID | Risk | How Mitigated |
|----|------|---------------|
| RSK-M01 | XSS vulnerability in chatbot | Fixed: textContent replaces innerHTML |
| RSK-M02 | GDPR consent falsified | Fixed: Dynamic checkbox added |
| RSK-M03 | Navigation 404 errors | Fixed: Anchor links replace routes |
| RSK-M04 | Chatbot API 404 | Fixed: Client-side FAQ, no API needed |
| RSK-M05 | Language selector broken on mobile | Fixed: Click-based toggle |
| RSK-M06 | Unnecessary 150KB font loading | Fixed: Conditional loading per language |
