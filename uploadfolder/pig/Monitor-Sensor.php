<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Pig Management & Environmental Monitoring</b></h5>
  </header>

  <div class="w3-container">
    <!-- Arduino Monitoring Panel -->
    <div class="w3-row-padding" style="margin-bottom:20px">
      <div class="w3-col l12">
        <div class="w3-card w3-white w3-padding">
          <h2><i class="fa fa-thermometer-half"></i> Environmental Monitoring</h2>
          
          <div class="w3-row-padding">
            <!-- Connection Panel -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3>Arduino Connection</h3>
                <button id="connectBtn" class="w3-button w3-blue">Connect to Arduino</button>
                <div id="connectionStatus" class="w3-panel w3-red w3-round">Disconnected</div>
              </div>
            </div>
            
            <!-- Fan Control Panel -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3>Fan Control</h3>
                <button id="fanOnBtn" class="w3-button w3-green" disabled>Turn ON</button>
                <button id="fanOffBtn" class="w3-button w3-red" disabled>Turn OFF</button>
                <div id="fanStatus" class="w3-panel w3-red w3-round">Fan: OFF</div>
              </div>
            </div>
            
            <!-- Sensor Data Panel -->
            <div class="w3-col l4">
              <div class="w3-panel w3-white w3-padding w3-border">
                <h3>Sensor Data</h3>
                <div id="tempData">Temperature: -- °C</div>
                <div id="humData">Humidity: -- %</div>
              </div>
            </div>
          </div>
          
          <!-- Chart Panel -->
          <div class="w3-panel w3-white w3-padding w3-border" style="margin-top:20px">
            <h3>Environmental Data Chart</h3>
            <div style="height:300px">
              <canvas id="dataChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Original Pig Management Content -->
   

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
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

  // Serial variables
  let port;
  let reader;
  let writer;
  let isConnected = false;

  // DOM elements
  const connectBtn = document.getElementById('connectBtn');
  const fanOnBtn = document.getElementById('fanOnBtn');
  const fanOffBtn = document.getElementById('fanOffBtn');
  const connStatus = document.getElementById('connectionStatus');
  const fanStatus = document.getElementById('fanStatus');
  const tempData = document.getElementById('tempData');
  const humData = document.getElementById('humData');

  // Connect to Arduino
  connectBtn.addEventListener('click', async () => {
      if (!isConnected) {
          try {
              port = await navigator.serial.requestPort();
              await port.open({ baudRate: 9600 });
              
              const decoder = new TextDecoderStream();
              const inputStream = port.readable.pipeThrough(decoder);
              reader = inputStream.getReader();
              writer = port.writable.getWriter();
              
              isConnected = true;
              connectBtn.textContent = 'Disconnect';
              connStatus.textContent = 'Connected';
              connStatus.className = 'w3-panel w3-green w3-round';
              
              fanOnBtn.disabled = false;
              fanOffBtn.disabled = false;
              
              readData();
          } catch (error) {
              console.error('Connection error:', error);
              alert('Failed to connect: ' + error.message);
          }
      } else {
          await disconnect();
      }
  });

  // Disconnect function
  async function disconnect() {
      if (writer) await writer.close();
      if (reader) await reader.cancel();
      if (port) await port.close();
      
      isConnected = false;
      connectBtn.textContent = 'Connect to Arduino';
      connStatus.textContent = 'Disconnected';
      connStatus.className = 'w3-panel w3-red w3-round';
      
      fanOnBtn.disabled = true;
      fanOffBtn.disabled = true;
  }

  // Read data from serial
  async function readData() {
      let buffer = '';
      try {
          while (isConnected) {
              const { value, done } = await reader.read();
              if (done) break;
              
              buffer += value;
              const lines = buffer.split('\n');
              buffer = lines.pop();
              
              for (const line of lines) {
                  const trimmed = line.trim();
                  console.log('Received:', trimmed);
                  
                  // Parse sensor data
                  if (trimmed.startsWith('TEMP:')) {
                      const parts = trimmed.split(',');
                      const temp = parseFloat(parts[0].split(':')[1]);
                      const hum = parseFloat(parts[1].split(':')[1]);
                      const fanState = parts[2].split(':')[1];
                      
                      tempData.textContent = `Temperature: ${temp.toFixed(1)} °C`;
                      humData.textContent = `Humidity: ${hum.toFixed(1)} %`;
                      
                      // Update fan status
                      fanStatus.textContent = `Fan: ${fanState}`;
                      fanStatus.className = fanState === 'ON' ? 'w3-panel w3-green w3-round' : 'w3-panel w3-red w3-round';
                      fanOnBtn.disabled = fanState === 'ON';
                      fanOffBtn.disabled = fanState === 'OFF';
                      
                      // Update chart
                      updateChart(temp, hum);
                  }
              }
          }
      } catch (error) {
          console.error('Read error:', error);
          await disconnect();
      }
  }

  // Update chart
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

  // Send commands
  async function sendCommand(cmd) {
      if (!isConnected || !writer) return;
      
      try {
          await writer.write(new TextEncoder().encode(cmd + '\n'));
          console.log('Sent command:', cmd);
      } catch (error) {
          console.error('Send error:', error);
          await disconnect();
      }
  }

  // Button events
  fanOnBtn.addEventListener('click', () => sendCommand('FAN_ON'));
  fanOffBtn.addEventListener('click', () => sendCommand('FAN_OFF'));

  // Check for Web Serial support
  if (!navigator.serial) {
      connectBtn.disabled = true;
      connectBtn.textContent = 'Web Serial Not Supported';
      connStatus.textContent = 'Web Serial API not available in this browser';
  }
  // Add this to your existing serial data reading function
if (trimmed.startsWith('EMPLOYEE:')) {
    const employeeID = trimmed.split('EMPLOYEE:')[1];
    updateEmployeeDisplay(employeeID);
}

// New functions for employee tracking

</script>
<!-- Add this section below your environmental monitoring -->
  <!-- Camera Feed Section -->

<?php include 'theme/foot.php'; ?>