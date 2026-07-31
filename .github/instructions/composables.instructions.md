---
applyTo: "resources/js/composables/**/*.js"
---

# Composables Instructions

- Name every composable `useXxx.js` and export a single primary function `useXxx()`; one composable per file.
- Composables should use the Composition API (`ref`, `reactive`, `computed`, `watch`, etc.) — no Options API patterns and no mixins.
- Return a plain object of refs/computed/functions from the composable (not a reactive object wrapping everything), so consumers can destructure without losing reactivity.
- Keep composables framework-focused, not component-specific: no direct DOM queries tied to a single component's template; accept refs/params as arguments instead.
- All backend communication goes through the shared Axios instance — don't create a new Axios instance inside a composable. Centralize repeated endpoint calls (e.g. `useDocuments.js` wrapping `/api/documents` calls) rather than duplicating Axios calls across components.
- Handle loading and error state explicitly and return them (e.g. `{ data, loading, error, fetchData }`) so components can render spinners/errors consistently, matching the `startbootstrap-sb-admin` UI patterns already in use. For user-facing errors, prefer surfacing via `vue-toast-notification` (already registered globally) rather than returning raw error strings for the component to `alert()`.
- If a composable needs cross-component/global state (not just reusable logic), use a Pinia store instead — composables are for reusable *logic*, Pinia stores are for shared *state*.
- Keep composables free of Bootstrap/DOM-specific markup logic; formatting/markup stays in the `.vue` component, computed values and data logic stay in the composable.
- Write composables to be testable in isolation (no hidden reliance on global component instance context).
