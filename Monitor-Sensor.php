<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<style>
/* Dashboard Modern UI Styles (same as previous improved code) */
.dashboard-main {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  min-height: 100vh;
  background: #f7f8fa;
  padding: 0 0 40px 0;
}
.dashboard-header {
  background: #38598b;
  color: #fff;
  border-radius: 0 0 18px 18px;
  margin-bottom: 34px;
  padding: 28px 34px 18px 34px;
  box-shadow: 0 4px 24px -10px #38598b40;
}
.dashboard-title h2 {
  font-size: 2.0rem;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin-bottom: 0;
}
.dashboard-card {
  background: #fff;
  border-radius: 17px;
  box-shadow: 0 4px 22px -8px #38598b18;
  padding: 28px 26px 18px 26px;
  min-height: 0;
  margin-bottom: 0;
}
@media (max-width: 1100px) {
  .dashboard-card { min-width: unset; max-width: 99vw; }
}
@media (max-width: 768px) {
  .dashboard-main { margin-left: 0; padding: 0 0 10px 0; }
  .dashboard-header { padding: 21px 8px 14px 8px; }
}
.w3-panel { margin-bottom: 16px; }
.w3-card h2, .w3-card h3 { color: #38598b; font-weight: 700; }
.w3-button {
  font-size: 1.09rem !important;
  font-weight: 600 !important;
  border-radius: 8px !important;
  box-shadow: 0 2px 8px -2px #38598b18 !important;
}
.w3-button.w3-green { background: #44b78b !important; color: #fff !important; }
.w3-button.w3-green:hover { background: #319b70 !important; }
.w3-button.w3-red { background: #e74c3c !important; color: #fff !important; }
.w3-button.w3-red:hover { background: #b93222 !important; }
.w3-button.w3-orange { background: #f7b731 !important; color: #fff !important; }
.w3-button.w3-orange:hover { background: #f5a623 !important; }
.w3-button.w3-blue { background: #38598b !important; color: #fff !important; }
.w3-button.w3-blue:hover { background: #2c406b !important; }
.automation-panel h4, .automation-panel label {
  color: #38598b;
  font-weight: 700;
}
.automation-panel {
  background: #f3f6fb;
  border-radius: 11px;
  padding: 18px 20px;
  margin-bottom: 18px;
  box-shadow: 0 2px 8px -2px #38598b10;
}
.automation-panel input, .automation-panel select {
  border-radius: 6px;
  border: 1px solid #b4c7e7;
  font-size: 1.10rem;
  padding: 6px 12px;
  margin-top: 5px;
  margin-bottom: 12px;
  width: 100%;
}
.automation-panel .w3-button {
  margin-top: 6px;
}
.automation-list {
  margin-top: 16px;
}
.automation-list-item {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 4px -2px #38598b18;
  padding: 12px 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
}
.automation-list-item .remove-btn {
  background: #e74c3c !important;
  color: #fff !important;
  border-radius: 6px !important;
  padding: 2px 10px !important;
  font-size: 0.92rem !important;
}
</style>

<!-- !PAGE CONTENT! -->
<div class="dashboard-main" style="margin-left:320px;margin-top:50px;">
  <header class="dashboard-header">
    <div class="dashboard-title">
      <h2><b><i class="fa fa-dashboard"></i> Pig Management & Environmental Monitoring</b></h2>
    </div>
  </header>
  <div class="dashboard-card" style="margin:38px 38px 0 38px;">

    <!-- Automation Controls -->
    <div class="automation-panel">
      <h4><i class="fa fa-magic"></i> Automation & Scheduling</h4>
      <form id="automationForm" style="display:flex;gap:18px;flex-wrap:wrap;">
        <div style="flex:1;min-width:220px;">
          <label>Conditional Automation:</label>
          <select id="automationType">
            <option value="fan">Fan (Temperature Based)</option>
            <option value="plug">Plug (Time Based)</option>
            <option value="feeder">Feeder (Scheduled Dispense)</option>
          </select>
        </div>
        <div style="flex:1;min-width:220px;" id="automationFanFields">
          <label>If Temperature</label>
          <select id="tempCondition">
            <option value=">">Above</option>
            <option value="<">Below</option>
          </select>
          <input type="number" id="tempValue" placeholder="Value (°C)">
          <label>Then</label>
          <select id="fanAction">
            <option value="on">Turn Fan ON</option>
            <option value="off">Turn Fan OFF</option>
          </select>
        </div>
        <div style="flex:1;min-width:220px;display:none;" id="automationPlugFields">
          <label>Plug #</label>
          <select id="plugNumber">
            <option value="1">Plug 1</option>
            <option value="2">Plug 2</option>
            <option value="3">Plug 3</option>
            <option value="4">Plug 4</option>
          </select>
          <label>From:</label>
          <input type="time" id="plugTimeStart">
          <label>To:</label>
          <input type="time" id="plugTimeEnd">
          <label>Action:</label>
          <select id="plugAction">
            <option value="on">Turn ON</option>
            <option value="off">Turn OFF</option>
          </select>
        </div>
        <div style="flex:1;min-width:220px;display:none;" id="automationFeederFields">
          <label>Times Per Day</label>
          <input type="number" id="feedTimes" min="1" max="10" placeholder="Times per day">
          <label>Dispense Times (HH:MM, comma separated)</label>
          <input type="text" id="feedDispenseTimes" placeholder="e.g. 07:00,12:00,18:00">
        </div>
        <div style="flex:0 0 180px;align-self:flex-end;">
          <button type="button" class="w3-button w3-blue" id="addAutomationBtn"><i class="fa fa-plus"></i> Add Automation</button>
        </div>
      </form>
      <div class="automation-list" id="automationList"></div>
    </div>

    <!-- Panels -->
    <div class="w3-row-padding" style="margin-bottom:20px">
      <div class="w3-col l12">
        <div>
          <div class="w3-row-padding">

            <!-- Connection Panel -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3><i class="fa fa-plug"></i> ESP32 Connection</h3>
                <div class="w3-row">
                  <div class="w3-col s8">
                    <input id="esp32Ip" type="text" class="w3-input w3-border" placeholder="ESP32 IP Address" value="192.168.43.1">
                  </div>
                  <div class="w3-col s4">
                    <button id="connectBtn" class="w3-button w3-blue">Connect</button>
                  </div>
                </div>
                <div id="connectionStatus" class="w3-panel w3-red w3-round">Disconnected</div>
              </div>
            </div>

            <!-- Fan Control -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3><i class="fa fa-fan"></i> Fan Control</h3>
                <button id="fanOnBtn" class="w3-button w3-green" disabled>Turn ON</button>
                <button id="fanOffBtn" class="w3-button w3-red" disabled>Turn OFF</button>
                <div id="fanStatus" class="w3-panel w3-red w3-round">Fan: OFF</div>
              </div>
            </div>

            <!-- Sensor Data -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3><i class="fa fa-thermometer-half"></i> Sensor Data</h3>
                <div id="tempData">Temperature: -- °C</div>
                <div id="humData">Humidity: -- %</div>
              </div>
            </div>
          </div>

          <!-- Chart -->
          <div class="w3-panel w3-white w3-padding w3-border" style="margin-top:20px">
            <h3><i class="fa fa-chart-line"></i> Environmental Data Chart</h3>
            <div style="height:300px">
              <canvas id="dataChart"></canvas>
            </div>
          </div>

          <!-- Feeding Control -->
          <div class="w3-panel w3-white w3-padding w3-border" style="margin-top:20px">
            <h3><i class="fa fa-utensils"></i> Feeding Control</h3>
            <button id="feedBtn" class="w3-button w3-orange" disabled>Feed Now</button>
            <div id="feedStatus" class="w3-panel w3-yellow w3-round">Idle</div>
          </div>

          <!-- Relay Control Panel -->
          <div class="w3-panel w3-white w3-padding w3-border" style="margin-top:20px">
            <h3><i class="fa fa-bolt"></i> Plug Control (Relay Module)</h3>
            <div>
              Plug 1: <span id="relay1Status">OFF</span>
              <button id="relay1OnBtn" class="w3-button w3-green" disabled>ON</button>
              <button id="relay1OffBtn" class="w3-button w3-red" disabled>OFF</button>
            </div>
            <div style="margin-top:10px">
              Plug 2: <span id="relay2Status">OFF</span>
              <button id="relay2OnBtn" class="w3-button w3-green" disabled>ON</button>
              <button id="relay2OffBtn" class="w3-button w3-red" disabled>OFF</button>
            </div>
            <div style="margin-top:10px">
              Plug 3: <span id="relay3Status">OFF</span>
              <button id="relay3OnBtn" class="w3-button w3-green" disabled>ON</button>
              <button id="relay3OffBtn" class="w3-button w3-red" disabled>OFF</button>
            </div>
            <div style="margin-top:10px">
              Plug 4: <span id="relay4Status">OFF</span>
              <button id="relay4OnBtn" class="w3-button w3-green" disabled>ON</button>
              <button id="relay4OffBtn" class="w3-button w3-red" disabled>OFF</button>
            </div>
            <div style="margin-top:20px">
              <b>All Relays:</b>
              <button id="allRelaysOnBtn" class="w3-button w3-green" disabled>Turn ALL ON</button>
              <button id="allRelaysOffBtn" class="w3-button w3-red" disabled>Turn ALL OFF</button>
            </div>
          </div>

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
const fanOnBtn = document.getElementById('fanOnBtn');
const fanOffBtn = document.getElementById('fanOffBtn');
const feedBtn = document.getElementById('feedBtn');
const connStatus = document.getElementById('connectionStatus');
const fanStatus = document.getElementById('fanStatus');
const feedStatus = document.getElementById('feedStatus');
const tempData = document.getElementById('tempData');
const humData = document.getElementById('humData');
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

// Automation controls
const automationType = document.getElementById('automationType');
const automationFanFields = document.getElementById('automationFanFields');
const automationPlugFields = document.getElementById('automationPlugFields');
const automationFeederFields = document.getElementById('automationFeederFields');
const addAutomationBtn = document.getElementById('addAutomationBtn');
const automationList = document.getElementById('automationList');

let automations = [];

automationType.addEventListener('change', function() {
  if (automationType.value === 'fan') {
    automationFanFields.style.display = '';
    automationPlugFields.style.display = 'none';
    automationFeederFields.style.display = 'none';
  } else if (automationType.value === 'plug') {
    automationFanFields.style.display = 'none';
    automationPlugFields.style.display = '';
    automationFeederFields.style.display = 'none';
  } else if (automationType.value === 'feeder') {
    automationFanFields.style.display = 'none';
    automationPlugFields.style.display = 'none';
    automationFeederFields.style.display = '';
  }
});

// Add automation
addAutomationBtn.addEventListener('click', function() {
  if (automationType.value === 'fan') {
    const cond = document.getElementById('tempCondition').value;
    const temp = parseFloat(document.getElementById('tempValue').value);
    const action = document.getElementById('fanAction').value;
    if (isNaN(temp)) {
      alert('Enter temperature value for automation.');
      return;
    }
    automations.push({
      type: 'fan',
      cond, temp, action
    });
  } else if (automationType.value === 'plug') {
    const plug = document.getElementById('plugNumber').value;
    const start = document.getElementById('plugTimeStart').value;
    const end = document.getElementById('plugTimeEnd').value;
    const action = document.getElementById('plugAction').value;
    if (!start || !end) {
      alert('Set both start and end time.');
      return;
    }
    automations.push({
      type: 'plug',
      plug, start, end, action
    });
  } else if (automationType.value === 'feeder') {
    const feedTimes = parseInt(document.getElementById('feedTimes').value);
    const timesStr = document.getElementById('feedDispenseTimes').value;
    if (isNaN(feedTimes) || feedTimes < 1) {
      alert('Enter valid number of times to feed per day.');
      return;
    }
    // Validate timesStr: comma separated HH:MM
    const timesArr = timesStr.split(',').map(s => s.trim()).filter(s => s.match(/^\d{2}:\d{2}$/));
    if (timesArr.length !== feedTimes) {
      alert('Number of dispense times must match "Times Per Day".');
      return;
    }
    automations.push({
      type: 'feeder',
      feedTimes, timesArr
    });
  }
  renderAutomationList();
});

function renderAutomationList() {
  automationList.innerHTML = '';
  automations.forEach((a, i) => {
    let text = '';
    if (a.type === 'fan') {
      text = `If temperature ${a.cond} ${a.temp}°C, then ${a.action === 'on' ? 'turn fan ON' : 'turn fan OFF'}`;
    } else if (a.type === 'plug') {
      text = `Plug ${a.plug}: ${a.action === 'on' ? 'ON' : 'OFF'} from ${a.start} to ${a.end}`;
    } else if (a.type === 'feeder') {
      text = `Feeder: ${a.feedTimes}x per day at [${a.timesArr.join(', ')}]`;
    }
    automationList.innerHTML += `
      <div class="automation-list-item">
        <span>${text}</span>
        <button type="button" class="w3-button remove-btn" onclick="removeAutomation(${i})"><i class="fa fa-trash"></i></button>
      </div>`;
  });
}

window.removeAutomation = function(idx) {
  automations.splice(idx, 1);
  renderAutomationList();
}

// Chart setup
const ctx = document.getElementById('dataChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'Temperature (°C)',
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                data: [],
                tension: 0.1
            },
            {
                label: 'Humidity (%)',
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                data: [],
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: false } }
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
            connectBtn.textContent = 'Disconnect';
            connStatus.textContent = 'Connected';
            connStatus.className = 'w3-panel w3-green w3-round';

            fanOnBtn.disabled = false;
            fanOffBtn.disabled = false;
            feedBtn.disabled = false;
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
    connectBtn.textContent = 'Connect';
    connStatus.textContent = 'Disconnected';
    connStatus.className = 'w3-panel w3-red w3-round';

    fanOnBtn.disabled = true;
    fanOffBtn.disabled = true;
    feedBtn.disabled = true;
    enableRelayBtns(false);
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
        runAutomations(data);
    } catch (error) {
        console.error('Update error:', error);
        disconnect();
    }
}

function updateUI(data) {
    tempData.textContent = `Temperature: ${data.temperature.toFixed(1)} °C`;
    humData.textContent = `Humidity: ${data.humidity.toFixed(1)} %`;

    fanStatus.textContent = `Fan: ${data.fan ? "ON" : "OFF"}`;
    fanStatus.className = data.fan ? 'w3-panel w3-green w3-round' : 'w3-panel w3-red w3-round';
    fanOnBtn.disabled = data.fan;
    fanOffBtn.disabled = !data.fan;

    feedStatus.textContent = data.feeding ? "Feeding..." : "Ready";
    feedStatus.className = data.feeding ? 'w3-panel w3-orange w3-round' : 'w3-panel w3-yellow w3-round';

    relay1Status.textContent = data.relay1 ? "ON" : "OFF";
    relay1OnBtn.disabled = data.relay1;
    relay1OffBtn.disabled = !data.relay1;
    relay2Status.textContent = data.relay2 ? "ON" : "OFF";
    relay2OnBtn.disabled = data.relay2;
    relay2OffBtn.disabled = !data.relay2;
    relay3Status.textContent = data.relay3 ? "ON" : "OFF";
    relay3OnBtn.disabled = data.relay3;
    relay3OffBtn.disabled = !data.relay3;
    relay4Status.textContent = data.relay4 ? "ON" : "OFF";
    relay4OnBtn.disabled = data.relay4;
    relay4OffBtn.disabled = !data.relay4;

    updateChart(data.temperature, data.humidity);
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
fanOnBtn.addEventListener('click', () => sendCommand('fan_on'));
fanOffBtn.addEventListener('click', () => sendCommand('fan_off'));
feedBtn.addEventListener('click', () => {
    feedStatus.textContent = 'Feeding...';
    feedStatus.className = 'w3-panel w3-orange w3-round';
    sendCommand('feed');
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

// --- Automation engine ---
function runAutomations(data) {
  const now = new Date();
  const nowStr = now.toTimeString().slice(0,5); // HH:MM
  automations.forEach(a => {
    if (a.type === 'fan') {
      if (a.cond === '>' && data.temperature > a.temp && a.action === 'on' && !data.fan) {
        sendCommand('fan_on');
      }
      if (a.cond === '>' && data.temperature > a.temp && a.action === 'off' && data.fan) {
        sendCommand('fan_off');
      }
      if (a.cond === '<' && data.temperature < a.temp && a.action === 'on' && !data.fan) {
        sendCommand('fan_on');
      }
      if (a.cond === '<' && data.temperature < a.temp && a.action === 'off' && data.fan) {
        sendCommand('fan_off');
      }
    }
    if (a.type === 'plug') {
      // If current time is within start/end, set plug to desired state
      if (a.start < a.end) { // normal day range
        if (nowStr >= a.start && nowStr <= a.end) {
          setPlugState(a.plug, a.action);
        }
      } else { // overnight range (e.g. 23:00 to 06:00)
        if (nowStr >= a.start || nowStr <= a.end) {
          setPlugState(a.plug, a.action);
        }
      }
    }
    if (a.type === 'feeder') {
      // dispense at exact times
      a.timesArr.forEach(feedTime => {
        if (nowStr === feedTime) {
          sendCommand('feed');
        }
      });
    }
  });
}

function setPlugState(plug, action) {
  const status = window[`relay${plug}Status`].textContent;
  if (action === 'on' && status !== 'ON') sendCommand(`relay${plug}_on`);
  if (action === 'off' && status !== 'OFF') sendCommand(`relay${plug}_off`);
}

</script>

<?php include 'theme/foot.php'; ?>