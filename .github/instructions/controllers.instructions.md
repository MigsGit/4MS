---
applyTo: "app/Http/Controllers/**/*.php"
---

# Controller Instructions

- Keep controllers thin: validation, authorization, and business logic should live in Form Requests, Policies, and Models/Services — not inline in the controller method.
- Use Form Request classes (`app/Http/Requests`) for validating incoming data on store/update actions instead of `$request->validate([...])` inline, unless the validation is trivial (1–2 rules).
- Use route-model binding (type-hinted Eloquent models in method signatures) instead of manually calling `Model::findOrFail($id)` where possible.
- Return JSON resources (`Illuminate\Http\Resources\Json\JsonResource`) or explicit arrays for API responses consumed by the Vue frontend — don't return raw Eloquent models directly for anything with sensitive or excess fields.
- Use Laravel's authorization (`$this->authorize()` / Policies) for access control rather than manual `if ($user->role !== ...)` checks scattered in controllers.
- Follow RESTful method naming (`index`, `show`, `store`, `update`, `destroy`) for resource controllers; use clearly named custom methods for anything else.
- Wrap multi-step database writes in `DB::transaction()`.
- Use dependency injection (constructor or method injection) for services/repositories rather than instantiating classes with `new` inside methods.
- Return consistent JSON error shapes (e.g. `{ message, errors }`) so the Vue Axios layer can handle them uniformly.
- Don't put DataTables server-side processing logic directly in the controller if it's non-trivial — extract to a dedicated class/trait so it's reusable across resources.
