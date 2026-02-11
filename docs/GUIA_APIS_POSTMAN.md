# 📘 Guía Completa: APIs REST y Postman

## 🎯 ¿Qué es una API REST?

**API** = Application Programming Interface  
**REST** = Representational State Transfer

Es la forma en que dos sistemas se comunican a través de HTTP. Piensa en ello como un **menú de restaurante**:
- El **menú** son los endpoints disponibles
- Tu **orden** es la petición (request)
- La **comida** que recibes es la respuesta (response)

---

## 🔹 Conceptos Fundamentales

### 1. Métodos HTTP (Verbos)

| Método | Acción | Ejemplo |
|--------|--------|---------|
| **GET** | Leer/Obtener | Obtener lista de servicios |
| **POST** | Crear | Crear un nuevo cliente |
| **PUT** | Actualizar completo | Actualizar todos los datos de una cita |
| **PATCH** | Actualizar parcial | Cambiar solo el estado de una cita |
| **DELETE** | Eliminar | Eliminar un pago |

### 2. Partes de una Petición (Request)

```
POST http://localhost:8001/api/v1/odt/clientes/lookup
├── Método: POST
├── URL: http://localhost:8001/api/v1/odt/clientes/lookup
├── Headers:
│   ├── Content-Type: application/json
│   └── Accept: application/json
└── Body:
    {
      "telefono": "+18099876543"
    }
```

### 3. Partes de una Respuesta (Response)

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
  "existe": true,
  "data": {
    "id": 2,
    "nombre": "Juan Pérez",
    "telefono": "+18099876543",
    "tipo_cliente": "existente"
  }
}
```

---

## 📊 Status Codes (Códigos de Estado)

### ✅ Éxito (2xx)

| Código | Nombre | Significado |
|--------|--------|-------------|
| **200** | OK | Petición exitosa |
| **201** | Created | Recurso creado exitosamente |
| **204** | No Content | Éxito pero sin contenido (DELETE) |

### ⚠️ Errores del Cliente (4xx)

| Código | Nombre | Significado |
|--------|--------|-------------|
| **400** | Bad Request | Petición malformada |
| **401** | Unauthorized | No autenticado (falta token) |
| **403** | Forbidden | Autenticado pero sin permisos |
| **404** | Not Found | Recurso no existe |
| **422** | Unprocessable Entity | Validación falló |

### 🔥 Errores del Servidor (5xx)

| Código | Nombre | Significado |
|--------|--------|-------------|
| **500** | Internal Server Error | Error del servidor |
| **503** | Service Unavailable | Servidor no disponible |

---

## 🚀 Postman: Guía Práctica

### 📥 Cómo Importar la Colección

1. Abre Postman
2. Click en **Import** (esquina superior izquierda)
3. Arrastra el archivo `Flowbot_Odontologico_API_v1.postman_collection.json`
4. Click en **Import**

### 🌍 Configurar el Environment

1. Click en **Environments** (barra lateral izquierda)
2. Click en **Import**
3. Arrastra `Flowbot_ODT_Local.postman_environment.json`
4. Selecciona el environment **"Flowbot ODT - Local"** en el dropdown superior derecho

### 🧪 Hacer tu Primera Petición

1. En la colección, abre **Clientes → Lookup por teléfono**
2. Verás que la URL usa `{{base_url}}` — esto viene del environment
3. Click en **Send**
4. Verás la respuesta abajo con:
   - Status: `200 OK`
   - Body: JSON con los datos del cliente

---

## 🎓 Conceptos Avanzados de Postman

### 1. Variables

Hay 3 tipos de variables:

#### a) Variables de Environment
```
{{base_url}} = http://localhost:8001/api/v1/odt
{{cliente_id_juan}} = 2
```

#### b) Variables de Colección
Se definen en la colección y aplican a todas las peticiones.

#### c) Variables Globales
Aplican a TODAS las colecciones.

**Orden de precedencia:**
```
Global < Collection < Environment < Local
```

### 2. Pre-request Scripts

Código JavaScript que se ejecuta **ANTES** de la petición.

**Ejemplo: Generar timestamp**
```javascript
pm.environment.set("timestamp", new Date().toISOString());
```

**Ejemplo: Login automático**
```javascript
const loginRequest = {
    url: pm.environment.get("base_url") + "/login",
    method: "POST",
    header: {
        "Content-Type": "application/json"
    },
    body: {
        mode: "raw",
        raw: JSON.stringify({
            email: "admin@example.com",
            password: "password123"
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

### 3. Tests (Validación Automática)

Código JavaScript que se ejecuta **DESPUÉS** de la petición.

**Ejemplo: Verificar status 200**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});
```

**Ejemplo: Verificar que existe un campo**
```javascript
pm.test("Response has 'data' field", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property("data");
});
```

**Ejemplo: Guardar ID de respuesta**
```javascript
const jsonData = pm.response.json();
pm.environment.set("last_created_id", jsonData.data.id);
```

### 4. Collection Runner

Ejecuta **TODAS** las peticiones de la colección automáticamente.

**Cómo usarlo:**
1. Click derecho en la colección
2. **Run collection**
3. Selecciona las peticiones a ejecutar
4. Click **Run**

**Útil para:**
- Testing automatizado
- Verificar que todos los endpoints funcionan
- Generar reportes

---

## 🔐 Autenticación en APIs

### 1. Bearer Token (más común)

```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

**En Postman:**
1. Tab **Authorization**
2. Type: **Bearer Token**
3. Token: `{{auth_token}}`

### 2. API Key

```
X-API-Key: abc123def456
```

### 3. Basic Auth

```
Authorization: Basic dXNlcjpwYXNzd29yZA==
```

---

## 📝 Buenas Prácticas

### ✅ DO (Hacer)

1. **Usa variables** para URLs, tokens, IDs
2. **Organiza en carpetas** por módulo (Clientes, Citas, Pagos)
3. **Documenta** cada endpoint con descripción clara
4. **Agrega ejemplos** de request y response
5. **Usa Tests** para validar automáticamente
6. **Nombra descriptivamente** las peticiones

### ❌ DON'T (No hacer)

1. **No hardcodees** URLs o tokens
2. **No mezcles** entornos (dev, staging, prod) sin environments
3. **No ignores** los status codes
4. **No olvides** los headers necesarios

---

## 🎯 Flujo de Trabajo Profesional

### 1. Desarrollo

```
1. Crear endpoint en Laravel
2. Probar con curl o Postman
3. Agregar a colección de Postman
4. Documentar con descripción y ejemplos
5. Agregar tests automáticos
```

### 2. Testing

```
1. Ejecutar Collection Runner
2. Verificar que todos los tests pasen
3. Revisar status codes
4. Validar estructura de respuestas
```

### 3. Documentación

```
1. Exportar colección de Postman
2. Generar documentación automática
3. Compartir con el equipo
```

---

## 🔍 Debugging en Postman

### Console de Postman

**Ver:**
- Click en **Console** (abajo a la izquierda)
- Verás todas las peticiones con detalles completos

**Usar console.log:**
```javascript
console.log("Cliente ID:", pm.environment.get("cliente_id_juan"));
```

### Snippets Útiles

**Ver headers de respuesta:**
```javascript
console.log(pm.response.headers);
```

**Ver tiempo de respuesta:**
```javascript
console.log("Response time:", pm.response.responseTime + "ms");
```

---

## 📚 Recursos para Aprender Más

1. **Postman Learning Center**: https://learning.postman.com/
2. **HTTP Status Codes**: https://httpstatuses.com/
3. **REST API Tutorial**: https://restfulapi.net/
4. **JSON Formatter**: https://jsonformatter.org/

---

## 🎓 Ejercicios Prácticos

### Ejercicio 1: Flujo Completo Cliente Nuevo

1. Lookup cliente (no existe)
2. Crear cliente
3. Listar servicios
4. Agendar cita
5. Crear pago
6. Confirmar pago
7. Verificar que la cita se confirmó automáticamente

### Ejercicio 2: Flujo Cliente Existente (Ortodoncia)

1. Lookup cliente (Juan Pérez)
2. Ver próxima cita
3. Ver plan de pago
4. Pagar cuota mensual
5. Verificar que `mes_actual` incrementó

### Ejercicio 3: Crear Tests Automáticos

Agrega estos tests a la petición de **Lookup**:

```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has 'existe' field", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property("existe");
});

pm.test("If exists, has data object", function () {
    const jsonData = pm.response.json();
    if (jsonData.existe) {
        pm.expect(jsonData).to.have.property("data");
        pm.expect(jsonData.data).to.have.property("telefono");
    }
});
```

---

## 🚀 Siguiente Nivel: Automatización

### Newman (Postman CLI)

Ejecuta colecciones desde la terminal:

```bash
npm install -g newman
newman run Flowbot_Odontologico_API_v1.postman_collection.json \
  -e Flowbot_ODT_Local.postman_environment.json
```

### Integración con CI/CD

```yaml
# .github/workflows/api-tests.yml
name: API Tests
on: [push]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run Postman tests
        run: |
          npm install -g newman
          newman run postman/collection.json -e postman/environment.json
```

---

## 💡 Tips Pro

1. **Usa Postman Interceptor** para capturar peticiones del navegador
2. **Mock Servers** para simular APIs antes de desarrollarlas
3. **Monitors** para ejecutar colecciones automáticamente cada X tiempo
4. **Workspaces** para colaborar con el equipo
5. **API Documentation** auto-generada desde la colección

---

¡Ahora eres un experto en APIs y Postman! 🎉
