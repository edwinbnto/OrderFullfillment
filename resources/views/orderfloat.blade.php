<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Floating widw</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
 
  body {
    height: 100vh;
    background: #1b2a52;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }
 
  /* Blurry background blobs */
  .blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px); 
    opacity: 0.5;
    z-index: 0;
  }
  .blob.one {
    width: 400px; height: 400px;
    background: #4f6fdb;
    top: -100px; left: -100px;
  }
  .blob.two {
    width: 350px; height: 350px;
    background: #b03a5b;
    bottom: -120px; right: -80px;
  }
  .blob.three {
    width: 300px; height: 300px;
    background: #2e3f7a;
    top: 40%; right: 10%;
  }
 
  .card {
    position: relative;
    z-index: 1;
    width: 400px;
    background: rgba(26, 38, 70, 0.95);
    border-radius: 14px;
    color: #e6e9f5;
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    overflow: hidden;
    backdrop-filter: blur(10px);
  }
 
  .card-header {
    padding: 18px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }
 
  .card-header .order-id {
    font-size: 16px;
    font-weight: 700;
  }
 
  .card-header .order-sub {
    font-size: 12px;
    color: #8b94b8;
    margin-top: 2px;
  }
 
  .card-body {
    padding: 20px 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    row-gap: 18px;
  }
 
  .field label {
    display: block;
    font-size: 12px;
    color: #8b94b8;
    margin-bottom: 4px;
  }
 
  .field .value {
    font-size: 14px;
    font-weight: 600;
    color: #f1f3fb;
  }
 
  .badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 4px;
  }
 
  .badge.status {
    background: #3a4a7a;
    color: #cdd6f5;
  }
 
  .badge.priority {
    background: #6e3a63;
    color: #e7c9e0;
  }
 
  .card-footer {
    display: flex;
    gap: 12px;
    padding: 18px 24px 24px;
  }
 
  .btn {
    flex: 1;
    padding: 12px 0;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s ease;
  }
 
  .btn:hover { opacity: 0.85; }
 
  .btn.close {
    background: #2c3a66;
    color: #cdd6f5;
  }
 
  .btn.cancel {
    background: #b3415f;
    color: #fce8ec;
  }
</style>
</head>
<body>
 
  <div class="blob one"></div>
  <div class="blob two"></div>
  <div class="blob three"></div>
 
  <div class="card">
    <div class="card-header">
      <div class="order-id">#ORD-4821</div>
      <div class="order-sub">Website order</div>
    </div>
 
    <div class="card-body">
      <div class="field">
        <label>Customer</label>
        <div class="value">Maria Santos</div>
      </div>
      <div class="field">
        <label>Status</label>
        <span class="badge status">NEW</span>
      </div>
 
      <div class="field">
        <label>Product</label>
        <div class="value">Wireless Headphone</div>
      </div>
      <div class="field">
        <label>Quantity</label>
        <div class="value">2</div>
      </div>
 
      <div class="field">
        <label>Priority</label>
        <span class="badge priority">Low</span>
        
      </div>
      <div class="field">
        <label>Due date</label>
        <div class="value">Jun 25</div>
      </div>
 
      <div class="field"></div>
      <div class="field">
        <label>Delivery Address</label>
        <div class="value">Hillsview Naic, Cavite</div>
      </div>
    </div>
 
    <div class="card-footer">
      <button class="btn close">Close</a>
      <button class="btn cancel">Cancel order</button>`
    </div>
  </div>
 
</body>
</html>