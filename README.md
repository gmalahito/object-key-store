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

### 1. **POST** `/api/object`

**Request Body:**
```json
{
  "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c"
}
```

**Response Body:**
```json
{
   "status": "success" 
}
```

### 2. **GET** `/api/object/{key}`

**Response Body:**
```json
{
   "data": {
        "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
    }
   
}
```

### 3. **GET** `/api/object/{key}y?timestamp=1440568980`

**Response Body:**
```json
{
   "data": {
        "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
    }
   
}
```

### 4. **GET** `/api/object/get_all_records`

**Response Body:**
```json
{
   "data": [
    {
        "key": "0efe6f71-8867-4b3a-b67d-52ef2683424c",
    }
   ] 
}
```
