<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Float win </title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
  }

  body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0f1b3d, #1b2a5e, #0a1230);
    position: relative;
    overflow: hidden;
    padding: 40px 20px;
  }

  /* Background text that peeks through the blur */
  .bg-text {
    position: absolute;
    inset: 0;
    display: flex;
    flex-wrap: wrap;
    align-content: center;
    justify-content: center;
    gap: 30px 60px;
    color: rgba(255, 255, 255, 0.08);
    font-size: 42px;
    font-weight: 800;
    letter-spacing: 4px;
    text-transform: uppercase;
    pointer-events: none;
    user-select: none;
    z-index: 0;
  }

  .bg-text span {
    white-space: nowrap;
  }

  /* Card container */
  .card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 480px;
    background: rgba(30, 45, 90, 0.45);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 24px;
    padding: 28px 26px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    color: #e8ecf7;
  }

  .header {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 22px;
  }

  .header h1 {
    font-size: 20px;
    font-weight: 700;
  }

  .header p {
    font-size: 13px;
    color: rgba(232, 236, 247, 0.55);
    margin-top: 2px;
  }

  .row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 18px;
  }

  .label {
    font-size: 13px;
    color: rgba(232, 236, 247, 0.5);
    margin-bottom: 4px;
  }

  .value {
    font-size: 15px;
    font-weight: 700;
  }

  .priority-badge {
    display: inline-block;
    background: #8a4a63;
    color: #f3d6de;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 8px;
  }

  .size-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 16px;
  }

  .size-box {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 14px 12px;
    text-align: left;
  }

  .size-box .size-name {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 2px;
  }

  .size-box .size-left {
    font-size: 12px;
    color: rgba(232, 236, 247, 0.5);
  }

  .courier-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 20px;
  }

  .courier-box {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 14px 12px;
    text-align: center;
  }

  .courier-box .courier-name {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 3px;
  }

  .courier-box .courier-time {
    font-size: 12px;
    color: rgba(232, 236, 247, 0.5);
  }

  hr {
    border: none;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 18px;
  }

  .action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .btn {
    border: none;
    border-radius: 12px;
    padding: 16px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s ease, filter 0.15s ease;
  }

  .btn:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
  }

  .btn-cancel {
    background: rgba(160, 60, 80, 0.55);
    color: #f3c9d3;
  }

  .btn-done {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.5);
  }
</style>
</head>
<body>

  <div class="card">
    <div class="header">
      <h1>#ORD-4821</h1>
      <p>Website order</p>
    </div>

    <div class="row">
      <div>
        <div class="label">Customer</div>
        <div class="value">Maria Santos</div>
      </div>
      <div>
        <div class="label">Priority</div>
        <span class="priority-badge">Low</span>
      </div>
    </div>

    <div class="row">
      <div>
        <div class="label">Items</div>
        <div class="value">Wireless Headphone</div>
      </div>
      <div>
        <div class="label">Quantity</div>
        <div class="value">2</div>
      </div>
    </div>

    <div class="row" style="margin-bottom: 22px;">
      <div>
        <div class="label">Delivery Address</div>
        <div class="value">Hillsview Naic, Cavite</div>
      </div>
    </div>

    <div class="size-grid">
      <div class="size-box">
        <div class="size-name">Small</div>
        <div class="size-left">14 left</div>
      </div>
      <div class="size-box">
        <div class="size-name">Medium</div>
        <div class="size-left">3 left</div>
      </div>
      <div class="size-box">
        <div class="size-name">Large</div>
        <div class="size-left">15 left</div>
      </div>
    </div>

    <div class="courier-grid">
      <div class="courier-box">
        <div class="courier-name">J &amp; T Express</div>
        <div class="courier-time">Next pickup 2:00 pm</div>
      </div>
      <div class="courier-box">
        <div class="courier-name">FLASH Express</div>
        <div class="courier-time">Next pickup 2:00 pm</div>
      </div>
    </div>

    <hr>

    <div class="action-grid">
      <button class="btn btn-cancel">Cancel order</button>
      <button class="btn btn-done">Done</button>
    </div>
  </div>

</body>
</html>