<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
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
      <img class="logo" src="data:image/png;base64,PASTE_YOUR_FULL_BASE64_LOGO_HERE" alt="Nexora logo">
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
        <div class="value">5</div>
      </div>
      <div class="stat-card">
        <div class="label">Ready to ship</div>
        <div class="value">5</div>
      </div>
      <div class="stat-card">
        <div class="label">Packing error today</div>
        <div class="value">0</div>
      </div>
      <div class="stat-card">
        <div class="label">Material low stock</div>
        <div class="value">2</div>
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
            <tr class="packing-row" data-id="4821" data-customer="Maria Santos" data-item="Wireless Headphone" data-qty="2" data-priority="Low" data-priority-class="priority-low" data-address="Hillsview Naic, Cavite">
              <td class="order-id">#ORD-4821</td>
              <td class="customer">Maria Santos</td>
              <td class="product">Wireless Headphone</td>
              <td>2</td>
              <td><span class="priority-low">Low</span></td>
              <td><button class="btn-prepare" onclick="openPackingModal('4821')">Process</button></td>
            </tr>
            <tr class="packing-row" data-id="4822" data-customer="Carlos Dela Cruz" data-item="Keyboard" data-qty="2" data-priority="Med" data-priority-class="priority-med" data-address="Imus, Cavite">
              <td class="order-id">#ORD-4822</td>
              <td class="customer">Carlos Dela Cruz</td>
              <td class="product">Keyboard</td>
              <td>2</td>
              <td><span class="priority-med">Med</span></td>
              <td><button class="btn-prepare" onclick="openPackingModal('4822')">Process</button></td>
            </tr>
            <tr class="packing-row" data-id="4823" data-customer="Ana Reyes" data-item="Gaming mouse" data-qty="1" data-priority="Low" data-priority-class="priority-low" data-address="Dasmarinas, Cavite">
              <td class="order-id">#ORD-4823</td>
              <td class="customer">Ana Reyes</td>
              <td class="product">Gaming mouse</td>
              <td>1</td>
              <td><span class="priority-low">Low</span></td>
              <td><button class="btn-prepare" onclick="openPackingModal('4823')">Process</button></td>
            </tr>
            <tr class="packing-row" data-id="4824" data-customer="Liza Mendoza" data-item="Mechanical Keyboard" data-qty="1" data-priority="Low" data-priority-class="priority-low" data-address="Bacoor, Cavite">
              <td class="order-id">#ORD-4824</td>
              <td class="customer">Liza Mendoza</td>
              <td class="product">Mechanical Keyboard</td>
              <td>1</td>
              <td><span class="priority-low">Low</span></td>
              <td><button class="btn-prepare" onclick="openPackingModal('4824')">Process</button></td>
            </tr>
            <tr class="packing-row" data-id="4825" data-customer="Jose Bautista" data-item="Webcam HD" data-qty="2" data-priority="High" data-priority-class="priority-high" data-address="Kawit, Cavite">
              <td class="order-id">#ORD-4825</td>
              <td class="customer">Jose Bautista</td>
              <td class="product">Webcam HD</td>
              <td>2</td>
              <td><span class="priority-high">High</span></td>
              <td><button class="btn-prepare" onclick="openPackingModal('4825')">Process</button></td>
            </tr>

            <tr class="empty-row"><td colspan="6"></td></tr>
            <tr class="empty-row"><td colspan="6"></td></tr>
            <tr class="empty-row"><td colspan="6"></td></tr>
            <tr class="empty-row"><td colspan="6"></td></tr>

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
          <div class="activity-item">
            <span class="activity-icon">📦</span>
            <span>Small boxes - 10 left</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">📦</span>
            <span>Medium boxes - 3 left</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">📦</span>
            <span>Large boxes - 15 left</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">✅</span>
            <span>Bubble wrap - 80% stocked</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">⚠️</span>
            <span>Packing tape - 8 rolls left</span>
          </div>
        </div>
      </div>

    </section>

  </div><!-- /#pageContent -->

  <div class="overlay" id="packingOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="modalOrderId">#ORD-4821</h2>
        <p>Website order</p>
      </div>

      <div class="modal-body">
        <div>
          <p class="field-label">Customer</p>
          <p class="field-value" id="modalCustomer">Maria Santos</p>
        </div>
        <div>
          <p class="field-label">Priority</p>
          <span class="priority-low" id="modalPriority">Low</span>
        </div>
        <div>
          <p class="field-label">Items</p>
          <p class="field-value" id="modalItem">Wireless Headphone</p>
        </div>
        <div>
          <p class="field-label">Quantity</p>
          <p class="field-value" id="modalQty">2</p>
        </div>
        <div style="grid-column: 1 / -1;">
          <p class="field-label">Delivery Address</p>
          <p class="field-value" id="modalAddress">Hillsview Naic, Cavite</p>
        </div>
      </div>

      <div class="box-options">
        <div class="box-option" data-box="small" onclick="selectBox(this)">
          <div>
            <div class="box-name">Small</div>
            <div class="box-stock">10 left</div>
          </div>
          <div class="box-icon">📦</div>
        </div>
        <div class="box-option" data-box="medium" onclick="selectBox(this)">
          <div>
            <div class="box-name">Medium</div>
            <div class="box-stock">3 left</div>
          </div>
          <div class="box-icon">📦</div>
        </div>
        <div class="box-option" data-box="large" onclick="selectBox(this)">
          <div>
            <div class="box-name">Large</div>
            <div class="box-stock">15 left</div>
          </div>
          <div class="box-icon">📦</div>
        </div>
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
    // Demo data keyed by order id. Swap this for a fetch() call to your
    // backend if you want live data instead of hardcoded values.
    const orders = {
      '4821': { customer: 'Maria Santos', item: 'Wireless Headphone', qty: 2, priority: 'Low', priorityClass: 'priority-low', address: 'Hillsview Naic, Cavite' },
      '4822': { customer: 'Carlos Dela Cruz', item: 'Keyboard', qty: 2, priority: 'Med', priorityClass: 'priority-med', address: 'Imus, Cavite' },
      '4823': { customer: 'Ana Reyes', item: 'Gaming mouse', qty: 1, priority: 'Low', priorityClass: 'priority-low', address: 'Dasmarinas, Cavite' },
      '4824': { customer: 'Liza Mendoza', item: 'Mechanical Keyboard', qty: 1, priority: 'Low', priorityClass: 'priority-low', address: 'Bacoor, Cavite' },
      '4825': { customer: 'Jose Bautista', item: 'Webcam HD', qty: 2, priority: 'High', priorityClass: 'priority-high', address: 'Kawit, Cavite' }
    };

    function openPackingModal(orderId) {
      const order = orders[orderId];
      if (order) {
        document.getElementById('modalOrderId').textContent = '#ORD-' + orderId;
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