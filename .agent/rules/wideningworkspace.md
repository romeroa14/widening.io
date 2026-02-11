---
trigger: always_on
---

# 🏗️ Widening.io — Workspace Rules & Clean Code Standards


## 🐘 Clean Code — PHP / Laravel



### Naming Conventions

```php
// Clases → PascalCase (singular)
class GalleryController extends Controller {}
class ImageService {}
class MediaRepository {}
class StoreImageRequest extends FormRequest {}

// Métodos → camelCase (verbos descriptivos)
public function uploadImage(StoreImageRequest $request): JsonResponse {}
public function getActiveGalleries(): Collection {}
public function findBySlug(string $slug): ?Gallery {}

// Variables → camelCase
$galleryImages = $this->imageService->getByGallery($galleryId);
$isPublished = $gallery->published_at !== null;

// Constantes y Enums → UPPER_SNAKE_CASE
const MAX_UPLOAD_SIZE = 10240;

// Tablas DB → snake_case plural
// galleries, gallery_images, image_tags

// Columnas DB → snake_case
// created_at, updated_at, gallery_id, image_url
```

### Estructura de un Controller (Ejemplo Perfecto)

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\StoreGalleryRequest;
use App\Http\Requests\Gallery\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Services\GalleryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class GalleryController extends Controller
{
    public function __construct(
        private readonly GalleryService $galleryService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $galleries = $this->galleryService->getAllPaginated();
        return GalleryResource::collection($galleries);
    }

    public function store(StoreGalleryRequest $request): JsonResponse
    {
        $gallery = $this->galleryService->create($request->validated());
        return GalleryResource::make($gallery)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): GalleryResource
    {
        $gallery = $this->galleryService->findOrFail($id);
        return GalleryResource::make($gallery);
    }

    public function update(UpdateGalleryRequest $request, int $id): GalleryResource
    {
        $gallery = $this->galleryService->update($id, $request->validated());
        return GalleryResource::make($gallery);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->galleryService->delete($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
```

### Reglas Estrictas de Laravel

- ✅ Usar **Form Requests** para TODA validación. Nunca validar en el controller.
- ✅ Usar **API Resources** para transformar las respuestas. Nunca retornar modelos directamente.
- ✅ Usar **Eloquent Scopes** para queries reutilizables.
- ✅ Usar **Enums de PHP 8.1+** en lugar de constantes sueltas.
- ✅ Usar **Route Model Binding** cuando sea apropiado.
- ✅ Tipar TODOS los parámetros y retornos de métodos (`string`, `int`, `array`, `void`, `Collection`, etc.).
- ✅ Usar **readonly properties** en servicios inyectados.
- ❌ NUNCA usar `DB::raw()` sin sanitizar.
- ❌ NUNCA queries N+1. Siempre usar `with()` / `load()` para eager loading.
- ❌ NUNCA hardcodear valores. Usar `config()` o `.env`.
- ❌ NUNCA retornar arrays crudos desde la API. Siempre usar Resources.

### API Responses — Formato Estándar

```json
// ✅ Éxito
{
    "data": { ... },
    "message": "Gallery created successfully",
    "status": 201
}

// ✅ Éxito con paginación
{
    "data": [ ... ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 73
    },
    "links": { ... }
}

// ✅ Error de validación
{
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."],
        "image": ["The image must be a file of type: jpg, png, webp."]
    },
    "status": 422
}

// ✅ Error general
{
    "message": "Gallery not found.",
    "status": 404
}
```

### API Versioning

- Las rutas SIEMPRE van versionadas: `/api/v1/galleries`
- Los controladores van en: `App\Http\Controllers\Api\V1\`
- Agrupar rutas con prefijo y middleware:

```php
Route::prefix('v1')->middleware(['api'])->group(function () {
    Route::apiResource('galleries', GalleryController::class);
});
```

---

## ⚛️ Clean Code — TypeScript / Next.js

### Principios Generales

1. **TypeScript SIEMPRE.** No usar `any` nunca. Si es necesario, usar `unknown` y hacer type narrowing.
2. **Componentes pequeños y reutilizables.** Si un componente supera ~80 líneas, dividirlo.
3. **Separar lógica de UI.** Usar custom hooks para lógica; los componentes solo renderizan.
4. **Server Components por defecto.** Usar `"use client"` solo cuando sea estrictamente necesario.

### Naming Conventions

```typescript
// Componentes → PascalCase
export function GalleryCard({ gallery }: GalleryCardProps) {}
export function ImageUploader() {}

// Hooks → camelCase con prefijo "use"
export function useGalleries() {}
export function useImageUpload() {}

// Interfaces/Types → PascalCase con sufijo descriptivo
interface Gallery {
  id: number;
  title: string;
  slug: string;
  images: GalleryImage[];
  createdAt: string;
}

interface GalleryCardProps {
  gallery: Gallery;
  onSelect?: (id: number) => void;
}

// Constantes → UPPER_SNAKE_CASE
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL;
const MAX_IMAGES_PER_GALLERY = 50;

// Archivos de componentes → PascalCase
// GalleryCard.tsx, ImageUploader.tsx

// Archivos de hooks / utils → camelCase
// useGalleries.ts, formatDate.ts

// Archivos de rutas (App Router) → lowercase
// app/gallery/[id]/page.tsx
```

### Estructura de un Componente (Ejemplo Perfecto)

```tsx
// components/gallery/GalleryCard.tsx
"use client";

import { useState } from "react";
import Image from "next/image";
import type { Gallery } from "@/types/gallery";
import styles from "./GalleryCard.module.css";

interface GalleryCardProps {
  gallery: Gallery;
  onSelect?: (gallery: Gallery) => void;
  isCompact?: boolean;
}

export function GalleryCard({
  gallery,
  onSelect,
  isCompact = false,
}: GalleryCardProps) {
  const [isHovered, setIsHovered] = useState(false);

  const handleClick = () => {
    onSelect?.(gallery);
  };

  return (
    <article
      className={`${styles.card} ${isCompact ? styles.compact : ""}`}
      onClick={handleClick}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      role="button"
      tabIndex={0}
      aria-label={`View gallery: ${gallery.title}`}
    >
      <div className={styles.imageWrapper}>
        <Image
          src={gallery.coverImage}
          alt={gallery.title}
          fill
          sizes="(max-width: 768px) 100vw, 33vw"
          className={styles.image}
          priority={false}
        />
      </div>
      <div className={styles.content}>
        <h3 className={styles.title}>{gallery.title}</h3>
        <p className={styles.count}>
          {gallery.imageCount} {gallery.imageCount === 1 ? "image" : "images"}
        </p>
      </div>
    </article>
  );
}
```

### API Service Layer

```typescript
// services/api.ts
const API_BASE = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api/v1";

interface ApiResponse<T> {
  data: T;
  message?: string;
  status: number;
}

interface PaginatedResponse<T> extends ApiResponse<T[]> {
  meta: {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
  };
}

async function fetchApi<T>(
  endpoint: string,
  options?: RequestInit
): Promise<T> {
  const response = await fetch(`${API_BASE}${endpoint}`, {
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      ...options?.headers,
    },
    ...options,
  });

  if (!response.ok) {
    const error = await response.json();
    throw new ApiError(error.message, response.status, error.errors);
  }

  return response.json();
}

// Uso específico por dominio
export const galleryService = {
  getAll: (page = 1) =>
    fetchApi<PaginatedResponse<Gallery>>(`/galleries?page=${page}`),

  getById: (id: number) =>
    fetchApi<ApiResponse<Gallery>>(`/galleries/${id}`),

  create: (data: CreateGalleryPayload) =>
    fetchApi<ApiResponse<Gallery>>("/galleries", {
      method: "POST",
      body: JSON.stringify(data),
    }),

  update: (id: number, data: UpdateGalleryPayload) =>
    fetchApi<ApiResponse<Gallery>>(`/galleries/${id}`, {
      method: "PUT",
      body: JSON.stringify(data),
    }),

  delete: (id: number) =>
    fetchApi<void>(`/galleries/${id}`, { method: "DELETE" }),
};
```

### Reglas Estrictas de Next.js / React

- ✅ Usar **Server Components** por defecto. Solo añadir `"use client"` si se usa estado, efectos o eventos.
- ✅ Usar **`Image` de Next.js** para todas las imágenes. Nunca `<img>`.
- ✅ Usar **CSS Modules** o **Vanilla CSS** para estilos. No Tailwind salvo indicación explícita.
- ✅ Crear **custom hooks** para toda lógica reutilizable.
- ✅ Usar **TypeScript strict mode** (`"strict": true` en tsconfig).
- ✅ Cada componente exportado debe tener su **interface de Props** claramente definida.
- ❌ NUNCA usar `any`. Usar `unknown` y hacer type guards.
- ❌ NUNCA dejar `console.log()` en código de producción. Usar un logger configurado.
- ❌ NUNCA hacer fetch directamente en componentes. Siempre usar el service layer o hooks.
- ❌ NUNCA usar `useEffect` para data fetching en App Router. Usar server components o React Query.

---


```

### Reglas de Migraciones

- ✅ Cada migración debe ser **reversible** (implementar `down()`).
- ✅ Usar **foreign keys** con `constrained()` y `cascadeOnDelete()` cuando corresponda.
- ✅ Añadir **índices** en columnas usadas en `WHERE`, `ORDER BY`, y `JOIN`.
- ✅ Usar `softDeletes()` en tablas donde se necesite papelera.
- ✅ Incluir `timestamps()` en TODAS las tablas.
- ❌ NUNCA modificar migraciones ya ejecutadas. Crear una nueva migración para cambios.
- ❌ NUNCA almacenar datos sensibles sin encriptar.





### Reglas de Postman

- ✅ Cada endpoint debe tener **descripción** clara de su funcionalidad.
- ✅ Incluir **ejemplos de request body** en cada POST/PUT/PATCH.
- ✅ Documentar **todos los posibles status codes** de respuesta.
- ✅ Usar **variables de entorno** para base URL, tokens, IDs dinámicos.
- ✅ Crear **pre-request scripts** para tokens automáticos:

```javascript
// Pre-request Script para autenticación automática
const loginRequest = {
    url: pm.environment.get("base_url") + "/api/v1/login",
    method: "POST",
    header: { "Content-Type": "application/json" },
    body: {
        mode: "raw",
        raw: JSON.stringify({
            email: pm.environment.get("test_email"),
            password: pm.environment.get("test_password")
        })
    }
};

pm.sendRequest(loginRequest, (err, res) => {
    if (!err) {
        const token = res.json().data.token;
        pm.environment.set("auth_token", token);
    }
});
```




### Reglas Universales (Todos los Lenguajes)

1. **DRY (Don't Repeat Yourself):** Si copias código, extráelo a una función/componente.
2. **KISS (Keep It Simple, Stupid):** La solución más simple que funcione correctamente.
3. **YAGNI (You Ain't Gonna Need It):** No implementes funcionalidades "por si acaso".
4. **Nombres descriptivos:** El código debe leerse como prosa. Prefiere nombres largos y claros a abreviaciones.
5. **Funciones pequeñas:** Máximo ~20 líneas por función. Si es más larga, dividir.
6. **Early returns:** Usar guard clauses para reducir anidación.
7. **Error handling:** Siempre manejar errores de forma explícita y descriptiva.

---

