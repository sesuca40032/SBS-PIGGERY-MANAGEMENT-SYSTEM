<?php include 'setting/system.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>

<!-- Add this to your head section or in theme/head.php -->
<script src="https://www.gstatic.com/assistant/sdk/assistant-sdk.min.js"></script>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Monitoring</b></h5>
  </header>

  <!-- Google Assistant Widget -->
  <div class="w3-container w3-section">
    <div class="w3-card-4" style="max-width:400px;">
      <header class="w3-container w3-blue">
        <h3>Google Assistant</h3>
      </header>
      <div class="w3-container">
        <button id="start-assistant" class="w3-button w3-green w3-block">Start Assistant</button>
        <div id="assistant-container" style="margin-top:10px; display:none;">
          <div id="assistant-status" class="w3-panel w3-light-grey"></div>
          <input id="query-input" class="w3-input w3-border" type="text" placeholder="Type your query...">
          <button id="send-query" class="w3-button w3-blue w3-block">Send</button>
          <div id="response-area" class="w3-panel" style="margin-top:10px; min-height:100px; border:1px solid #ddd;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Initialize the Assistant SDK
let assistant = null;

document.getElementById('start-assistant').addEventListener('click', () => {
  const startButton = document.getElementById('start-assistant');
  const container = document.getElementById('assistant-container');
  
  if (!assistant) {
    startButton.disabled = true;
    startButton.textContent = 'Initializing...';
    
    // Load the Assistant SDK
    assistant = window.AssistantSDK({
      // You'll need to get these credentials from Google Cloud Console
      clientConfig: {
        client_id: 'YOUR_CLIENT_ID.apps.googleusercontent.com',
        client_secret: 'YOUR_CLIENT_SECRET',
        redirect_uri: 'YOUR_REDIRECT_URI'
      },
      locale: 'en-US' // Set your preferred language
    });
    
    assistant.on('ready', () => {
      startButton.textContent = 'Assistant Ready';
      container.style.display = 'block';
      document.getElementById('assistant-status').textContent = 'Assistant is ready!';
    });
    
    assistant.on('response', (response) => {
      const responseArea = document.getElementById('response-area');
      responseArea.innerHTML += `<p><strong>Assistant:</strong> ${response.text}</p>`;
    });
    
    assistant.on('error', (error) => {
      console.error('Assistant error:', error);
      document.getElementById('assistant-status').textContent = 'Error: ' + error.message;
      startButton.textContent = 'Start Assistant';
      startButton.disabled = false;
    });
  }
});

document.getElementById('send-query').addEventListener('click', () => {
  const query = document.getElementById('query-input').value;
  if (query && assistant) {
    const responseArea = document.getElementById('response-area');
    responseArea.innerHTML += `<p><strong>You:</strong> ${query}</p>`;
    document.getElementById('query-input').value = '';
    assistant.sendTextQuery(query);
  }
});
</script>

<?php include 'theme/foot.php'; ?>