<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<style>
/* Modern Environmental Monitoring Dashboard - Blue Theme */
.modern-dashboard {
  font-family: 'Segoe UI', 'Roboto', 'Inter', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
  position: relative;
  overflow-x: hidden;
}

.modern-dashboard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: none;
  opacity: 0;
  pointer-events: none;
}

/* Hero Section */
.hero-section {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 30px 38px 20px 38px;
  box-shadow: 0 4px 24px -10px #38598b40;
  border: none;
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.hero-title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
  text-shadow: 0 2px 10px rgba(56,89,139,0.13);
  letter-spacing: -0.5px;
}

.hero-title i {
  margin-right: 15px;
  color: #ffd700;
}

.hero-subtitle {
  font-size: 1.1rem;
  color: #e6e9f2;
  margin: 10px 0 0 0;
  font-weight: 400;
}

.hero-stats {
  display: flex;
  align-items: center;
  gap: 15px;
}

.stat-badge {
  background: #fff;
  color: #38598b;
  font-weight: 700;
  font-size: 1.06rem;
  border-radius: 20px;
  padding: 11px 22px;
  box-shadow: 0 2px 8px -2px #00000018;
  border: none;
}

.stat-badge i {
  margin-right: 10px;
  color: #38598b;
}

/* Statistics Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 0 50px 50px 50px;
}

.stat-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: #38598b;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px #38598b28;
}

.primary-card::before { background: #38598b; }
.success-card::before { background: #28a745; }
.info-card::before { background: #2c406b; }
.warning-card::before { background: #b4c7e7; }

.primary-card .card-icon { background: #38598b; }
.success-card .card-icon { background: #28a745; }
.info-card .card-icon { background: #2c406b; }
.warning-card .card-icon { background: #b4c7e7; color: #38598b; }

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.card-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.8rem;
  color: #fff;
  box-shadow: 0 4px 15px #38598b18;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #38598b;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-body {
  position: relative;
}

.main-stat {
  font-size: 3rem;
  font-weight: 800;
  color: #38598b;
  margin-bottom: 10px;
  line-height: 1;
}

.card-subtitle {
  font-size: 0.95rem;
  color: #7f8c8d;
  font-weight: 500;
}

/* Content Container */
.content-container {
  margin: 0 50px 30px 50px;
}

.content-card {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px #38598b18;
  border: 1px solid #b4c7e7;
  margin-bottom: 30px;
}

.card-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #38598b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-title i {
  color: #38598b;
}

.card-subtitle {
  font-size: 1rem;
  color: #7f8c8d;
  margin: 0 0 30px 0;
  font-weight: 400;
}

/* Control Panels */
.control-panels {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.control-panel {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 25px;
  border: 1px solid #e8f5e8;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.panel-title {
  font-size: 1.3rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.panel-title i {
  color: #38598b;
}

.status-indicator {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
  margin: 10px 0;
}

.status-connected {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: #fff;
}

.status-disconnected {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: #fff;
}

.status-idle {
  background: linear-gradient(135deg, #ffc107, #ffb300);
  color: #fff;
}

.status-active {
  background: linear-gradient(135deg, #fd7e14, #e55a00);
  color: #fff;
}

/* Modern Buttons */
.modern-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  margin: 5px;
}

.btn-primary {
  background: #38598b;
  color: #fff;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px #38598b40;
}

.btn-success {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: #fff;
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-danger {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: #fff;
}

.btn-danger:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

.btn-warning {
  background: linear-gradient(135deg, #ffc107, #ffb300);
  color: #fff;
}

.btn-warning:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
}

.btn-info {
  background: linear-gradient(135deg, #17a2b8, #138496);
  color: #fff;
}

.btn-info:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
}

/* Form Controls */
.modern-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e8f5e8;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fff;
  margin: 5px 0;
}

.modern-input:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

.modern-select {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e8f5e8;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fff;
  margin: 5px 0;
}

.modern-select:focus {
  outline: none;
  border-color: #38598b;
  box-shadow: 0 0 0 3px #b4c7e750;
}

/* Automation Panels */
.automation-panel {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 25px;
  margin-bottom: 25px;
  border: 1px solid #e8f5e8;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.automation-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.automation-title i {
  color: #38598b;
}

.automation-form {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.automation-field {
  display: flex;
  flex-direction: column;
}

.automation-field label {
  font-weight: 600;
  color: #38598b;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.automation-list {
  margin-top: 20px;
}

.automation-item {
  background: #fff;
  border-radius: 10px;
  padding: 15px 20px;
  margin-bottom: 10px;
  box-shadow: 0 2px 8px #38598b18;
  border: 1px solid #e8f5e8;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.automation-item span {
  font-weight: 500;
  color: #38598b;
}

.remove-btn {
  background: linear-gradient(135deg, #dc3545, #c82333) !important;
  color: #fff !important;
  border: none !important;
  border-radius: 8px !important;
  padding: 8px 16px !important;
  font-size: 0.9rem !important;
  cursor: pointer;
  transition: all 0.3s ease;
}

.remove-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Chart Container */
.chart-container {
  background: #fff;
  border-radius: 15px;
  padding: 25px;
  margin-bottom: 25px;
  box-shadow: 0 4px 15px #38598b18;
  border: 1px solid #e8f5e8;
}

.chart-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #38598b;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.chart-title i {
  color: #38598b;
}

.chart-wrapper {
  height: 300px;
  position: relative;
}

/* Relay Control */
.relay-control {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 0;
  border-bottom: 1px solid #e8f5e8;
}

.relay-control:last-child {
  border-bottom: none;
}

.relay-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.relay-name {
  font-weight: 600;
  color: #38598b;
  font-size: 1.1rem;
}

.relay-status {
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
  font-weight: 600;
}

.relay-on {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: #fff;
}

.relay-off {
  background: linear-gradient(135deg, #6c757d, #5a6268);
  color: #fff;
}

.relay-actions {
  display: flex;
  gap: 10px;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 0 30px 40px 30px;
  }
  
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  
  .content-container {
    margin-left: 30px;
    margin-right: 30px;
  }
  
  .control-panels {
    grid-template-columns: 1fr;
  }
  
  .automation-form {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .modern-dashboard {
    margin-left: 0;
  }
  
  .hero-section {
    padding: 30px 20px 20px 20px;
    margin-bottom: 30px;
  }
  
  .hero-title {
    font-size: 2.2rem;
  }
  
  .stats-grid {
    margin: 0 20px 30px 20px;
    grid-template-columns: 1fr;
  }
  
  .content-container {
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .content-card {
    padding: 20px;
  }
  
  .control-panels {
    grid-template-columns: 1fr;
  }
  
  .relay-control {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }
  
  .relay-actions {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }
  
  .main-stat {
    font-size: 2.5rem;
  }
  
  .control-panel {
    padding: 15px;
  }
  
  .automation-panel {
    padding: 15px;
  }
  
  .chart-container {
    padding: 15px;
  }
}
</style>
<!-- Modern Environmental Monitoring Dashboard -->
<div class="modern-dashboard" style="margin-left:280px">
    <!-- Hero Header Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <i class="fa fa-thermometer-half"></i>
                    Environmental Monitoring
                </h1>
                <p class="hero-subtitle">Real-time sensor monitoring and automated control for optimal pig farm conditions</p>
    </div>
            <div class="hero-stats">
                <div class="stat-badge">
                    <i class="fa fa-wifi"></i>
                    <span id="connectionStatusText">Disconnected</span>
                </div>
                <div class="stat-badge">
                    <i class="fa fa-cog"></i>
                    <span>Automated Control</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-thermometer-half"></i>
                </div>
                <div class="card-title">Temperature</div>
            </div>
            <div class="card-body">
                <div class="main-stat" id="tempDisplay">--°C</div>
                <div class="card-subtitle">Current reading</div>
            </div>
        </div>

        <div class="stat-card success-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-tint"></i>
                </div>
                <div class="card-title">Humidity</div>
            </div>
            <div class="card-body">
                <div class="main-stat" id="humDisplay">--%</div>
                <div class="card-subtitle">Current reading</div>
            </div>
        </div>

        <div class="stat-card info-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-wind"></i>
                </div>
                <div class="card-title">Ammonia</div>
            </div>
            <div class="card-body">
                <div class="main-stat" id="ammoniaDisplay">--</div>
                <div class="card-subtitle">Air quality level</div>
            </div>
        </div>

        <div class="stat-card warning-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa fa-bolt"></i>
                </div>
                <div class="card-title">Active Relays</div>
            </div>
            <div class="card-body">
                <div class="main-stat" id="activeRelaysDisplay">0/4</div>
                <div class="card-subtitle">Currently active</div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="content-container">
    <!-- Automation Controls -->
        <div class="content-card">
            <h2 class="card-title">
                <i class="fa fa-magic"></i>
                Automation & Scheduling
            </h2>
            <p class="card-subtitle">Configure automated feeding and relay control based on environmental conditions</p>

            <!-- Feeder Automation Controls -->
    <div class="automation-panel">
                <h3 class="automation-title">
                    <i class="fa fa-utensils"></i>
                    Feeder Automation
                </h3>
                <form id="feederAutomationForm" class="automation-form">
                    <div class="automation-field">
                        <label>Sensor Condition:</label>
                        <select id="feederConditionType" class="modern-select">
                            <option value="temperature">Temperature</option>
                            <option value="humidity">Humidity</option>
                            <option value="ammonia">Ammonia</option>
                            <option value="none">None (only schedule)</option>
          </select>
        </div>
                    <div class="automation-field">
                        <label>Condition:</label>
                        <select id="feederCond" class="modern-select">
            <option value=">">Above</option>
            <option value="<">Below</option>
          </select>
                        <input type="number" id="feederCondValue" placeholder="Value" class="modern-input">
        </div>

                    <div class="automation-field">
                        <label>Schedule Start (optional):</label>
                        <input type="time" id="feederScheduleStart" class="modern-input">
                    </div>
                    <div class="automation-field">
                        <label>Schedule End (optional):</label>
                        <input type="time" id="feederScheduleEnd" class="modern-input">
                    </div>

                    <div class="automation-field">
                        <label>Dispense Times (HH:MM, comma separated):</label>
                        <input type="text" id="feederDispenseTimes" placeholder="e.g. 07:00,12:00,18:00" class="modern-input">
                    </div>

                    <div class="automation-field">
                        <label>Feed Pulse Duration (ms):</label>
                        <input type="number" id="feederPulseMs" placeholder="2000" class="modern-input" value="2000">
                    </div>

                    <div class="automation-field">
                        <label>Minimum Interval Between Feedings (minutes):</label>
                        <input type="number" id="feederMinInterval" placeholder="30" class="modern-input" value="30">
                    </div>

                    <div class="automation-field">
                        <button type="button" class="modern-btn btn-primary" id="addFeederAutomationBtn">
                            <i class="fa fa-plus"></i>
                            <span>Add Feeder Rule</span>
                        </button>
                    </div>
                </form>
                <div style="margin:10px 0;">
                    <label><input type="checkbox" id="globalFeederAutomationEnabled" checked> Enable Feeder Automation (global)</label>
                </div>
                <div class="automation-list" id="feederAutomationList"></div>
            </div>

            <!-- Plug Automation Controls -->
            <div class="automation-panel">
                <h3 class="automation-title">
                    <i class="fa fa-plug"></i>
                    Plug Automation
                </h3>
                <form id="plugAutomationForm" class="automation-form">
                    <div class="automation-field">
                        <label>Plug Number:</label>
                        <select id="plugNumber" class="modern-select">
            <option value="1">Plug 1</option>
            <option value="2">Plug 2</option>
            <option value="3">Plug 3</option>
            <option value="4">Plug 4</option>
          </select>
                    </div>
                    <div class="automation-field">
          <label>Action:</label>
                        <select id="plugAction" class="modern-select">
            <option value="on">Turn ON</option>
            <option value="off">Turn OFF</option>
          </select>
        </div>
                    <div class="automation-field">
                        <label>Start Time:</label>
                        <input type="time" id="plugTimeStart" class="modern-input">
        </div>
                    <div class="automation-field">
                        <label>End Time:</label>
                        <input type="time" id="plugTimeEnd" class="modern-input">
                    </div>
                    <div class="automation-field">
                        <label>Sensor Condition (optional):</label>
                        <select id="plugConditionType" class="modern-select">
                            <option value="">None</option>
                            <option value="temperature">Temperature</option>
                            <option value="humidity">Humidity</option>
                            <option value="ammonia">Ammonia</option>
                        </select>
                    </div>
                    <div class="automation-field">
                        <label>Condition:</label>
                        <select id="plugCond" class="modern-select">
                            <option value=">">Above</option>
                            <option value="<">Below</option>
                        </select>
                        <input type="number" id="plugCondValue" placeholder="Value" class="modern-input">
                    </div>
                    <div class="automation-field">
                        <button type="button" class="modern-btn btn-primary" id="addPlugAutomationBtn">
                            <i class="fa fa-plus"></i>
                            <span>Add Plug Rule</span>
                        </button>
        </div>
      </form>
                <div class="automation-list" id="plugAutomationList"></div>
            </div>
    </div>

        <!-- Control Panels -->
        <div class="control-panels">
            <!-- Connection Panel -->
            <div class="control-panel">
                <h3 class="panel-title">
                    <i class="fa fa-wifi"></i>
                    ESP32 Connection
                </h3>
                <div style="margin-bottom: 15px;">
                    <input id="esp32Ip" type="text" class="modern-input" placeholder="ESP32 IP Address" value="192.168.43.1">
                  </div>
                <button id="connectBtn" class="modern-btn btn-info">
                    <i class="fa fa-plug"></i>
                    <span>Connect</span>
                </button>
                <div id="connectionStatus" class="status-indicator status-disconnected">Disconnected</div>
                  </div>

            <!-- Sensor Data Panel -->
            <div class="control-panel">
                <h3 class="panel-title">
                    <i class="fa fa-thermometer-half"></i>
                    Sensor Readings
                </h3>
                <div id="tempData" style="margin: 10px 0; font-size: 1.1rem; color: #2c3e50;">
                    <strong>Temperature:</strong> <span id="tempValue">-- °C</span>
                </div>
                <div id="humData" style="margin: 10px 0; font-size: 1.1rem; color: #2c3e50;">
                    <strong>Humidity:</strong> <span id="humValue">-- %</span>
                </div>
                <div id="ammoniaData" style="margin: 10px 0; font-size: 1.1rem; color: #2c3e50;">
                    <strong>Ammonia:</strong> <span id="ammoniaValue">--</span>
              </div>
            </div>

            <!-- Feeding Control Panel -->
            <div class="control-panel">
                <h3 class="panel-title">
                    <i class="fa fa-utensils"></i>
                    Feeding Control
                </h3>
                <button id="feedBtn" class="modern-btn btn-warning" disabled>
                    <i class="fa fa-play"></i>
                    <span>Feed Now</span>
                </button>
                <button id="cancelScheduledFeedBtn" class="modern-btn btn-secondary" disabled style="margin-left:10px;">
                    <i class="fa fa-ban"></i>
                    <span>Cancel Pending</span>
                </button>
                <div id="feedStatus" class="status-indicator status-idle">Idle</div>

                <div style="margin-top:10px; font-size:0.9rem;">
                    <strong>Next Scheduled Feed:</strong> <span id="nextFeedInfo">-</span>
                </div>
              </div>
            </div>

        <!-- Charts -->
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fa fa-chart-line"></i>
                Environmental Data Trends
            </h3>
            <div class="chart-wrapper">
                <canvas id="dataChart"></canvas>
              </div>
        </div>

        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fa fa-wind"></i>
                Ammonia Level Monitoring
            </h3>
            <div class="chart-wrapper">
                <canvas id="ammoniaChart"></canvas>
            </div>
          </div>

        <!-- Relay Control Panel -->
        <div class="content-card">
            <h2 class="card-title">
                <i class="fa fa-bolt"></i>
                Relay Control Panel
            </h2>
            <p class="card-subtitle">Manual control of all relay modules and automated devices</p>

            <div class="relay-control">
                <div class="relay-info">
                    <span class="relay-name">Plug 1</span>
                    <span id="relay1Status" class="relay-status relay-off">OFF</span>
                </div>
                <div class="relay-actions">
                    <button id="relay1OnBtn" class="modern-btn btn-success" disabled>
                        <i class="fa fa-power-off"></i>
                        <span>ON</span>
                    </button>
                    <button id="relay1OffBtn" class="modern-btn btn-danger" disabled>
                        <i class="fa fa-stop"></i>
                        <span>OFF</span>
                    </button>
            </div>
          </div>

            <div class="relay-control">
                <div class="relay-info">
                    <span class="relay-name">Plug 2</span>
                    <span id="relay2Status" class="relay-status relay-off">OFF</span>
                </div>
                <div class="relay-actions">
                    <button id="relay2OnBtn" class="modern-btn btn-success" disabled>
                        <i class="fa fa-power-off"></i>
                        <span>ON</span>
                    </button>
                    <button id="relay2OffBtn" class="modern-btn btn-danger" disabled>
                        <i class="fa fa-stop"></i>
                        <span>OFF</span>
                    </button>
                </div>
          </div>

            <div class="relay-control">
                <div class="relay-info">
                    <span class="relay-name">Plug 3</span>
                    <span id="relay3Status" class="relay-status relay-off">OFF</span>
            </div>
                <div class="relay-actions">
                    <button id="relay3OnBtn" class="modern-btn btn-success" disabled>
                        <i class="fa fa-power-off"></i>
                        <span>ON</span>
                    </button>
                    <button id="relay3OffBtn" class="modern-btn btn-danger" disabled>
                        <i class="fa fa-stop"></i>
                        <span>OFF</span>
                    </button>
            </div>
            </div>

            <div class="relay-control">
                <div class="relay-info">
                    <span class="relay-name">Plug 4</span>
                    <span id="relay4Status" class="relay-status relay-off">OFF</span>
            </div>
                <div class="relay-actions">
                    <button id="relay4OnBtn" class="modern-btn btn-success" disabled>
                        <i class="fa fa-power-off"></i>
                        <span>ON</span>
                    </button>
                    <button id="relay4OffBtn" class="modern-btn btn-danger" disabled>
                        <i class="fa fa-stop"></i>
                        <span>OFF</span>
                    </button>
            </div>
          </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e8f5e8;">
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <button id="allRelaysOnBtn" class="modern-btn btn-success" disabled>
                        <i class="fa fa-power-off"></i>
                        <span>Turn ALL ON</span>
                    </button>
                    <button id="allRelaysOffBtn" class="modern-btn btn-danger" disabled>
                        <i class="fa fa-stop"></i>
                        <span>Turn ALL OFF</span>
                    </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let isConnected = false;
let updateInterval;
const connectBtn = document.getElementById('connectBtn');
const feedBtn = document.getElementById('feedBtn');
const cancelScheduledFeedBtn = document.getElementById('cancelScheduledFeedBtn');
const connStatus = document.getElementById('connectionStatus');
const feedStatus = document.getElementById('feedStatus');
const tempData = document.getElementById('tempData');
const humData = document.getElementById('humData');
const ammoniaData = document.getElementById('ammoniaData');
const esp32IpInput = document.getElementById('esp32Ip');

// Relay controls
const relay1OnBtn = document.getElementById('relay1OnBtn');
const relay1OffBtn = document.getElementById('relay1OffBtn');
const relay2OnBtn = document.getElementById('relay2OnBtn');
const relay2OffBtn = document.getElementById('relay2OffBtn');
const relay3OnBtn = document.getElementById('relay3OnBtn');
const relay3OffBtn = document.getElementById('relay3OffBtn');
const relay4OnBtn = document.getElementById('relay4OnBtn');
const relay4OffBtn = document.getElementById('relay4OffBtn');
const relay1Status = document.getElementById('relay1Status');
const relay2Status = document.getElementById('relay2Status');
const relay3Status = document.getElementById('relay3Status');
const relay4Status = document.getElementById('relay4Status');
const allRelaysOnBtn = document.getElementById('allRelaysOnBtn');
const allRelaysOffBtn = document.getElementById('allRelaysOffBtn');

// Feeder automation controls
const feederConditionType = document.getElementById('feederConditionType');
const feederCond = document.getElementById('feederCond');
const feederCondValue = document.getElementById('feederCondValue');
const feederDispenseTimes = document.getElementById('feederDispenseTimes');
const feederScheduleStart = document.getElementById('feederScheduleStart');
const feederScheduleEnd = document.getElementById('feederScheduleEnd');
const feederPulseMsInput = document.getElementById('feederPulseMs');
const feederMinIntervalInput = document.getElementById('feederMinInterval');
const addFeederAutomationBtn = document.getElementById('addFeederAutomationBtn');
const feederAutomationList = document.getElementById('feederAutomationList');
const globalFeederAutomationEnabled = document.getElementById('globalFeederAutomationEnabled');
const nextFeedInfo = document.getElementById('nextFeedInfo');

// Plug automation controls
const plugNumber = document.getElementById('plugNumber');
const plugAction = document.getElementById('plugAction');
const plugTimeStart = document.getElementById('plugTimeStart');
const plugTimeEnd = document.getElementById('plugTimeEnd');
const plugConditionType = document.getElementById('plugConditionType');
const plugCond = document.getElementById('plugCond');
const plugCondValue = document.getElementById('plugCondValue');
const addPlugAutomationBtn = document.getElementById('addPlugAutomationBtn');
const plugAutomationList = document.getElementById('plugAutomationList');

let feederAutomations = [];
let plugAutomations = [];

// Track pending scheduled feed timeouts so user can cancel them
let pendingFeedTimeouts = [];

// Utility: parse HH:MM to minutes since midnight
function hmToMinutes(hm) {
  const [h, m] = hm.split(':').map(x => parseInt(x,10));
  return h*60 + m;
}

// Utility: current time string HH:MM
function nowHHMM() {
  return new Date().toTimeString().slice(0,5);
}

// Add feeder automation rule
addFeederAutomationBtn.addEventListener('click', function() {
  const condType = feederConditionType.value;
  const cond = feederCond.value;
  const condValue = parseFloat(feederCondValue.value);
  const timesStr = feederDispenseTimes.value.trim();
  const start = feederScheduleStart.value;
  const end = feederScheduleEnd.value;
  const pulseMs = parseInt(feederPulseMsInput.value) || 2000;
  const minIntervalMin = parseInt(feederMinIntervalInput.value) || 30;

  let timesArr = [];
  if (timesStr.length > 0) {
    timesArr = timesStr.split(',').map(s => s.trim()).filter(s => s.match(/^\d{2}:\d{2}$/));
  }

  // validation: if condType != none require condValue
  if (condType !== 'none' && isNaN(condValue)) {
    alert('Enter a valid value for feeder condition or select "None".');
    return;
  }

  feederAutomations.push({
    condType,
    cond,
    condValue,
    timesArr,
    start, // could be ""
    end,   // could be ""
    pulseMs,
    minIntervalMin,
    enabled: true,
    lastFiredAt: 0 // epoch ms of last fired => used to prevent repeats
  });
  renderFeederAutomationList();
  scheduleNextFeeds(); // refresh next scheduled feed display
});

function renderFeederAutomationList() {
  feederAutomationList.innerHTML = '';
  feederAutomations.forEach((a, i) => {
    let text = `${a.enabled ? '●' : '○'} If `;
    if (a.condType === 'none') {
      text += `SCHEDULE`;
    } else {
      text += `${a.condType} ${a.cond} ${a.condValue}`;
    }
    if (a.timesArr.length > 0) {
      text += ` at [${a.timesArr.join(', ')}]`;
    } else {
      text += ` (any time)`;
    }
    if (a.start || a.end) {
      text += ` within ${a.start || '00:00'} - ${a.end || '23:59'}`;
    }
    text += ` — pulse ${a.pulseMs}ms, min interval ${a.minIntervalMin}min`;
    feederAutomationList.innerHTML += `
      <div class="automation-item">
        <span>${text}</span>
        <div style="display:inline-block; margin-left:10px;">
          <button type="button" class="modern-btn" onclick="toggleFeederAutomation(${i})">${a.enabled ? 'Disable' : 'Enable'}</button>
          <button type="button" class="remove-btn" onclick="removeFeederAutomation(${i})">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </div>`;
  });
}

window.toggleFeederAutomation = function(idx) {
  feederAutomations[idx].enabled = !feederAutomations[idx].enabled;
  renderFeederAutomationList();
  scheduleNextFeeds();
}

window.removeFeederAutomation = function(idx) {
  feederAutomations.splice(idx, 1);
  renderFeederAutomationList();
  scheduleNextFeeds();
}

// Plug automations
addPlugAutomationBtn.addEventListener('click', function() {
  const plug = plugNumber.value;
  const action = plugAction.value;
  const start = plugTimeStart.value;
  const end = plugTimeEnd.value;
  const condType = plugConditionType.value;
  const cond = plugCond.value;
  const condValue = parseFloat(plugCondValue.value);

    if (!start || !end) {
      alert('Set both start and end time.');
      return;
    }
  let useCond = condType !== "" && !isNaN(condValue);
  plugAutomations.push({
    plug, action, start, end, useCond, condType, cond, condValue, enabled: true
  });
  renderPlugAutomationList();
});

function renderPlugAutomationList() {
  plugAutomationList.innerHTML = '';
  plugAutomations.forEach((a, i) => {
    let text = `Plug ${a.plug}: ${a.action.toUpperCase()} from ${a.start} to ${a.end}`;
    if (a.useCond) {
      text += ` if ${a.condType} ${a.cond} ${a.condValue}`;
    }
    plugAutomationList.innerHTML += `
      <div class="automation-item">
        <span>${text}</span>
        <button type="button" class="remove-btn" onclick="removePlugAutomation(${i})">
          <i class="fa fa-trash"></i>
        </button>
      </div>`;
  });
}

window.removePlugAutomation = function(idx) {
  plugAutomations.splice(idx, 1);
  renderPlugAutomationList();
}

// Chart setup with agricultural green theme
const ctx = document.getElementById('dataChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'Temperature (°C)',
                borderColor: '#56ab2f',
                backgroundColor: 'rgba(86, 171, 47, 0.2)',
                data: [],
                tension: 0.1,
                borderWidth: 3,
                pointBackgroundColor: '#56ab2f',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            },
            {
                label: 'Humidity (%)',
                borderColor: '#a8e6cf',
                backgroundColor: 'rgba(168, 230, 207, 0.2)',
                data: [],
                tension: 0.1,
                borderWidth: 3,
                pointBackgroundColor: '#a8e6cf',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#2c3e50',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            }
        },
        scales: { 
            y: { 
                beginAtZero: false,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    color: '#2c3e50',
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    color: '#2c3e50',
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Ammonia Chart with agricultural green theme
const ammoniaCtx = document.getElementById('ammoniaChart').getContext('2d');
const ammoniaChart = new Chart(ammoniaCtx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'Ammonia Level (raw)',
                borderColor: '#2d5016',
                backgroundColor: 'rgba(45, 80, 22, 0.2)',
                data: [],
                tension: 0.1,
                borderWidth: 3,
                pointBackgroundColor: '#2d5016',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#2c3e50',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            }
        },
        scales: { 
            y: { 
                beginAtZero: false,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    color: '#2c3e50',
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                },
                ticks: {
                    color: '#2c3e50',
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Connect to ESP32
connectBtn.addEventListener('click', async () => {
    if (!isConnected) {
        try {
            // Test connection by fetching data
            const response = await fetch(`http://${esp32IpInput.value}/data`);
            if (!response.ok) throw new Error("Connection failed");
            
            const data = await response.json();
            updateUI(data);
            
            isConnected = true;
            connectBtn.innerHTML = '<i class="fa fa-unlink"></i><span>Disconnect</span>';
            connStatus.textContent = 'Connected';
            connStatus.className = 'status-indicator status-connected';
            document.getElementById('connectionStatusText').textContent = 'Connected';

            feedBtn.disabled = false;
            cancelScheduledFeedBtn.disabled = false;
            enableRelayBtns(true);

            // Start regular updates
            updateInterval = setInterval(updateData, 2000);
        } catch (error) {
            console.error('Connection error:', error);
            alert('Failed to connect: ' + error.message);
        }
    } else {
        disconnect();
    }
});

function disconnect() {
    clearInterval(updateInterval);
    isConnected = false;
    connectBtn.innerHTML = '<i class="fa fa-plug"></i><span>Connect</span>';
    connStatus.textContent = 'Disconnected';
    connStatus.className = 'status-indicator status-disconnected';
    document.getElementById('connectionStatusText').textContent = 'Disconnected';

    feedBtn.disabled = true;
    cancelScheduledFeedBtn.disabled = true;
    enableRelayBtns(false);
    // clear pending timeouts when disconnected
    pendingFeedTimeouts.forEach(t => clearTimeout(t));
    pendingFeedTimeouts = [];
}

function enableRelayBtns(enable) {
  relay1OnBtn.disabled = !enable;
  relay1OffBtn.disabled = !enable;
  relay2OnBtn.disabled = !enable;
  relay2OffBtn.disabled = !enable;
  relay3OnBtn.disabled = !enable;
  relay3OffBtn.disabled = !enable;
  relay4OnBtn.disabled = !enable;
  relay4OffBtn.disabled = !enable;
  allRelaysOnBtn.disabled = !enable;
  allRelaysOffBtn.disabled = !enable;
}

async function updateData() {
    try {
        const response = await fetch(`http://${esp32IpInput.value}/data`);
        if (!response.ok) throw new Error("Update failed");
        
        const data = await response.json();
        updateUI(data);
        runFeederAutomations(data);
        runPlugAutomations(data);
        scheduleNextFeeds();
    } catch (error) {
        console.error('Update error:', error);
        disconnect();
    }
}

function updateUI(data) {
    // Update statistics cards
    document.getElementById('tempDisplay').textContent = `${data.temperature.toFixed(1)}°C`;
    document.getElementById('humDisplay').textContent = `${data.humidity.toFixed(1)}%`;
    document.getElementById('ammoniaDisplay').textContent = data.ammonia;
    
    // Update detailed sensor readings
    document.getElementById('tempValue').textContent = `${data.temperature.toFixed(1)} °C`;
    document.getElementById('humValue').textContent = `${data.humidity.toFixed(1)} %`;
    document.getElementById('ammoniaValue').textContent = data.ammonia;

    // Update feed status
    const feedStatusElem = document.getElementById('feedStatus');
    feedStatusElem.textContent = data.feeder ? "Feeder ON" : "Idle";
    feedStatusElem.className = data.feeder ? 'status-indicator status-active' : 'status-indicator status-idle';

    // Update relay statuses
    const relays = [
        { status: document.getElementById('relay1Status'), onBtn: relay1OnBtn, offBtn: relay1OffBtn, state: data.relay1 },
        { status: document.getElementById('relay2Status'), onBtn: relay2OnBtn, offBtn: relay2OffBtn, state: data.relay2 },
        { status: document.getElementById('relay3Status'), onBtn: relay3OnBtn, offBtn: relay3OffBtn, state: data.relay3 },
        { status: document.getElementById('relay4Status'), onBtn: relay4OnBtn, offBtn: relay4OffBtn, state: data.relay4 }
    ];

    let activeRelays = 0;
    relays.forEach(relay => {
        relay.status.textContent = relay.state ? "ON" : "OFF";
        relay.status.className = relay.state ? 'relay-status relay-on' : 'relay-status relay-off';
        relay.onBtn.disabled = relay.state;
        relay.offBtn.disabled = !relay.state;
        if (relay.state) activeRelays++;
    });

    // Update active relays count
    document.getElementById('activeRelaysDisplay').textContent = `${activeRelays}/4`;

    updateChart(data.temperature, data.humidity);
    updateAmmoniaChart(data.ammonia);
}

function updateChart(temp, hum) {
    const now = new Date().toLocaleTimeString();
    chart.data.labels.push(now);
    chart.data.datasets[0].data.push(temp);
    chart.data.datasets[1].data.push(hum);

    if (chart.data.labels.length > 15) {
        chart.data.labels.shift();
        chart.data.datasets[0].data.shift();
        chart.data.datasets[1].data.shift();
    }

    chart.update();
}

function updateAmmoniaChart(ammonia) {
    const now = new Date().toLocaleTimeString();
    ammoniaChart.data.labels.push(now);
    ammoniaChart.data.datasets[0].data.push(ammonia);

    if (ammoniaChart.data.labels.length > 15) {
        ammoniaChart.data.labels.shift();
        ammoniaChart.data.datasets[0].data.shift();
    }

    ammoniaChart.update();
}

async function sendCommand(cmd) {
    if (!isConnected) return;
    try {
        const response = await fetch(`http://${esp32IpInput.value}/${cmd}`);
        if (!response.ok) throw new Error("Command failed");
    } catch (error) {
        console.error('Command error:', error);
        disconnect();
    }
}

// Button actions
feedBtn.addEventListener('click', () => {
    // manual feed; respect global automation disabled or not
    feedStatus.textContent = 'Feeding...';
    feedStatus.className = 'w3-panel w3-orange w3-round';
    sendCommand('feeder_on');
    const pulseMs = 2000;
    setTimeout(() => {
      sendCommand('feeder_off');
    }, pulseMs);
});

cancelScheduledFeedBtn.addEventListener('click', () => {
  pendingFeedTimeouts.forEach(t => clearTimeout(t));
  pendingFeedTimeouts = [];
  nextFeedInfo.textContent = '-';
  alert('Cancelled pending scheduled feeds.');
});

relay1OnBtn.addEventListener('click', () => sendCommand('relay1_on'));
relay1OffBtn.addEventListener('click', () => sendCommand('relay1_off'));
relay2OnBtn.addEventListener('click', () => sendCommand('relay2_on'));
relay2OffBtn.addEventListener('click', () => sendCommand('relay2_off'));
relay3OnBtn.addEventListener('click', () => sendCommand('relay3_on'));
relay3OffBtn.addEventListener('click', () => sendCommand('relay3_off'));
relay4OnBtn.addEventListener('click', () => sendCommand('relay4_on'));
relay4OffBtn.addEventListener('click', () => sendCommand('relay4_off'));
allRelaysOnBtn.addEventListener('click', () => {
    sendCommand('relay1_on');
    sendCommand('relay2_on');
    sendCommand('relay3_on');
    sendCommand('relay4_on');
});
allRelaysOffBtn.addEventListener('click', () => {
    sendCommand('relay1_off');
    sendCommand('relay2_off');
    sendCommand('relay3_off');
    sendCommand('relay4_off');
});

// --- Feeder Automation engine ---
// Now feeder rules are more powerful: optional condition, optional schedule window, times list, pulse duration, min interval
function runFeederAutomations(data) {
  if (!globalFeederAutomationEnabled.checked) return;

  const now = new Date();
  const nowStr = nowHHMM();
  const nowMs = now.getTime();

  feederAutomations.forEach(a => {
    if (!a.enabled) return;

    // Check schedule window if provided
    let inScheduleWindow = true;
    if (a.start || a.end) {
      if (a.start && a.end) {
        if (a.start < a.end) {
          inScheduleWindow = nowStr >= a.start && nowStr <= a.end;
        } else {
          // overnight window
          inScheduleWindow = nowStr >= a.start || nowStr <= a.end;
        }
      } else if (a.start) {
        inScheduleWindow = nowStr >= a.start;
      } else if (a.end) {
        inScheduleWindow = nowStr <= a.end;
      }
    }

    if (!inScheduleWindow) return;

    // Check sensor condition (if any)
    let condMet = true;
    if (a.condType && a.condType !== 'none') {
      if (a.condType === 'temperature') {
        condMet = (a.cond === '>' ? data.temperature > a.condValue : data.temperature < a.condValue);
      } else if (a.condType === 'humidity') {
        condMet = (a.cond === '>' ? data.humidity > a.condValue : data.humidity < a.condValue);
      } else if (a.condType === 'ammonia') {
        condMet = (a.cond === '>' ? data.ammonia > a.condValue : data.ammonia < a.condValue);
      }
    }

    if (!condMet) return;

    // Ensure min interval since last fired
    const minIntervalMs = (a.minIntervalMin || 0) * 60 * 1000;
    if (a.lastFiredAt && (nowMs - a.lastFiredAt) < minIntervalMs) {
      return; // skip, too soon
    }

    // If timesArr present, trigger only at matching times (exact match)
    if (a.timesArr && a.timesArr.length > 0) {
      a.timesArr.forEach(feedTime => {
        if (nowStr === feedTime) {
          // Trigger feed
          fireFeeder(a);
        }
      });
    } else {
      // No explicit times: treat as event-based (on condition) -> trigger once when condition is met,
      // but avoid repeating continuously by using lastFiredAt and a small debounce window (1 minute)
      const debounceMs = 60 * 1000;
      if (!a._condPreviouslyMet) {
        // first time we see it true, fire
        fireFeeder(a);
      }
      a._condPreviouslyMet = true;
      // We'll clear _condPreviouslyMet when condition becomes false next update
    }
  });

  // For rules without times, clear their _condPreviouslyMet flag when condition no longer met
  feederAutomations.forEach(a => {
    if (a.condType && a.condType !== 'none' && a.timesArr.length === 0) {
      let condMetNow = true;
      if (a.condType === 'temperature') {
        condMetNow = (a.cond === '>' ? data.temperature > a.condValue : data.temperature < a.condValue);
      } else if (a.condType === 'humidity') {
        condMetNow = (a.cond === '>' ? data.humidity > a.condValue : data.humidity < a.condValue);
      } else if (a.condType === 'ammonia') {
        condMetNow = (a.cond === '>' ? data.ammonia > a.condValue : data.ammonia < a.condValue);
      }
      if (!condMetNow) a._condPreviouslyMet = false;
    }
  });
}

function fireFeeder(rule) {
  // mark last fired
  rule.lastFiredAt = Date.now();
  // human-visible status
  feedStatus.textContent = 'Feeding (auto)...';
  feedStatus.className = 'status-indicator status-active';
  // send commands
  sendCommand('feeder_on');
  setTimeout(() => {
    sendCommand('feeder_off');
    // restore idle after short delay
    setTimeout(() => {
      feedStatus.textContent = 'Idle';
      feedStatus.className = 'status-indicator status-idle';
    }, 500);
  }, rule.pulseMs || 2000);
}

// --- Plug Automation engine ---
function runPlugAutomations(data) {
  const now = new Date();
  const nowStr = nowHHMM();
  plugAutomations.forEach(a => {
    if (!a.enabled) return;

    let timeMet = false;
    if (a.start < a.end) { // e.g. 08:00-17:00
      timeMet = nowStr >= a.start && nowStr <= a.end;
    } else { // overnight e.g. 23:00-06:00
      timeMet = nowStr >= a.start || nowStr <= a.end;
    }
    let condMet = true;
    if (a.useCond) {
      if (a.condType === 'temperature') {
        condMet = (a.cond === '>' ? data.temperature > a.condValue : data.temperature < a.condValue);
      } else if (a.condType === 'humidity') {
        condMet = (a.cond === '>' ? data.humidity > a.condValue : data.humidity < a.condValue);
      } else if (a.condType === 'ammonia') {
        condMet = (a.cond === '>' ? data.ammonia > a.condValue : data.ammonia < a.condValue);
      }
    }
    if (timeMet && condMet) {
      setPlugState(a.plug, a.action);
    }
  });
}

function setPlugState(plug, action) {
  const status = window[`relay${plug}Status`].textContent;
  if (action === 'on' && status !== 'ON') sendCommand(`relay${plug}_on`);
  if (action === 'off' && status !== 'OFF') sendCommand(`relay${plug}_off`);
}

// Scheduling helper: compute next scheduled feed (for display) and set timeouts for upcoming exact times
function scheduleNextFeeds() {
  // Clear previous pending timeouts
  pendingFeedTimeouts.forEach(t => clearTimeout(t));
  pendingFeedTimeouts = [];

  if (!isConnected || !globalFeederAutomationEnabled.checked) {
    nextFeedInfo.textContent = '-';
    return;
  }

  const now = new Date();
  const nowMs = now.getTime();
  let nextFeed = null; // {whenMs, ruleIndex, timeStr}

  feederAutomations.forEach((a, idx) => {
    if (!a.enabled) return;

    // If rule has explicit times, schedule them (today or tomorrow)
    if (a.timesArr && a.timesArr.length > 0) {
      a.timesArr.forEach(tStr => {
        const [hh, mm] = tStr.split(':').map(Number);
        const candidate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hh, mm, 0, 0);
        // If candidate in past for today, make it tomorrow
        if (candidate.getTime() <= nowMs) candidate.setDate(candidate.getDate() + 1);

        // Check schedule window if provided (start/end)
        let inScheduleWindow = true;
        if (a.start || a.end) {
          const candidateHHMM = tStr;
          if (a.start && a.end) {
            if (a.start < a.end) {
              inScheduleWindow = candidateHHMM >= a.start && candidateHHMM <= a.end;
            } else {
              inScheduleWindow = candidateHHMM >= a.start || candidateHHMM <= a.end;
            }
          } else if (a.start) {
            inScheduleWindow = candidateHHMM >= a.start;
          } else if (a.end) {
            inScheduleWindow = candidateHHMM <= a.end;
          }
        }
        if (!inScheduleWindow) return;

        const whenMs = candidate.getTime();
        // respect min interval: if lastFiredAt exists and next candidate is too soon, skip scheduling it
        if (a.lastFiredAt && (whenMs - a.lastFiredAt) < (a.minIntervalMin*60*1000)) return;

        if (!nextFeed || whenMs < nextFeed.whenMs) {
          nextFeed = {whenMs, ruleIndex: idx, timeStr: tStr};
        }
      });
    } else {
      // No explicit times: cannot predict exact next event (event-driven). Display schedule window earliest time if available
      if (a.start) {
        // compute candidate today at start
        const [hh, mm] = a.start.split(':').map(Number);
        let candidate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hh, mm, 0, 0);
        if (candidate.getTime() <= nowMs) candidate.setDate(candidate.getDate() + 1);
        const whenMs = candidate.getTime();
        if (!nextFeed || whenMs < nextFeed.whenMs) {
          nextFeed = {whenMs, ruleIndex: idx, timeStr: `${a.start} (start window)`};
        }
      }
    }
  });

  if (nextFeed) {
    const msUntil = nextFeed.whenMs - nowMs;
    nextFeedInfo.textContent = `Next: ${new Date(nextFeed.whenMs).toLocaleString()}`;
    // set a timeout to fire the feed when its time arrives but only if still valid then
    const to = setTimeout(() => {
      // Get fresh rule and verify it's still enabled and conditions/schedule allow it
      const rule = feederAutomations[nextFeed.ruleIndex];
      if (!rule || !rule.enabled) return;
      // check min interval
      if (rule.lastFiredAt && (Date.now() - rule.lastFiredAt) < (rule.minIntervalMin*60*1000)) return;
      // For rules with times, ensure the time matches
      if (rule.timesArr && rule.timesArr.length > 0 && !rule.timesArr.includes(nextFeed.timeStr)) return;
      // Fire feeder
      fireFeeder(rule);
      scheduleNextFeeds(); // schedule next after firing
    }, msUntil);
    pendingFeedTimeouts.push(to);
  } else {
    nextFeedInfo.textContent = '-';
  }
}

// Periodically refresh next feed display (every minute)
setInterval(scheduleNextFeeds, 60*1000);

</script>

<?php include 'theme/foot.php'; ?>