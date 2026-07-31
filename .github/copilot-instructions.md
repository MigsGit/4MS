# Copilot Instructions — Docu App (Laravel 8 + Vue 3)

## Stack
- Backend: Laravel 8 (PHP), Eloquent ORM, Laravel migrations
- Frontend: Vue 3 (Composition API preferred for new code), Vue Router 4, Pinia for state
- Build: Webpack Mix (not Vite)
- UI: Bootstrap 5 (`bootstrap@5.3.5`) + the `startbootstrap-sb-admin` template (not SB Admin 2 — README is outdated on this). Template CSS/JS come from `startbootstrap-sb-admin/dist/...` and are imported once in `app.js`.
- Icons: Font Awesome 5 Free, solid style only (`@fortawesome/free-solid-svg-icons`), registered globally via `library.add(fas)` and the globally-registered `<font-awesome-icon>` component — not per-component imports.
- Select inputs: `@vueform/multiselect`, registered globally as `<Multiselect>` in `app.js`. Its default theme CSS is imported once globally — don't re-import per component.
- Notifications/toasts: `vue-toast-notification` (Bootstrap theme) is available — use it for user-facing success/error messages instead of `alert()` or ad hoc banners.
- Tables: `startbootstrap-sb-admin/dist/js/datatables-simple-demo.js` is imported globally and drives the vanilla DataTables setup used by the SB Admin template. The `datatables.net-vue3` Vue wrapper exists in the codebase but is currently commented out in `app.js` — don't assume it's active. Follow the existing vanilla-DataTables-on-a-`<table>` pattern for new tables, and flag it if a task seems to need the Vue wrapper instead, since that would require uncommenting/registering it first.
- HTTP: Axios for all API calls from the frontend (configured in `../js/bootstrap`).

## App structure (from app.js)
- Root component: `resources/js/pages/AppTemplate.vue`, mounted to `#app`.
- Pinia instance: exported from `resources/js/stores/index.js` as `pinia`.
- Router: exported from `resources/js/routes` as the default export, used via `.use(router)`.
- Global plugins/components are registered once in `app.js`: `pinia`, `router`, `font-awesome-icon`, `Multiselect`. New global registrations (rare) belong there too — don't re-register the same plugin/component locally in individual `.vue` files.

## General conventions
- Follow PSR-12 for PHP and the existing Laravel 8 folder structure (`app/Http/Controllers`, `app/Models`, `app/Http/Requests`, etc.).
- Prefer Eloquent relationships and query builder over raw SQL unless there's a clear performance reason.
- Use Form Request classes for validation on non-trivial inputs rather than inline `$request->validate()`.
- For Vue components, prefer `<script setup>` Composition API syntax for new/edited components. Don't rewrite existing Options API components unless asked.
- Shared reactive logic goes in `resources/js/composables/` as `useXxx.js` functions, not mixins.
- Global/cross-component state goes in Pinia stores (`resources/js/stores/`), not Vuex, not prop-drilling across many levels.
- API calls from Vue go through a shared Axios instance (don't instantiate a new Axios client per component) and should live in composables or a dedicated `services/`/`api/` layer, not directly inline in components when reused elsewhere.
- Match existing Bootstrap 5 / `startbootstrap-sb-admin` markup conventions (grid classes, card layouts, sidebar structure) rather than introducing a different CSS framework or utility approach.
- Use the globally-registered `<font-awesome-icon icon="..." />` component rather than raw `<i class="fa ...">` tags or per-component FontAwesome imports. Only solid-style icons (`fas`) are in the library — if a design calls for regular/brand icons, flag it, since those aren't currently imported.
- For selectable dropdowns needing search/tagging, use the globally-registered `<Multiselect>` for consistency, not a new library.
- For user-facing success/error/info messages, use `vue-toast-notification` for consistency rather than `alert()`, `console.log`, or a custom banner component.
- For tabular data with sorting/searching/pagination, follow the existing vanilla DataTables pattern (via `startbootstrap-sb-admin`'s `datatables-simple-demo.js`) rather than assuming the Vue DataTables wrapper is available — it's present but currently disabled.

## What not to do
- Don't introduce Vuex, Vite, Options API for new components, or a new CSS framework — this project intentionally uses Pinia, Webpack Mix, Composition API, and Bootstrap 5.
- Don't add new npm packages for functionality already covered by an existing dependency (Axios, Multiselect, FontAwesome, vue-toast-notification, DataTables) without flagging it first.
- Don't import FontAwesome, Multiselect, or their CSS locally inside a `.vue` component — they're registered and styled globally in `app.js` already.
- Don't bypass Laravel's CSRF/auth middleware conventions in new routes.

## Related instructions
- PHP controller-specific rules: `.github/instructions/controllers.instructions.md`
- Vue composable-specific rules: `.github/instructions/composables.instructions.md`
