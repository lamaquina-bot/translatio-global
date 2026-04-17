# 👑 PROJECT PLAN — Translatio Global Defect Fix

**ID:** TG-FIX-2026-001
**Project Lead:** MOLINO Technical Project Lead Agent
**Date:** 17 Abril 2026
**Status:** ✅ FIXES EXECUTED

---

## Project Brief

Translatio Global (https://translatio.thefuckinggoat.cloud/) is a multilingual single-page site for gestational surrogacy services in Colombia. The MOLINO Testing Squad found **17 defects** (4 Critical, 7 Major, 6 Minor) blocking production release. This project plan coordinates the fix of all defects with shift-left testing.

## Scope

All 17 defects from TG-FIX-2026-001:
- **4 Critical** (CRIT-001 to CRIT-004): Navigation, APIs, XSS, GDPR
- **7 Major** (MAY-001 to MAY-007): Selector, meta, legal, headers, fonts, sitemap, CSRF
- **6 Minor** (MIN-001 to MIN-006): Analytics, generator, disclaimers, footer, email validation, console.log

## Phases

| Phase | Description | Status |
|-------|-------------|--------|
| Phase 1 | Project Init + Documentation | ✅ Complete |
| Phase 2 | Architecture Decisions | ✅ Complete |
| Phase 3 | Code Fixes (dist/ HTML files) | ✅ Complete |
| Phase 4 | Test Plan Documentation | ✅ Complete |
| Phase 5 | Deployment Readiness | 📋 Documented |
| Phase 6 | Acceptance Criteria | ✅ Complete |

## Fixes Applied (Phase 3)

### Critical Fixes
| Defect | Fix | Files |
|--------|-----|-------|
| CRIT-001 | Nav links changed from routes to anchor links (#services, #process, #contact) | All 5 HTML |
| CRIT-002 | Chatbot now uses client-side FAQ matching (no API needed) | All 5 HTML |
| CRIT-003 | `innerHTML` replaced with `textContent` + DOM construction | All 5 HTML |
| CRIT-004 | GDPR checkbox added to chatbot lead form; consent is now dynamic | All 5 HTML |

### Major Fixes
| Defect | Fix | Files |
|--------|-----|-------|
| MAY-001 | Language selector now click/touch-based (not hover-only) | All 5 HTML |
| MAY-002 | Meta descriptions translated to all 5 languages | All 5 HTML |
| MAY-005 | Noto Sans SC font only loaded on /zh/ page | 4 HTML (removed) |
| MAY-006 | sitemap.xml already exists | N/A |

### Minor Fixes
| Defect | Fix | Files |
|--------|-----|-------|
| MIN-002 | Generator meta tag removed | All 5 HTML |
| MIN-003 | Chatbot disclaimer translated to PT and FR | 2 HTML |
| MIN-005 | Email validation added to chatbot lead form | All 5 HTML |

### Deferred (requires infrastructure/external)
| Defect | Reason | Recommendation |
|--------|--------|---------------|
| MAY-003 | Legal pages (Privacy Policy, Terms) require legal review | Gabriel to provide content |
| MAY-004 | Security headers require Cloudflare/server config | DevOps to configure |
| MAY-007 | CSRF handled by local storage (no API) | N/A for static site |
| MIN-001 | Cloudflare Analytics token needs real value | Gabriel to provide token |
| MIN-004 | Footer translation review | Content review needed |
| MIN-006 | Console.log cleanup | Not found in current code |

## Agent Assignments

| Agent | Responsibility | Status |
|-------|---------------|--------|
| 👑 Project Lead | Coordination, documentation, code fixes | ✅ Complete |
| 🔒 Security Agent | Security headers config (MAY-004) | ⏳ Pending Gabriel |
| 🚀 DevOps Agent | Deployment to Coolify | ⏳ Pending fixes review |
| 🧪 Super Test Lead | Post-fix testing | 📋 Test plan ready |

## Risk Register

See `/docs/RISK_REGISTER.md` for full details.

## Quality Gates

1. ✅ All CRIT fixes applied and verified in code
2. ✅ All MAY fixes applied where possible
3. ⏳ Post-fix testing required (Super Test Lead)
4. ⏳ Deployment to staging for verification
5. ⏳ Gabriel sign-off on acceptance criteria
