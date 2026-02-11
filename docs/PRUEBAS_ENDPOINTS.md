# 🧪 Pruebas de Endpoints - Flowbot Odontológico

## 📋 Información General

- **Base URL:** `http://localhost:8001/api/v1/odt`
- **Formato:** JSON
- **Headers requeridos:**
  - `Content-Type: application/json`
  - `Accept: application/json`

---

## 1️⃣ CLIENTES

### Lookup por Teléfono (Webhook clave del bot)

**Cliente existente (Juan Pérez):**
```bash
curl -X POST http://localhost:8001/api/v1/odt/clientes/lookup \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"telefono": "+18099876543"}'
```

**Respuesta esperada:**
```json
{
  "existe": true,
  "data": {
    "id": 2,
    "nombre": "Juan Pérez",
    "telefono": "+18099876543",
    "email": "juan@gmail.com",
    "tipo_cliente": "existente",
    "fecha_registro": "2025-10-15"
  }
}
```

**Cliente NO existente:**
```bash
curl -X POST http://localhost:8001/api/v1/odt/clientes/lookup \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"telefono": "+18095559999"}'
```

**Respuesta esperada:**
```json
{
  "existe": false,
  "message": "Cliente no encontrado."
}
```

### Crear Cliente Nuevo

```bash
curl -X POST http://localhost:8001/api/v1/odt/clientes \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre": "Pedro Martínez",
    "telefono": "+18091112222",
    "email": "pedro@example.com"
  }'
```

### Ver Cliente por ID

```bash
curl -X GET http://localhost:8001/api/v1/odt/clientes/2 \
  -H "Accept: application/json"
```

---

## 2️⃣ SERVICIOS

### Listar Todos los Servicios

```bash
curl -X GET http://localhost:8001/api/v1/odt/servicios \
  -H "Accept: application/json"
```

**Respuesta esperada:**
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Consulta odontológica",
      "precio": 1000,
      "tipo": "consulta"
    },
    {
      "id": 2,
      "nombre": "Ortodoncia – mantenimiento mensual",
      "precio": 3000,
      "tipo": "recurrente"
    },
    {
      "id": 3,
      "nombre": "Limpieza dental",
      "precio": 2000,
      "tipo": "unico"
    }
  ]
}
```

### Ver Servicio Específico

```bash
curl -X GET http://localhost:8001/api/v1/odt/servicios/1 \
  -H "Accept: application/json"
```

---

## 3️⃣ CITAS

### Próxima Cita del Cliente (Webhook bot)

```bash
curl -X GET http://localhost:8001/api/v1/odt/citas/proxima/2 \
  -H "Accept: application/json"
```

**Respuesta esperada:**
```json
{
  "data": {
    "id": 2,
    "cliente_id": 2,
    "servicio_id": 2,
    "fecha": "2026-03-22",
    "hora": "09:00",
    "estado": "pendiente",
    "cliente": {
      "id": 2,
      "nombre": "Juan Pérez",
      "telefono": "+18099876543"
    },
    "servicio": {
      "id": 2,
      "nombre": "Ortodoncia – mantenimiento mensual",
      "precio": 3000,
      "tipo": "recurrente"
    }
  }
}
```

### Agendar Cita

```bash
curl -X POST http://localhost:8001/api/v1/odt/citas \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cliente_id": 1,
    "servicio_id": 1,
    "fecha": "2026-03-25",
    "hora": "14:00"
  }'
```

### Confirmar Cita

```bash
curl -X PATCH http://localhost:8001/api/v1/odt/citas/2/confirmar \
  -H "Accept: application/json"
```

### Cancelar Cita

```bash
curl -X PATCH http://localhost:8001/api/v1/odt/citas/2/cancelar \
  -H "Accept: application/json"
```

---

## 4️⃣ PAGOS

### Crear Pago

```bash
curl -X POST http://localhost:8001/api/v1/odt/pagos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cliente_id": 1,
    "cita_id": 1,
    "monto": 1000,
    "metodo_pago": "tarjeta"
  }'
```

**Respuesta esperada:**
```json
{
  "data": {
    "id": 4,
    "cliente_id": 1,
    "cita_id": 1,
    "monto": 1000,
    "metodo_pago": "tarjeta",
    "estado": "pendiente",
    "fecha_pago": null
  },
  "message": "Pago creado. Pendiente de confirmación."
}
```

### Confirmar Pago (Webhook Pasarela)

**⚠️ Regla de negocio importante:**
Al confirmar el pago, si tiene `cita_id`, la cita se confirma automáticamente.

```bash
curl -X PATCH http://localhost:8001/api/v1/odt/pagos/1/confirmar \
  -H "Accept: application/json"
```

**Respuesta esperada:**
```json
{
  "data": {
    "id": 1,
    "cliente_id": 1,
    "cita_id": 1,
    "monto": 1000,
    "metodo_pago": "tarjeta",
    "estado": "aprobado",
    "fecha_pago": "2026-02-11T02:30:00.000000Z"
  },
  "message": "Pago confirmado exitosamente."
}
```

### Historial de Pagos del Cliente

```bash
curl -X GET http://localhost:8001/api/v1/odt/pagos/cliente/2 \
  -H "Accept: application/json"
```

---

## 5️⃣ PLANES DE PAGO (Ortodoncia)

### Plan Activo del Cliente

```bash
curl -X GET http://localhost:8001/api/v1/odt/planes-pago/cliente/2 \
  -H "Accept: application/json"
```

**Respuesta esperada:**
```json
{
  "data": {
    "id": 1,
    "cliente_id": 2,
    "servicio_id": 2,
    "monto_mensual": 3000,
    "total_meses": 18,
    "mes_actual": 5,
    "meses_restantes": 13,
    "esta_completo": false,
    "activo": true,
    "cliente": {
      "id": 2,
      "nombre": "Juan Pérez"
    },
    "servicio": {
      "id": 2,
      "nombre": "Ortodoncia – mantenimiento mensual",
      "precio": 3000
    }
  }
}
```

### Pagar Cuota Mensual

```bash
curl -X POST http://localhost:8001/api/v1/odt/planes-pago/1/pagar \
  -H "Accept: application/json"
```

**Respuesta esperada:**
```json
{
  "data": {
    "id": 1,
    "mes_actual": 6,
    "total_meses": 18,
    "meses_restantes": 12,
    "esta_completo": false
  },
  "message": "Cuota 6 de 18 pagada exitosamente."
}
```

---

## 🔄 FLUJOS COMPLETOS

### Flujo 1: Cliente Nuevo (María)

```bash
# 1. Lookup (no existe)
curl -X POST http://localhost:8001/api/v1/odt/clientes/lookup \
  -H "Content-Type: application/json" \
  -d '{"telefono": "+18091234567"}'

# 2. Ver servicios disponibles
curl -X GET http://localhost:8001/api/v1/odt/servicios

# 3. Agendar consulta
curl -X POST http://localhost:8001/api/v1/odt/citas \
  -H "Content-Type: application/json" \
  -d '{
    "cliente_id": 1,
    "servicio_id": 1,
    "fecha": "2026-03-20",
    "hora": "10:00"
  }'

# 4. Crear pago
curl -X POST http://localhost:8001/api/v1/odt/pagos \
  -H "Content-Type: application/json" \
  -d '{
    "cliente_id": 1,
    "cita_id": 1,
    "monto": 1000,
    "metodo_pago": "tarjeta"
  }'

# 5. Confirmar pago (auto-confirma la cita)
curl -X PATCH http://localhost:8001/api/v1/odt/pagos/1/confirmar
```

### Flujo 2: Cliente Existente con Ortodoncia (Juan)

```bash
# 1. Lookup (existe)
curl -X POST http://localhost:8001/api/v1/odt/clientes/lookup \
  -H "Content-Type: application/json" \
  -d '{"telefono": "+18099876543"}'

# 2. Ver próxima cita
curl -X GET http://localhost:8001/api/v1/odt/citas/proxima/2

# 3. Ver plan de pago
curl -X GET http://localhost:8001/api/v1/odt/planes-pago/cliente/2

# 4. Pagar cuota mensual
curl -X POST http://localhost:8001/api/v1/odt/planes-pago/1/pagar

# 5. Confirmar asistencia a la cita
curl -X PATCH http://localhost:8001/api/v1/odt/citas/2/confirmar
```

---

## ✅ Checklist de Pruebas

- [ ] Lookup cliente existente retorna `existe: true`
- [ ] Lookup cliente no existente retorna `existe: false`
- [ ] Crear cliente con teléfono duplicado retorna error 422
- [ ] Listar servicios retorna 5 servicios
- [ ] Próxima cita retorna la cita más cercana
- [ ] Agendar cita con fecha pasada retorna error 422
- [ ] Crear pago genera token simulado
- [ ] Confirmar pago auto-confirma la cita asociada
- [ ] Plan de pago muestra meses restantes correctamente
- [ ] Pagar cuota incrementa `mes_actual`
- [ ] Pagar última cuota desactiva el plan

---

## 🐛 Errores Comunes

### Error 422: Validation Failed

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "telefono": ["El número de teléfono es obligatorio."]
  }
}
```

**Solución:** Verifica que estés enviando todos los campos requeridos.

### Error 404: Not Found

```json
{
  "message": "No query results for model [App\\Modules\\Odontology\\Models\\OdtCliente]."
}
```

**Solución:** El ID no existe en la base de datos.

### Error 500: Internal Server Error

Revisa los logs del servidor:
```bash
tail -f storage/logs/laravel.log
```

---

## 📊 Datos de Prueba

### Clientes

| ID | Nombre | Teléfono | Tipo |
|----|--------|----------|------|
| 1 | María Rodríguez | +18091234567 | nuevo |
| 2 | Juan Pérez | +18099876543 | existente |
| 3 | Ana Gómez | +18095554444 | existente |

### Servicios

| ID | Nombre | Precio | Tipo |
|----|--------|--------|------|
| 1 | Consulta odontológica | RD$1,000 | consulta |
| 2 | Ortodoncia mensual | RD$3,000 | recurrente |
| 3 | Limpieza dental | RD$2,000 | unico |
| 4 | Blanqueamiento | RD$8,500 | unico |
| 5 | Endodoncia | RD$6,000 | unico |

---

¡Listo para probar! 🚀
