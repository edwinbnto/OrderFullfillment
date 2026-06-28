# Order Fulfillment

A web-based system for managing the order fulfillment pipeline — from order intake through packing, shipping, and returns. Built on Laravel 12.

## Features

- **Dashboard** — shows all orders, packing, and shipped items at a glance, along with alerts and an activity feed.
- **Orders** — view order info and manage status and priority/due date. Clicking **Prepare** on an order moves it to Packing.
- **Packing** — pack orders here. Once packed, the order moves to Shipping.
- **Shipping** — tracks each order's progression through the shipping lifecycle, from Shipped to Ready for Delivery. Once an order reaches Ready for Delivery, a delivery agent can be assigned, automatically advancing the order's status to Out for Delivery and subsequently to Delivered upon completion.
- **Returns** — manage and track returned orders.

Each stage presents records in a table with columns such as Order ID, Customer, Product, Qty, Status, Priority, Courier, Due, and Action.

> **Note:** This project is currently scaffolding — the routes and views are in place, but they return static views rather than dynamic data. Controllers, models, and database tables for orders/shipments still need to be built out.

## Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade templates, Tailwind CSS 4, Vite
- **Database:** MySQL
- **Testing:** Pest 3

## Project Structure

```
app/
  Http/Controllers/   # Controller.php (base controller only, no resource controllers yet)
  Models/              # User.php (default Laravel model only)
database/
  migrations/          # Default Laravel tables (users, cache, jobs)
resources/
  views/
    dashboard.blade.php
    order.blade.php
    packing.blade.php
    shipping.blade.php
    return.blade.php
routes/
  web.php              # Defines the dashboard/order/packing/shipping/return routes
```

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL
