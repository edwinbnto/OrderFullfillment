@php
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Same priority rule used on the dashboard/orders pages:
// NEW on day 0, LOW after 1 day, MEDIUM after 2 days, HIGH after 3+ days
function getPackingPriority($createdAt) {
    if (!$createdAt) {
        return ['label' => 'Low', 'class' => 'priority-low', 'key' => 'Low'];
    }

    $daysOld = Carbon::parse($createdAt)->diffInDays(Carbon::now());

    if ($daysOld >= 3) {
        return ['label' => 'High', 'class' => 'priority-high', 'key' => 'High'];
    } elseif ($daysOld == 2) {
        return ['label' => 'Med', 'class' => 'priority-med', 'key' => 'Med'];
    }

    return ['label' => 'Low', 'class' => 'priority-low', 'key' => 'Low'];
}

// ---- Orders currently in the PACKING column ----
// Queried directly here (same pattern as dashboard.blade.php) so this
// page works no matter which controller/route renders it — it isn't
// dependent on the controller remembering to pass $packingOrders in.
// If a controller DOES pass $packingOrders, that value is used instead.
$packingOrders = $packingOrders ?? DB::table('orders')->where('status', 'PACKING')->get();

// ---- Stats row (all derived from the orders table, nothing hardcoded) ----
$inPackingCount     = $packingOrders->count();
$readyToShipCount   = $readyToShipCount   ?? DB::table('orders')->where('status', 'READY_TO_SHIP')->count();
$packingErrorToday  = $packingErrorToday  ?? 0; // TODO: wire to a packing_errors log once that table exists

// ---- Packing materials (boxes, tape, wrap, etc.) ----
// Expected shape per row from a `packing_materials` table:
// id, name, icon, stock_qty, low_stock_threshold, is_box (bool), box_size (small|medium|large|null)
// Queried directly (with a safe fallback) so this page doesn't break if
// that table doesn't exist yet in your DB.
if (!isset($materials)) {
    $materials = \Illuminate\Support\Facades\Schema::hasTable('packing_materials')
        ? DB::table('packing_materials')->get()
        : collect();
}
$lowStockMaterialCount = $materials->filter(function ($m) {
    return isset($m->stock_qty, $m->low_stock_threshold) && $m->stock_qty <= $m->low_stock_threshold;
})->count();

// Box options shown inside the "prepare shipment" modal — pulled straight
// from the same $materials collection instead of being hardcoded.
$boxMaterials = $materials->filter(fn($m) => !empty($m->is_box));

// Build a lookup (order id => details) for the modal, in the exact shape
// the front-end JS expects, generated from real DB rows instead of a
// hand-typed object.
$packingOrdersJson = $packingOrders->mapWithKeys(function ($order) {
    $priority = getPackingPriority($order->created_at ?? null);
    return [
        (string) $order->id => [
            'customer'      => $order->customer_name,
            'item'          => $order->product_name,
            'qty'           => $order->qty,
            'priority'      => $priority['label'],
            'priorityClass' => $priority['class'],
            'address'       => $order->address ?? '',
        ],
    ];
});
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Nexora Packing</title>
<style>
  :root {
    --bg-header: #0B1E3D;
    --bg-dark: #1B3A6B;
    --bg-card: #0B1E3D;
    --text-light: #FFFFFF;
    --text-muted: #9FB3D1;
    --border-soft: rgba(255,255,255,0.08);
    --accent: #3B82F6;
    --pill: #16305c;
    --pill-border: #2c4373;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg-dark);
    color: var(--text-light);
  }

  /* ===== Navbar ===== */
  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 40px;
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-soft);
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .logo {
    width: 46px;
    height: 50px;
    object-fit: contain;
  }

  .brand-text .title {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 1px;
  }

  .brand-text .subtitle {
    font-size: 11px;
    color: #3B82F6;
    letter-spacing: 1px;
  }

  .nav-links {
    display: flex;
    gap: 36px;
  }

  .nav-links a {
    color: var(--text-muted);
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
  }

  .nav-links a.active {
    color: var(--text-light);
    font-weight: 700;
  }

  .nav-links a:hover {
    color: var(--text-light);
    text-shadow: 0 0 0.4px currentColor, 0 0 0.4px currentColor;
  }

  /* ===== Stats Row ===== */
  .stats-row {
    display: flex;
    gap: 24px;
    padding: 32px 40px 10px;
    flex-wrap: wrap;
  }

  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-soft);
    border-radius: 12px;
    padding: 22px 28px;
    flex: 1;
    min-width: 200px;
  }

  .stat-card .label {
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .stat-card .value {
    font-size: 32px;
    font-weight: 700;
  }

  /* ---------- Main Content ---------- */
  .content {
    display: flex;
    gap: 24px;
    padding: 28px 40px 60px 40px;
  }

  .panel {
    background: var(--bg-card);
    border-radius: 12px;
    overflow: hidden;
  }

  .order-queue { flex: 2.5; }
  .activity { flex: 1; }

  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    position: relative;
    gap: 16px;
  }

  .panel-header .title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: 16px;
    white-space: nowrap;
  }

  .panel-header .actions {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-size: 14px;
  }

  /* ===== Search + Filter (working controls) ===== */
  .search-wrap {
    position: relative;
  }

  .search-wrap input {
    width: 170px;
    background: var(--pill);
    border: 1px solid var(--pill-border);
    border-radius: 20px;
    padding: 8px 14px 8px 32px;
    color: var(--text-light);
    font-size: 13px;
    outline: none;
    transition: width 0.15s ease, border-color 0.15s ease;
  }

  .search-wrap input:focus {
    width: 210px;
    border-color: var(--accent);
  }

  .search-wrap input::placeholder {
    color: var(--text-muted);
  }

  .search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
    font-size: 12px;
  }

  .filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--pill);
    border: 1px solid var(--pill-border);
    border-radius: 20px;
    padding: 8px 14px;
    color: var(--text-light);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    position: relative;
  }

  .filter-btn:hover,
  .filter-btn.active {
    border-color: var(--accent);
  }

  .filter-btn .caret {
    font-size: 10px;
    color: var(--text-muted);
    transition: transform 0.15s ease;
  }

  .filter-btn.open .caret {
    transform: rotate(180deg);
  }

  .filter-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff2f92;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
    line-height: 1.4;
    display: none;
  }

  .filter-panel {
    position: absolute;
    right: 24px;
    top: 56px;
    background: #16305c;
    border: 1px solid var(--pill-border);
    border-radius: 12px;
    padding: 14px 16px;
    width: 180px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.5);
    display: none;
    z-index: 30;
  }

  .filter-panel.show {
    display: block;
  }

  .filter-panel .filter-title {
    color: var(--text-muted);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 10px;
  }

  .filter-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 0;
    cursor: pointer;
    color: var(--text-light);
    font-size: 14px;
    font-weight: 600;
    user-select: none;
  }

  .filter-option input {
    width: 16px;
    height: 16px;
    accent-color: var(--accent);
    cursor: pointer;
  }

  .filter-overlay {
    position: fixed;
    inset: 0;
    z-index: 20;
    display: none;
  }

  .filter-overlay.show {
    display: block;
  }

  .no-results-row td {
    text-align: center;
    padding: 30px;
    color: var(--text-muted);
    font-size: 14px;
  }
  /* ===== end search + filter ===== */

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead th {
    text-align: left;
    padding: 14px 24px;
    font-size: 14px;
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }

  tbody td {
    padding: 14px 24px;
    font-size: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.02);
  }

  .order-id, .product {
    color: var(--text-muted);
  }

  .customer {
    font-weight: 600;
  }

  .priority-low {
    background: #5A3A4A;
    color: #E8B8C8;
    padding: 3px 12px;
    border-radius: 5px;
    font-size: 11px;
    display: inline-block;
  }

  .priority-med {
    background: #6B4A1E;
    color: #FBD38D;
    padding: 3px 12px;
    border-radius: 5px;
    font-size: 11px;
    display: inline-block;
  }

  .priority-high {
    background: #7F1D2E;
    color: #FCA5B1;
    padding: 3px 12px;
    border-radius: 5px;
    font-size: 11px;
    display: inline-block;
  }

  .btn-prepare {
    display: inline-block;
    background: var(--bg-dark);
    color: var(--text-light);
    font-weight: 700;
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 20px;
    text-align: center;
    border: none;
    cursor: pointer;
  }

  .btn-prepare:hover {
    background: #244a80;
  }

  .empty-row td {
    height: 38px;
  }

  .activity-list {
    padding: 8px 0;
  }

  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 14px;
  }

  .activity-item:last-child {
    border-bottom: none;
  }

  .activity-icon {
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    margin-top: 2px;
  }

  /* ============================================
     Blur + modal mechanism
     ============================================ */
  #pageContent {
    transition: filter 0.25s ease;
  }

  #pageContent.blurred {
    filter: blur(4px);
  }

  .overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(5, 12, 28, 0.45);
    align-items: center;
    justify-content: center;
    z-index: 100;
  }

  .overlay.active {
    display: flex;
  }

  .modal {
    width: 620px;
    max-width: 90vw;
    background: #16305c;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  }

  .modal-header {
    background: #0f2549;
    padding: 20px 28px;
  }

  .modal-header h2 {
    margin: 0;
    color: #fff;
    font-size: 18px;
  }

  .modal-header p {
    margin: 4px 0 0;
    color: #8ea3cc;
    font-size: 13px;
  }

  .modal-body {
    padding: 24px 28px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 20px;
  }

  .modal-body .field-label {
    margin: 0 0 6px;
    font-size: 12px;
    color: #8ea3cc;
  }

  .modal-body .field-value {
    margin: 0;
    font-size: 15px;
    color: #fff;
    font-weight: 600;
  }

  .box-options {
    display: flex;
    gap: 12px;
    padding: 0 28px 20px;
  }

  .box-option {
    flex: 1;
    background: #1c3766;
    border: 2px solid transparent;
    border-radius: 8px;
    padding: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
  }

  .box-option.selected { border-color: #3B82F6; background: #24437a; }
  .box-option .box-name { font-weight: 700; font-size: 14px; }
  .box-option .box-stock { font-size: 12px; color: #9FB3D1; }
  .box-option .box-icon { font-size: 22px; }

  .courier-options {
    display: flex;
    gap: 12px;
    padding: 0 28px 20px;
  }

  .courier-option {
    flex: 1;
    border: 2px solid transparent;
    border-radius: 8px;
    padding: 14px 20px;
    cursor: pointer;
    font-weight: 700;
    text-align: center;
  }

  .courier-option.jt { background: #d81f2a; color: #fff; }
  .courier-option.flash { background: #ffd400; color: #111; }
  .courier-option.selected { border-color: #fff; }

  .modal-footer {
    display: flex;
    gap: 12px;
    padding: 20px 28px;
    border-top: 1px solid rgba(255,255,255,0.08);
  }

  .btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
  }

  .btn-done { background: #2b4a7c; color: #dbe4f5; }
  .btn-done:hover { background: #345a94; }

  .btn-cancel { background: #7a2340; color: #f9c3d3; }
  .btn-cancel:hover { background: #8f2a4b; }
</style>
</head>
<body>

  <div class="top-strip"></div>

  <div id="pageContent">

    <!-- Navbar -->
    <div class="navbar">
      <div class="brand">
            <img src="{{ asset('logo/Nexora_Logo_Transparent.png') }}" class="logo" alt="Logo">
        <div class="brand-text">
          <div class="title">NEXORA</div>
          <div class="subtitle">ENTERPRISE RESOURCE PLANNING</div>
        </div>
      </div>
      <div class="nav-links">
     <a href="{{ route('dashboard') }}">Dashboard</a>
     <a href="{{ route('orders') }}">Orders</a>
      <a href="{{ route('packing') }}" class="active">Packing</a>
      <a href="{{ route('shipping') }}">Shipping</a>
      <a href="{{ route('return') }}">Returns</a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="label">In packing</div>
        <div class="value">{{ $inPackingCount }}</div>
      </div>
      <div class="stat-card">
        <div class="label">Ready to ship</div>
        <div class="value">{{ $readyToShipCount }}</div>
      </div>
      <div class="stat-card">
        <div class="label">Packing error today</div>
        <div class="value">{{ $packingErrorToday }}</div>
      </div>
      <div class="stat-card">
        <div class="label">Material low stock</div>
        <div class="value">{{ $lowStockMaterialCount }}</div>
      </div>
    </div>

    <section class="content">

      <div class="panel order-queue">
        <div class="panel-header">
          <div class="title">📦 Packing queue</div>
          <div class="actions">
            <div class="search-wrap">
              <span class="search-icon">🔍</span>
              <input type="text" id="packingSearch" placeholder="Search..." autocomplete="off">
            </div>

            <button id="filterBtn" class="filter-btn">
              Filter <span class="caret">▾</span>
              <span id="filterBadge" class="filter-badge">1</span>
            </button>

            <div id="filterPanel" class="filter-panel">
              <div class="filter-title">Priority</div>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="" class="priority-check" checked>
                All
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="Low" class="priority-check">
                Low
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="Med" class="priority-check">
                Med
              </label>
              <label class="filter-option">
                <input type="radio" name="priorityFilter" value="High" class="priority-check">
                High
              </label>
            </div>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Customer</th>
              <th>Item</th>
              <th>Qty</th>
              <th>Priority</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="packingTableBody">
            @forelse ($packingOrders as $order)
              @php $priority = getPackingPriority($order->created_at ?? null); @endphp
              <tr class="packing-row"
                  data-id="{{ $order->id }}"
                  data-customer="{{ $order->customer_name }}"
                  data-item="{{ $order->product_name }}"
                  data-qty="{{ $order->qty }}"
                  data-priority="{{ $priority['key'] }}"
                  data-priority-class="{{ $priority['class'] }}"
                  data-address="{{ $order->address ?? '' }}">
                <td class="order-id">{{ $order->id }}</td>
                <td class="customer">{{ $order->customer_name }}</td>
                <td class="product">{{ $order->product_name }}</td>
                <td>{{ $order->qty }}</td>
                <td><span class="{{ $priority['class'] }}">{{ $priority['label'] }}</span></td>
                <td><button class="btn-prepare" onclick="openPackingModal('{{ $order->id }}')">Process</button></td>
              </tr>
            @empty
              <tr class="empty-row"><td colspan="6" style="text-align:center; padding:24px; color:var(--text-muted);">Nothing in packing right now.</td></tr>
            @endforelse

            @for ($i = 0; $i < max(0, 4 - $packingOrders->count()); $i++)
              <tr class="empty-row"><td colspan="6"></td></tr>
            @endfor

            <tr class="no-results-row" id="noResultsRow" style="display:none;">
              <td colspan="6">No orders match your search or filter.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="panel activity">
        <div class="panel-header">
          <div class="title">📝 Material packing</div>
        </div>
        <div class="activity-list">
          @forelse ($materials as $material)
            @php
              $isLow = isset($material->stock_qty, $material->low_stock_threshold)
                  && $material->stock_qty <= $material->low_stock_threshold;
              $icon = $material->icon ?? ($isLow ? '⚠️' : '📦');
            @endphp
            <div class="activity-item">
              <span class="activity-icon">{{ $icon }}</span>
              <span>{{ $material->name }} - {{ $material->stock_label ?? ($material->stock_qty . ' left') }}</span>
            </div>
          @empty
            <div class="activity-item">
              <span class="activity-icon">📦</span>
              <span style="color: var(--text-muted);">No material data yet.</span>
            </div>
          @endforelse
        </div>
      </div>

    </section>

  </div><!-- /#pageContent -->

  <div class="overlay" id="packingOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="modalOrderId">—</h2>
        <p>Website order</p>
      </div>

      <div class="modal-body">
        <div>
          <p class="field-label">Customer</p>
          <p class="field-value" id="modalCustomer">—</p>
        </div>
        <div>
          <p class="field-label">Priority</p>
          <span class="priority-low" id="modalPriority">—</span>
        </div>
        <div>
          <p class="field-label">Items</p>
          <p class="field-value" id="modalItem">—</p>
        </div>
        <div>
          <p class="field-label">Quantity</p>
          <p class="field-value" id="modalQty">—</p>
        </div>
        <div style="grid-column: 1 / -1;">
          <p class="field-label">Delivery Address</p>
          <p class="field-value" id="modalAddress">—</p>
        </div>
      </div>

      <div class="box-options">
        @forelse ($boxMaterials as $box)
          <div class="box-option" data-box="{{ $box->box_size }}" onclick="selectBox(this)">
            <div>
              <div class="box-name">{{ $box->name }}</div>
              <div class="box-stock">{{ $box->stock_label ?? ($box->stock_qty . ' left') }}</div>
            </div>
            <div class="box-icon">📦</div>
          </div>
        @empty
          <div class="box-option" style="opacity:0.5; pointer-events:none;">
            <div>
              <div class="box-name">No box sizes configured</div>
              <div class="box-stock">Add rows to packing_materials</div>
            </div>
            <div class="box-icon">📦</div>
          </div>
        @endforelse
      </div>

      <div class="courier-options">
        <div class="courier-option jt" data-courier="jt" onclick="selectCourier(this)">J &amp; T Express</div>
        <div class="courier-option flash" data-courier="flash" onclick="selectCourier(this)">FLASH Express</div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-cancel" onclick="closePackingModal()">Cancel order</button>
        <button class="btn btn-done" onclick="closePackingModal()">Done</button>
      </div>
    </div>
  </div>

  <div class="filter-overlay" id="filterOverlay"></div>

  <script>
    // Order data keyed by order id, rendered straight from the DB
    // ($packingOrders, queried in the controller) — nothing hardcoded.
    const orders = @json($packingOrdersJson);

    function openPackingModal(orderId) {
      const order = orders[orderId];
      if (order) {
        document.getElementById('modalOrderId').textContent = orderId;
        document.getElementById('modalCustomer').textContent = order.customer;
        document.getElementById('modalItem').textContent = order.item;
        document.getElementById('modalQty').textContent = order.qty;
        document.getElementById('modalAddress').textContent = order.address;

        const priorityEl = document.getElementById('modalPriority');
        priorityEl.textContent = order.priority;
        priorityEl.className = order.priorityClass;
      }

      // reset box/courier selection each time the modal opens
      document.querySelectorAll('.box-option').forEach(el => el.classList.remove('selected'));
      document.querySelectorAll('.courier-option').forEach(el => el.classList.remove('selected'));

      document.getElementById('pageContent').classList.add('blurred');
      document.getElementById('packingOverlay').classList.add('active');
    }

    function closePackingModal() {
      document.getElementById('pageContent').classList.remove('blurred');
      document.getElementById('packingOverlay').classList.remove('active');
    }

    function selectBox(el) {
      document.querySelectorAll('.box-option').forEach(o => o.classList.remove('selected'));
      el.classList.add('selected');
    }

    function selectCourier(el) {
      document.querySelectorAll('.courier-option').forEach(o => o.classList.remove('selected'));
      el.classList.add('selected');
    }

    /* ===================== Search + Filter (working) ===================== */
    const packingRows    = Array.from(document.querySelectorAll('.packing-row'));
    const searchInput    = document.getElementById('packingSearch');
    const filterBtn       = document.getElementById('filterBtn');
    const filterPanel     = document.getElementById('filterPanel');
    const filterOverlay   = document.getElementById('filterOverlay');
    const filterBadge     = document.getElementById('filterBadge');
    const noResultsRow    = document.getElementById('noResultsRow');
    const priorityChecks  = document.querySelectorAll('.priority-check');

    function activePriority() {
      const checked = Array.from(priorityChecks).find(c => c.checked);
      return checked ? checked.value : '';
    }

    function applyPackingFilters() {
      const query = searchInput.value.trim().toLowerCase();
      const active = activePriority();
      let visibleCount = 0;

      packingRows.forEach(function (row) {
        const d = row.dataset;
        const haystack = [d.id, d.customer, d.item, d.address]
          .join(' ')
          .toLowerCase();

        const matchesSearch = query === '' || haystack.includes(query);
        const matchesPriority = active === '' || d.priority === active;
        const visible = matchesSearch && matchesPriority;

        row.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
      });

      noResultsRow.style.display = visibleCount === 0 ? '' : 'none';

      if (active !== '') {
        filterBtn.classList.add('active');
        filterBadge.style.display = 'inline-block';
        filterBadge.textContent = '1';
      } else {
        filterBtn.classList.remove('active');
        filterBadge.style.display = 'none';
      }
    }

    function openFilterPanel() {
      filterPanel.classList.add('show');
      filterOverlay.classList.add('show');
      filterBtn.classList.add('open');
    }

    function closeFilterPanel() {
      filterPanel.classList.remove('show');
      filterOverlay.classList.remove('show');
      filterBtn.classList.remove('open');
    }

    filterBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      filterPanel.classList.contains('show') ? closeFilterPanel() : openFilterPanel();
    });

    filterOverlay.addEventListener('click', closeFilterPanel);

    priorityChecks.forEach(function (c) {
      c.addEventListener('change', applyPackingFilters);
    });

    searchInput.addEventListener('input', applyPackingFilters);
    /* =================== end Search + Filter =================== */
  </script>

</body>
</html>