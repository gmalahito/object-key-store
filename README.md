# Laravel Object Key Store API

A lightweight version-controlled key-value storage API built with Laravel 12.

## 📦 Features

- ✅ Store any key with a versioned JSON value.
- ✅ Retrieve the latest value of a key.
- ✅ Retrieve the value of a key at a specific timestamp (UNIX UTC).
- ✅ List all keys with their latest values.
  

## 🛠️ Tech Stack

- Laravel 12 (PHP 8.3+)
- MySQL or SQLite
- GitHub Actions for CI
- PHPUnit for testing

---

## 🔌 API Endpoints

| Method | Endpoint                        | Description                            |
| ------ | ------------------------------- | -------------------------------------- |
| POST   | `/api/object`                   | Save a new value for a key             |
| GET    | `/api/object/{key}`             | Get the latest value for a key         |
| GET    | `/api/object/{key}?timestamp=t` | Get value for key at given timestamp      |
| GET    | `/api/object`                   | Get all historical versions (all keys) |

### 1. **POST** `/api/v1/object-keys`

https://object-key-gmalahito.up.railway.app/api/v1/object-keys

**Request Body:**
```json
{
  "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c"
}
```

**Response Body:**
```json
{
    "message": "Object created successfully",
    "data": {
        "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
        "value": "String value",
        "type": "string",
        "created_at": "2025-08-07T12:58:27.000000Z"
    }
}
```

### 2. **GET** `/api/v1/object-keys/0efe6f71-8867-4b3a-b67d-52ef2683424c`

https://object-key-gmalahito.up.railway.app/api/v1/object-keys/0efe6f71-8867-4b3a-b67d-52ef2683424c

**Response Body:**
```json
{
   "data": {
        "New String value"
    }
   
}
```

### 3. **GET** `/api/v1/object-keys/0efe6f71-8867-4b3a-b67d-52ef2683424c?timestamp=1754572022`

https://object-key-gmalahito.up.railway.app/api/v1/object-keys/0efe6f71-8867-4b3a-b67d-52ef2683424c?timestamp=1754572022

**Response Body:**
```json
{
   "data": {
        "String value"
    }
   
}
```

### 4. **GET** `/api/v1/object-keys`

https://object-key-gmalahito.up.railway.app/api/v1/object-keys

**Response Body:**
```json
{
    "data": [
        {
            "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
            "value": "String value",
            "type": "string",
            "created_at": "2025-08-07T13:04:18.000000Z"
        },
        {
            "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
            "value": "New String value",
            "type": "string",
            "created_at": "2025-08-07T13:07:52.000000Z"
        }
    ],
    "meta": {
        "total": 2,
        "timestamp": "2025-08-07T13:08:56.675859Z"
    }
}
```
