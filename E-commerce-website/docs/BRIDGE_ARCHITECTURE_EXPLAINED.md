# 🌉 The Bridge Architecture: Legacy vs. Modern MVC

In this project, you will notice a "double" structure for Controllers, Models, and Views. This is a deliberate architectural decision called a **Migration Bridge** (or **Strangler Pattern**).

Here is exactly why there are two versions and how they work together.

---

## 🏗️ 1. The Two Structures

| Layer | **Modern Class-based** (Located in `/libs`) | **Legacy Script-based** (Root folders) |
| :--- | :--- | :--- |
| **Controller** | `libs/Controller/Home.php` (Classes) | `controllers/home.php` (Scripts) |
| **Model/DAO** | `libs/Model/Product.php` (Objects) | `src/ProductDAO.php` (Data Access Objects) |
| **View** | `libs/View/Default.php` (Class-based rendering) | `views/home.view.php` (Direct include) |

---

## ❓ 2. Why Two Versions?

This project is in a state of **Evolution**. 

### 1. Refactoring Strategy (Strangler Pattern)
Instead of deleting all the old code and starting from scratch (which is dangerous and causes bugs), we build a **New, Better Engine** (`libs`) alongside the **Old Engine** (root folders). 
We slowly "strangle" the old code by moving functionality into the new classes one by one.

### 2. Scalability (Professional Standards)
*   **The Old Way (`/controllers`, `/src`)**: Useful for small projects. It's easy but hard to maintain when you have 100+ pages.
*   **The New Way (`/libs`)**: Follows Enterprise standards (like Magento or Laravel). It uses **Classes, Inheritance, and Autoloading**. This is what Senior Developers use.

---

## 🛠️ 3. How they Work Together (The "Bridge")

The `index.php` file acts as the **Smart Switch**. Look at this logic inside `index.php`:

```php
// 1. Try the Modern Class-based Route FIRST
if ($controllerClass && class_exists($controllerClass)) {
    $controllerInstance = new $controllerClass();
    $controllerInstance->execute();
    exit;
}

// 2. FALLBACK to the Legacy Script-based Route if class doesn't exist
$controllerPath = ROOT_PATH . '/controllers/' . $controller;
if (file_exists($controllerPath)) {
    require_once $controllerPath;
}
```

### The Logic:
1.  **Priority**: The system first checks if a modern Class (like `Controller_Home`) exists in `libs/`. 
2.  **Execution**: If it exists, it runs the modern code and stops.
3.  **Fallback**: If you haven't written the modern version yet, the system says: *"Okay, let's use the old reliable script in the `controllers/` folder for now."*

---

## 🏆 4. Why this is Helpful for You

### 1. In a Technical Interview:
If an interviewer asks, *"Why do you have two controller folders?"*, you can give a very impressive answer:
> *"I am implementing a **Strangler Fig Pattern** refactor. I am migrating the legacy script-based architecture into a modern Object-Oriented Class structure inside the `libs` folder. The system uses a 'Bridge' in the Front Controller to prefer the new classes while maintaining backward compatibility with the existing scripts. This allows for a zero-downtime migration to a more scalable MVC framework."*

### 2. Developer Workflow:
*   You can build new features using the modern `libs` classes.
*   Existing features keep working from the `controllers` folder.
*   You don't break the site while you're improving it.

### 3. Separation of Concerns:
*   **Root Folders**: Focus on the **Database Queries** (DAOs) and **Public Templates**.
*   **`/libs`**: Focus on the **Core Logic** and **Framework Architecture**.

---

## 📝 5. Summary
*   **Root `controllers/`, `src/`, `views/`**: These are the **Reliable Workers** (The Original Project).
*   **`/libs`**: This is the **Management Team** (The New Framework).
*   **`index.php`**: This is the **CEO** who decides which team handles each request.
