---
name: wayfinder-development
description: "Activates whenever working with Wayfinder-generated TypeScript — routes, models, enums, form requests, Inertia page props, shared data, broadcast channels/events, or environment variables. Use when importing from @/wayfinder/, syncing Laravel types to TypeScript, or keeping frontend and backend perfectly in sync."
license: MIT
metadata:
  author: laravel
---

# Wayfinder Development (Beta / next)

## Documentation

Use `search-docs` for detailed Wayfinder patterns and documentation.

## Overview

Wayfinder (beta) bridges your Laravel backend and TypeScript frontend by auto-generating fully-typed TypeScript from your Laravel code. All output lives under `resources/js/wayfinder` (alias `@/wayfinder`).

Wayfinder generates TypeScript for:

- **Routes & Controller Actions** — type-safe URL functions with parameter validation
- **Named Routes** — access routes via Laravel route names
- **Form Requests** — TypeScript types from validation rules
- **Eloquent Models** — TypeScript interfaces with attributes and relationships
- **PHP Enums** — TypeScript types and runtime constants
- **Inertia Page Props** — types for Inertia page data
- **Inertia Shared Data** — types for HandleInertiaRequests shared data
- **Broadcast Channels** — type-safe channel name builders
- **Broadcast Events** — typed WebSocket event payloads
- **Environment Variables** — typed `import.meta.env` for `VITE_*` vars

## Quick Reference

### Generate

Run after any PHP changes if the Vite plugin isn't running:

```bash
php artisan wayfinder:generate --no-interaction
```

> `--with-form`, `--skip-actions`, `--skip-routes` flags are removed in the beta — configure via `config/wayfinder.php` instead.

### Routes & Controller Actions

<!-- Controller Action Imports -->

```typescript
import { PostController } from '@/wayfinder/App/Http/Controllers/PostController'

// Route object { url, method }
PostController.index()                        // { url: '/posts', method: 'get' }
PostController.show({ post: 1 })             // { url: '/posts/1', method: 'get' }

// URL only
PostController.show.url({ post: 42 })        // '/posts/42'

// HTTP method variants
PostController.index.get()
PostController.update.patch({ post: 1 })
PostController.destroy.delete({ post: 1 })

// Form-safe (spoofs PUT/PATCH/DELETE via _method)
PostController.update.form({ post: 1 })      // { action: '/posts/1?_method=PATCH', method: 'post' }

// Query parameters
PostController.index({ query: { page: 2 } })
PostController.index({ mergeQuery: { page: 3, sort: null } }) // null removes param
```

### Named Routes

```typescript
import posts from '@/wayfinder/routes/posts'

posts.index()            // { url: '/posts', method: 'get' }
posts.show({ post: 1 }) // { url: '/posts/1', method: 'get' }
```

### Enums

```typescript
// Runtime constants
import PostStatus from '@/wayfinder/App/Enums/PostStatus'

PostStatus.Published  // 'published'
if (post.status === PostStatus.Draft) { ... }

// Type-checking (from types.d.ts)
import { App } from '@/wayfinder/types'
function setStatus(status: App.Enums.PostStatus) { ... }
```

### Models

```typescript
import { App } from '@/wayfinder/types'

function displayUser(user: App.Models.User) {
    console.log(user.name, user.email)
}
```

### Form Request Types

```typescript
import { App } from '@/wayfinder/types'

const form = useForm<App.Http.Controllers.PostController.Store.Request>()
```

### Inertia Page Props

```vue
<script setup lang="ts">
import { Inertia } from "@/wayfinder/types";

defineProps<Inertia.Pages.Dashboard>();
// Inertia.Pages.Dashboard extends Inertia.SharedData automatically
</script>
```

### Broadcast Channels & Events

```typescript
import { BroadcastChannels } from '@/wayfinder/broadcast-channels'
import { App } from '@/wayfinder/types'

Echo.private(BroadcastChannels.orders(orderId)).listen('OrderShipped', (e) => {
    // e is App.Events.OrderShipped — fully typed
    console.log(e.trackingNumber)
})
```

### Environment Variables

Wayfinder generates `vite-env.d.ts` — `VITE_*` vars in `.env` become typed on `import.meta.env`:

```typescript
const appName = import.meta.env.VITE_APP_NAME // TypeScript knows this is string
```

## Wayfinder + Inertia

Use `.form()` with the `<Form>` component:

```vue
<Form v-bind="PostController.store.form()"><input name="title" /></Form>
```

Or with `useForm`:

```typescript
form.submit(PostController.store())
```

## Verification

1. Run `php artisan wayfinder:generate` to regenerate if Vite plugin isn't running
2. Check TypeScript imports resolve under `@/wayfinder/`
3. Verify `types.d.ts` contains expected model, enum, and Inertia namespaces

## Breaking Changes from Previous Beta

- Import path changed: `@/actions/` and `@/routes/` → `@/wayfinder/`
- Controller imports are now namespace objects: `PostController.show()` not `{ show }`
- `types.ts` renamed to `types.d.ts`
- `--with-form`, `--skip-actions`, `--skip-routes` CLI flags removed — use `config/wayfinder.php`
- Vite plugin: remove `routes`, `actions`, and `withForm` arguments

## Common Pitfalls

- Using old `@/actions/` or `@/routes/` import paths instead of `@/wayfinder/`
- Forgetting to regenerate after PHP model, enum, or route changes
- Using CLI flags that no longer exist (`--with-form`, `--skip-actions`)
- Not using type-safe parameter objects for route model binding
