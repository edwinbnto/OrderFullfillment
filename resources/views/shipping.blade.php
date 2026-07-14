<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nexora Shipping</title>
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

.brand{
    display:flex;
    align-items:center;
    gap:14px;
}

.logout-logo{
    display:flex;
    align-items:center;
    gap:14px;
    text-decoration:none;
    color:inherit;
    cursor:pointer;
    transition:
        transform .25s ease,
        filter .25s ease;
}

.logout-logo:hover{
    transform:scale(1.06);
    filter:drop-shadow(0 8px 18px rgba(59,130,246,.45));
}

.logout-logo:active{
    transform:scale(.96);
}

.logout-logo:visited,
.logout-logo:link,
.logout-logo:hover,
.logout-logo:active{
    color:inherit;
}

.logout-logo .title{
    color:#FFFFFF;
}

.logout-logo .subtitle{
    color:#3B82F6;
}

  .logo {
    width: 46px;
    height: 50px;
    object-fit: contain;
  }

  .brand-text .title { font-size: 20px; font-weight: 700; letter-spacing: 1px; }
  .brand-text .subtitle { font-size: 11px; color: #3B82F6; letter-spacing: 1px; }

  .nav-links { display: flex; gap: 36px; }
  .nav-links a { color: var(--text-muted); text-decoration: none; font-size: 15px; font-weight: 500; }
  .nav-links a.active { color: var(--text-light); font-weight: 700; }

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

  .stat-card .label { color: var(--text-muted); font-size: 14px; font-weight: 600; margin-bottom: 10px; }
  .stat-card .value { font-size: 32px; font-weight: 700; }

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
    width: 200px;
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

  table { width: 100%; border-collapse: collapse; }

  thead th {
    text-align: left;
    padding: 14px 24px;
    font-size: 14px;
    color: #fff;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }

  tbody td { padding: 14px 24px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.05); }
  tbody tr:nth-child(even) { background: rgba(255,255,255,0.02); }

  .order-id, .product { color: var(--text-muted); }
  .customer { font-weight: 600; }

  .priority-low {
    background: #1B6FC8;
    color: #fff;
    padding: 3px 12px;
    border-radius: 5px;
    font-size: 11px;
    display: inline-block;
  }

  .priority-med {
    background: #16A34A;
    color: #fff;
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

  .btn-prepare:hover { background: #244a80; }

  .empty-row td { height: 38px; }

  /* Delivery alerts */
  .activity-list { padding: 8px 0; }

  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 14px;
  }

  .activity-item:last-child { border-bottom: none; }
  .activity-icon { width: 18px; text-align: center; flex-shrink: 0; margin-top: 2px; }

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

  .overlay.active { display: flex; }

  .modal {
    width: 620px;
    max-width: 90vw;
    background: #16305c;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  }

  .modal-header { background: #0f2549; padding: 20px 28px; }
  .modal-header h2 { margin: 0; color: #fff; font-size: 18px; }
  .modal-header p { margin: 4px 0 0; color: #8ea3cc; font-size: 13px; }

  .modal-body {
    padding: 24px 28px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 20px;
  }

  .modal-body .field-label { margin: 0 0 6px; font-size: 12px; color: #8ea3cc; }
  .modal-body .field-value { margin: 0; font-size: 15px; color: #fff; font-weight: 600; }

  .modal-body .status-pill {
    display: inline-block;
    background: #1c3a7a;
    color: #9ec4ff;
    font-weight: 700;
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 6px;
  }

  .assign-banner {
    margin: 0 28px 20px;
    background: #3a3016;
    border: 1px solid #6b5a24;
    border-radius: 8px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    color: #f3d98a;
    font-size: 13px;
  }

  .btn-assign-driver {
    background: #6B4A1E;
    color: #FBD38D;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
  }

  .btn-assign-driver:hover { background: #7d5824; }

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

  .btn-close { background: #2b4a7c; color: #dbe4f5; }
  .btn-close:hover { background: #345a94; }

  .btn-cancel { background: #7a2340; color: #f9c3d3; }
  .btn-cancel:hover { background: #8f2a4b; }
</style>
</head>
<body>

  <div class="top-strip"></div>

  <!-- ============================================
       Everything the user should see BLURRED while
       the modal is open goes inside #pageContent.
       ============================================ -->
  <div id="pageContent">

    <!-- Navbar -->
    <div class="navbar">
      <a href="{{ route('logout') }}" class="brand logout-logo" title="Logout">
    <img class="logo" src="{{ asset('logo/Nexora_Logo_Transparent.png') }}" alt="Nexora Logo">
    <div class="brand-text">
        <div class="title">NEXORA</div>
        <div class="subtitle">ENTERPRISE RESOURCE PLANNING</div>
    </div>
</a>
      <div class="nav-links">
      <a href="{{ route('dashboard') }}">Dashboard</a>
      <a href="{{ route('orders') }}">Orders</a>
      <a href="{{ route('packing') }}">Packing</a>
      <a href="{{ route('shipping') }}" class="active">Shipping</a>
      <a href="{{ route('return') }}">Returns</a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="label">Shipped today</div>
        <div class="value">2</div>
      </div>
      <div class="stat-card">
        <div class="label">In transit</div>
        <div class="value">2</div>
      </div>
      <div class="stat-card">
        <div class="label">On time delivery rate</div>
        <div class="value">80%</div>
      </div>
      <div class="stat-card">
        <div class="label">Delayed shipment</div>
        <div class="value">1</div>
      </div>
    </div>

    <section class="content">

      <div class="panel order-queue">
        <div class="panel-header">
          <div class="title">📦 Shipment tracking</div>
          <div class="actions">
            <div class="search-wrap">
              <span class="search-icon">🔍</span>
              <input type="text" id="shippingSearch" placeholder="Search..." autocomplete="off">
            </div>

            <button id="filterBtn" class="filter-btn">
              Filter <span class="caret">▾</span>
              <span id="filterBadge" class="filter-badge">1</span>
            </button>

            <div id="filterPanel" class="filter-panel">
              <div class="filter-title">Status</div>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="" class="status-check" checked>
                All
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="Ready for delivery" class="status-check">
                Ready for delivery
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="Shipped" class="status-check">
                Shipped
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="Delivered" class="status-check">
                Delivered
              </label>
              <label class="filter-option">
                <input type="radio" name="statusFilter" value="Delayed" class="status-check">
                Delayed
              </label>
            </div>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Tracking no.</th>
              <th>Status</th>
              <th>Destination</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="shippingTableBody">
            <tr class="shipping-row" data-id="4821" data-customer="Maria Santos" data-product="Wireless Headphone" data-tracking="0001" data-status="Ready for delivery" data-status-class="priority-low" data-destination="Cebu City">
              <td class="order-id">#ORD-4821</td>
              <td class="customer">Maria Santos</td>
              <td class="product">Wireless Headphone</td>
              <td>0001</td>
              <td><span class="priority-low">Ready for delivery</span></td>
              <td>Cebu City</td>
              <td><button class="btn-prepare" onclick="openShippingModal('4821')">Assign Driver</button></td>
            </tr>
            <tr class="shipping-row" data-id="4822" data-customer="Carlos Dela Cruz" data-product="Keyboard" data-tracking="0002" data-status="Delayed" data-status-class="priority-high" data-destination="Iloilo City">
              <td class="order-id">#ORD-4822</td>
              <td class="customer">Carlos Dela Cruz</td>
              <td class="product">Keyboard</td>
              <td>0002</td>
              <td><span class="priority-high">Delayed</span></td>
              <td>Iloilo City</td>
              <td><button class="btn-prepare" onclick="openShippingModal('4822')">Assign Driver</button></td>
            </tr>
            <tr class="shipping-row" data-id="4823" data-customer="Ana Reyes" data-product="Gaming mouse" data-tracking="0003" data-status="Delivered" data-status-class="priority-med" data-destination="Calamba, Laguna">
              <td class="order-id">#ORD-4823</td>
              <td class="customer">Ana Reyes</td>
              <td class="product">Gaming mouse</td>
              <td>0003</td>
              <td><span class="priority-med">Delivered</span></td>
              <td>Calamba, Laguna</td>
              <td><button class="btn-prepare" onclick="openShippingModal('4823')">Assign Driver</button></td>
            </tr>
            <tr class="shipping-row" data-id="4824" data-customer="Liza Mendoza" data-product="Mechanical Keyboard" data-tracking="0004" data-status="Shipped" data-status-class="priority-low" data-destination="Metro Manila">
              <td class="order-id">#ORD-4824</td>
              <td class="customer">Liza Mendoza</td>
              <td class="product">Mechanical Keyboard</td>
              <td>0004</td>
              <td><span class="priority-low">Shipped</span></td>
              <td>Metro Manila</td>
              <td><button class="btn-prepare" onclick="openShippingModal('4824')">Assign Driver</button></td>
            </tr>
            <tr class="shipping-row" data-id="4825" data-customer="Jose Bautista" data-product="Webcam HD" data-tracking="0005" data-status="Shipped" data-status-class="priority-low" data-destination="Quezon City">
              <td class="order-id">#ORD-4825</td>
              <td class="customer">Jose Bautista</td>
              <td class="product">Webcam HD</td>
              <td>0005</td>
              <td><span class="priority-low">Shipped</span></td>
              <td>Quezon City</td>
              <td><button class="btn-prepare" onclick="openShippingModal('4825')">Assign Driver</button></td>
            </tr>
            <tr class="empty-row"><td colspan="7"></td></tr>
            <tr class="empty-row"><td colspan="7"></td></tr>
            <tr class="empty-row"><td colspan="7"></td></tr>
            <tr class="empty-row"><td colspan="7"></td></tr>

            <tr class="no-results-row" id="noResultsRow" style="display:none;">
              <td colspan="7">No shipments match your search or filter.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="panel activity">
        <div class="panel-header">
          <div class="title">🔔 Delivery alerts</div>
        </div>
        <div class="activity-list">
          <div class="activity-item">
            <span class="activity-icon">🚫</span>
            <span>DHL pickup delayed</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">📍</span>
            <span>Address verification needed</span>
          </div>
          <div class="activity-item">
            <span class="activity-icon">📝</span>
            <span>3 manifests pending handoff</span>
          </div>
        </div>
      </div>

    </section>
  </div>

  <!-- ============================================
       Modal lives OUTSIDE #pageContent so it never
       gets blurred itself.
       ============================================ -->
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
          <p class="field-label">Status</p>
          <span class="status-pill" id="modalStatus">Ready for delivery</span>
        </div>
        <div>
          <p class="field-label">Product</p>
          <p class="field-value" id="modalItem">Wireless Headphone</p>
        </div>
        <div>
          <p class="field-label">Tracing no.</p>
          <p class="field-value" id="modalTracking">2</p>
        </div>
        <div>
          <p class="field-label">Courier</p>
          <p class="field-value" id="modalCourier">J &amp; T Express</p>
        </div>
        <div>
          <p class="field-label">Due date</p>
          <p class="field-value" id="modalDue">Jun 25</p>
        </div>
        <div style="grid-column: 1 / -1;">
          <p class="field-label">Delivery Address</p>
          <p class="field-value" id="modalAddress">Hillsview Naic, Cavite</p>
        </div>
      </div>

      <div class="assign-banner">
        <span>This order is ready for delivery. Assign a driver to begin the final leg.</span>
        <button class="btn-assign-driver" onclick="assignDriver()">Assign driver</button>
      </div>

      <div class="modal-footer">
        <button class="btn btn-close" onclick="closePackingModal()">Close</button>
        <button class="btn btn-cancel">Cancel order</button>
      </div>
    </div>
  </div>

  <div class="filter-overlay" id="filterOverlay"></div>

  <script>
    // Demo data keyed by order id. Swap this for a fetch() call to your
    // backend if you want live data instead of hardcoded values.
    const orders = {
      '4821': { customer: 'Maria Santos', item: 'Wireless Headphone', tracking: '2', status: 'Ready for delivery', courier: 'J & T Express', due: 'Jun 25', address: 'Hillsview Naic, Cavite' },
      '4822': { customer: 'Carlos Dela Cruz', item: 'Keyboard', tracking: '2', status: 'Delayed', courier: 'DHL', due: 'Jun 24', address: 'Iloilo City' },
      '4823': { customer: 'Ana Reyes', item: 'Gaming mouse', tracking: '1', status: 'Delivered', courier: 'FLASH Express', due: 'Jun 20', address: 'Calamba, Laguna' },
      '4824': { customer: 'Liza Mendoza', item: 'Mechanical Keyboard', tracking: '1', status: 'Shipped', courier: 'J & T Express', due: 'Jun 26', address: 'Metro Manila' },
      '4825': { customer: 'Jose Bautista', item: 'Webcam HD', tracking: '2', status: 'Shipped', courier: 'FLASH Express', due: 'Jun 27', address: 'Quezon City' }
    };

    function openShippingModal(orderId) {
      const order = orders[orderId];
      if (order) {
        document.getElementById('modalOrderId').textContent = '#ORD-' + orderId;
        document.getElementById('modalCustomer').textContent = order.customer;
        document.getElementById('modalItem').textContent = order.item;
        document.getElementById('modalTracking').textContent = order.tracking;
        document.getElementById('modalStatus').textContent = order.status;
        document.getElementById('modalCourier').textContent = order.courier;
        document.getElementById('modalDue').textContent = order.due;
        document.getElementById('modalAddress').textContent = order.address;
      }

      document.getElementById('pageContent').classList.add('blurred');
      document.getElementById('packingOverlay').classList.add('active');
    }

    function closePackingModal() {
      document.getElementById('pageContent').classList.remove('blurred');
      document.getElementById('packingOverlay').classList.remove('active');
    }

    function assignDriver() {
      // TODO: hook this up to your real "assign driver" action
      alert('Driver assigned for this shipment.');
    }

    /* ===================== Search + Filter (working) ===================== */
    const shippingRows   = Array.from(document.querySelectorAll('.shipping-row'));
    const searchInput    = document.getElementById('shippingSearch');
    const filterBtn      = document.getElementById('filterBtn');
    const filterPanel    = document.getElementById('filterPanel');
    const filterOverlay  = document.getElementById('filterOverlay');
    const filterBadge    = document.getElementById('filterBadge');
    const noResultsRow   = document.getElementById('noResultsRow');
    const statusChecks   = document.querySelectorAll('.status-check');

    function activeStatus() {
      const checked = Array.from(statusChecks).find(c => c.checked);
      return checked ? checked.value : '';
    }

    function applyShippingFilters() {
      const query = searchInput.value.trim().toLowerCase();
      const active = activeStatus();
      let visibleCount = 0;

      shippingRows.forEach(function (row) {
        const d = row.dataset;
        const haystack = [d.id, d.customer, d.product, d.tracking, d.status, d.destination]
          .join(' ')
          .toLowerCase();

        const matchesSearch = query === '' || haystack.includes(query);
        const matchesStatus = active === '' || d.status === active;
        const visible = matchesSearch && matchesStatus;

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

    statusChecks.forEach(function (c) {
      c.addEventListener('change', applyShippingFilters);
    });

    searchInput.addEventListener('input', applyShippingFilters);
    /* =================== end Search + Filter =================== */
  </script>

</body>
</html>