
# 📊 Sistema Electoral - Frontend React + Backend Laravel

## 📁 Estructura del Proyecto Frontend

Este proyecto contiene tres componentes React que se conectan a una API REST en Laravel para gestionar un sistema electoral.

---

## 📄 Descripción de Archivos

### 1. **App.jsx** - Aplicación Principal (CRUD Completo)

**Propósito:** Sistema completo de gestión electoral con todas las operaciones CRUD.

#### ✨ Características:

- **Panel de Control** con estadísticas en tiempo real
- **Gestión de Provincias:** Crear y listar provincias
- **Gestión de Listas:** Crear listas electorales asociadas a provincias
- **Gestión de Candidatos:** Crear candidatos asociados a listas
- **Gestión de Mesas:** Crear mesas de votación por provincia
- **Exportación CSV:** Descarga de datos en formato CSV con soporte para caracteres especiales (ñ, tildes)
- **Navegación por pestañas** entre secciones
- **Interfaz moderna** con Tailwind CSS

#### 🔌 Endpoints utilizados:

```javascript
GET  /api/provincias  → Lista todas las provincias
POST /api/provincias  → Crea una nueva provincia

GET  /api/listas      → Lista todas las listas electorales
POST /api/listas      → Crea una nueva lista

GET  /api/candidatos  → Lista todos los candidatos
POST /api/candidatos  → Crea un nuevo candidato

GET  /api/mesas       → Lista todas las mesas
POST /api/mesas       → Crea una nueva mesa
```

#### 📊 Formato de datos esperado:

**GET /api/provincias:**
```json
{
  "provincias": [
    {
      "idProvincia": 1,
      "nombre": "Buenos Aires",
      "codigo": "BA",
      "region": "Centro"
    }
  ]
}
```

**GET /api/listas:**
```json
{
  "listas": [
    {
      "idLista": 1,
      "idProvincia": 1,
      "provincia": { "nombre": "Buenos Aires" },
      "cargo": "Gobernador",
      "nombre": "Lista 1",
      "alianza": "Alianza A"
    }
  ]
}
```

**GET /api/candidatos:**
```json
[
  {
    "idCandidato": 1,
    "nombre": "Juan Pérez",
    "cargo": "Gobernador",
    "idLista": 1
  }
]
```

**GET /api/mesas:**
```json
[
  {
    "idMesa": 1,
    "numero": "001",
    "escuela": "Escuela 1",
    "idProvincia": 1
  }
]
```

---

### 2. **ListadeCandidatos.jsx** - Componente Simple

**Propósito:** Componente básico para listar candidatos únicamente.

#### ✨ Características:

- Lista simple de candidatos
- Manejo de estados de carga
- Manejo básico de errores

#### 🔌 Endpoint utilizado:

```javascript
GET /api/candidatos → Devuelve array de candidatos
```

#### 📊 Respuesta esperada:

```json
[
  {
    "id": 1,
    "nombre": "Juan Pérez",
    "lista_id": 1
  }
]
```

---

### 3. **ListaDeListas.jsx** - Componente Simple

**Propósito:** Componente básico para listar listas electorales con información detallada.

#### ✨ Características:

- Muestra listas con información completa
- Incluye datos de la provincia asociada
- Manejo de errores y carga
- Usa async/await

#### 🔌 Endpoint utilizado:

```javascript
GET /api/listas → Devuelve objeto con array de listas
```

#### 📊 Respuesta esperada:

```json
{
  "listas": [
    {
      "idLista": 1,
      "nombre": "Lista Unidad",
      "alianza": "Frente Común",
      "provincia": {
        "nombre": "Buenos Aires",
        "codigo": "BA"
      },
      "cargoDiputado": "Diputado Nacional",
      "cargoSenador": "Senador Nacional"
    }
  ]
}
```

---

## ⚙️ Configuración del Backend Laravel

### 1. **Configurar CORS**

En `config/cors.php`:

```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',  // Create React App
        'http://localhost:5173',  // Vite
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### 2. **Rutas API** (`routes/api.php`)

```php
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\MesaController;

// Provincias
Route::get('/provincias', [ProvinciaController::class, 'index']);
Route::post('/provincias', [ProvinciaController::class, 'store']);

// Listas
Route::get('/listas', [ListaController::class, 'index']);
Route::post('/listas', [ListaController::class, 'store']);

// Candidatos
Route::get('/candidatos', [CandidatoController::class, 'index']);
Route::post('/candidatos', [CandidatoController::class, 'store']);

// Mesas
Route::get('/mesas', [MesaController::class, 'index']);
Route::post('/mesas', [MesaController::class, 'store']);
```

### 3. **Ejemplo de Controladores**

#### ProvinciaController.php

```php
public function index()
{
    return response()->json([
        'provincias' => Provincia::all()
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'nullable|string|max:10',
        'region' => 'nullable|string|max:100'
    ]);

    $provincia = Provincia::create($validated);

    return response()->json([
        'provincia' => $provincia
    ], 201);
}
```

#### ListaController.php

```php
public function index()
{
    return response()->json([
        'listas' => Lista::with('provincia')->get()
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'idProvincia' => 'required|exists:provincias,idProvincia',
        'cargo' => 'required|string',
        'nombre' => 'required|string',
        'alianza' => 'nullable|string'
    ]);

    $lista = Lista::create($validated);

    return response()->json([
        'lista' => $lista->load('provincia')
    ], 201);
}
```

#### CandidatoController.php

```php
public function index()
{
    return response()->json(
        Candidato::all()
    );
}

public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string',
        'cargo' => 'required|string',
        'idLista' => 'required|exists:listas,idLista'
    ]);

    $candidato = Candidato::create($validated);

    return response()->json([
        'candidato' => $candidato
    ], 201);
}
```

#### MesaController.php

```php
public function index()
{
    return response()->json(
        Mesa::with('provincia')->get()
    );
}

public function store(Request $request)
{
    $validated = $request->validate([
        'numero' => 'required|string',
        'escuela' => 'required|string',
        'idProvincia' => 'required|exists:provincias,idProvincia'
    ]);

    $mesa = Mesa::create($validated);

    return response()->json([
        'mesa' => $mesa->load('provincia')
    ], 201);
}
```

---

## 🚀 Instalación y Ejecución

### Backend (Laravel)

```bash
# Navegar a la carpeta del backend
cd backend

# Instalar dependencias
composer install

# Configurar el archivo .env
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Iniciar servidor
php artisan serve
# El servidor estará en http://localhost:8000
```

### Frontend (React)

```bash
# Navegar a la carpeta del frontend
cd frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
# Vite: http://localhost:5173
# CRA: http://localhost:3000
```

---


## 🔗 Flujo de Datos

```
┌─────────────┐      HTTP/JSON      ┌──────────────┐
│   React     │ ←─────────────────→ │   Laravel    │
│  Frontend   │   axios requests    │   Backend    │
└─────────────┘                     └──────────────┘
      │                                     │
      │                                     │
      ├─ GET /api/provincias               ├─ ProvinciasController
      ├─ GET /api/listas                   ├─ ListasController
      ├─ GET /api/candidatos               ├─ CandidatosController
      ├─ GET /api/mesas                    ├─ MesasController
      │                                     │
      └─ POST /api/* (crear datos)         └─ Eloquent ORM → MySQL
```

---

## 🎯 Recomendaciones

1. **Usar App.jsx** como componente principal - es el más completo y moderno
2. Los otros dos componentes son útiles como **referencia** o para casos simples
3. **Configurar CORS** correctamente antes de conectar frontend y backend
4. Verificar que las **URLs de API** coincidan entre React y Laravel
5. Probar cada endpoint con **Postman** o **Thunder Client** antes de integrar
6. Implementar **manejo de errores** robusto en producción
7. Considerar agregar **autenticación** con Laravel Sanctum

---

## 🐛 Solución de Problemas Comunes

### Error: "Network Error" o "CORS blocked"

**Solución:** Verificar configuración CORS en Laravel y que el servidor esté corriendo.

```bash
php artisan serve
```

### Error: "404 Not Found"

**Solución:** Verificar que las rutas en `routes/api.php` coincidan con las peticiones del frontend.

### Datos no se muestran

**Solución:** Revisar el formato de respuesta JSON del backend con las herramientas de desarrollo del navegador (F12 → Network).

---

## 📝 Notas Adicionales

- **App.jsx** incluye un mock de axios para desarrollo sin backend
- La exportación CSV usa **punto y coma** como separador (estándar latino)
- Se incluye **BOM UTF-8** para correcta visualización de tildes y ñ
- Los IDs de relaciones se manejan como claves foráneas (idProvincia, idLista, etc.)

---
