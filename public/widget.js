(function () {
  const currentScript = document.currentScript;
  const publicKey = currentScript && currentScript.dataset && currentScript.dataset.publicKey;
  const clientId = currentScript && currentScript.dataset && (currentScript.dataset.chatId || currentScript.dataset.clientId);
  if (!clientId) {
    console.error('[Chatbot] data-chat-id não definido no script tag.');
    return;
  }

  const API_BASE = (function () {
    try {
      const url = new URL(currentScript.src);
      return url.origin.replace(/\/$/, '');
    } catch (e) {
      return '';
    }
  })();

  const state = {
    settings: null,
    conversationId: null,
    visitorId: null,
    pollingInterval: null,
    lastFetchAt: null,
  };

  function uuid() {
    return 'v-' + Math.random().toString(36).slice(2) + Date.now().toString(36);
  }

  function ensureVisitorId() {
    const key = 'chatbot_visitor_id';
    let id = localStorage.getItem(key);
    if (!id) {
      id = uuid();
      localStorage.setItem(key, id);
    }
    state.visitorId = id;
  }

  async function fetchSettings() {
    const path = publicKey
      ? ('/api/widget/key/' + encodeURIComponent(publicKey))
      : ('/api/widget/' + encodeURIComponent(clientId));
    const res = await fetch(API_BASE + path, {
      method: 'GET',
      credentials: 'omit',
      headers: { 'Content-Type': 'application/json' }
    });
    if (!res.ok) throw new Error('Falha ao obter configurações do widget');
    const data = await res.json();
    state.settings = data.settings || {};
    state.isOnline = !!data.is_online;
    return data;
  }

  async function sendMessage(content, senderType) {
    const payload = {
      client_id: Number(clientId),
      visitor_id: state.visitorId,
      conversation_id: state.conversationId,
      sender_type: senderType || 'visitor',
      content: content
    };
    const res = await fetch(API_BASE + '/api/messages', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (!res.ok) {
      console.error('[Chatbot] Erro ao enviar mensagem', res.status);
      return null;
    }
    const data = await res.json();
    if (!state.conversationId) {
      state.conversationId = data.conversation_id;
    }
    return data.message;
  }

  async function fetchMessages() {
    if (!state.conversationId) return [];
    const url = new URL(API_BASE + '/api/messages');
    url.searchParams.set('conversation_id', String(state.conversationId));
    if (state.lastFetchAt) url.searchParams.set('since', state.lastFetchAt);

    const res = await fetch(url.toString(), { method: 'GET' });
    if (!res.ok) return [];
    const data = await res.json();
    if (data.messages && data.messages.length > 0) {
      state.lastFetchAt = data.messages[data.messages.length - 1].created_at;
    }
    return data.messages || [];
  }

  function createBubble() {
    const bubble = document.createElement('div');
    bubble.id = 'chatbot-bubble';
    bubble.style.position = 'fixed';
    bubble.style.right = '20px';
    bubble.style.bottom = '20px';
    bubble.style.width = '56px';
    bubble.style.height = '56px';
    bubble.style.borderRadius = '28px';
    bubble.style.background = state.settings.primary_color || '#2f80ed';
    bubble.style.boxShadow = '0 8px 24px rgba(0,0,0,0.15)';
    bubble.style.cursor = 'pointer';
    bubble.style.display = 'flex';
    bubble.style.alignItems = 'center';
    bubble.style.justifyContent = 'center';
    bubble.style.color = '#fff';
    bubble.style.fontSize = '28px';
    bubble.style.zIndex = '999999';
    bubble.textContent = '💬';

    bubble.addEventListener('click', openChat);

    document.body.appendChild(bubble);
  }

  function openChat() {
    if (document.getElementById('chatbot-panel')) {
      document.getElementById('chatbot-panel').style.display = 'block';
      return;
    }

    const panel = document.createElement('div');
    panel.id = 'chatbot-panel';
    panel.style.position = 'fixed';
    panel.style.right = '20px';
    panel.style.bottom = '84px';
    panel.style.width = '320px';
    panel.style.height = '420px';
    panel.style.borderRadius = '16px';
    panel.style.background = '#fff';
    panel.style.boxShadow = '0 12px 28px rgba(0,0,0,0.15)';
    panel.style.zIndex = '999999';
    panel.style.display = 'flex';
    panel.style.flexDirection = 'column';
    panel.style.overflow = 'hidden';
    panel.style.border = '1px solid #eee';

    const header = document.createElement('div');
    header.style.background = state.settings.primary_color || '#2f80ed';
    header.style.color = state.settings.secondary_color || '#ffffff';
    header.style.padding = '12px';
    header.style.fontWeight = '600';
    header.textContent = state.isOnline
      ? (state.settings.online_message || 'Estamos online')
      : (state.settings.offline_message || 'Estamos offline, deixe sua mensagem');

    const messages = document.createElement('div');
    messages.id = 'chatbot-messages';
    messages.style.flex = '1';
    messages.style.padding = '12px';
    messages.style.overflowY = 'auto';
    messages.style.background = '#fafafa';

    const inputWrap = document.createElement('div');
    inputWrap.style.display = 'flex';
    inputWrap.style.padding = '8px';

    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = state.settings.welcome_message || 'Olá! Como posso ajudar?';
    input.style.flex = '1';
    input.style.padding = '10px';
    input.style.border = '1px solid #ddd';
    input.style.borderRadius = '8px';

    const sendBtn = document.createElement('button');
    sendBtn.textContent = 'Enviar';
    sendBtn.style.marginLeft = '8px';
    sendBtn.style.background = state.settings.primary_color || '#2f80ed';
    sendBtn.style.color = state.settings.secondary_color || '#ffffff';
    sendBtn.style.border = 'none';
    sendBtn.style.borderRadius = '8px';
    sendBtn.style.padding = '10px 12px';
    sendBtn.style.cursor = 'pointer';

    sendBtn.addEventListener('click', async function () {
      const val = input.value.trim();
      if (!val) return;
      appendMessage(val, 'visitor');
      input.value = '';
      await sendMessage(val, 'visitor');
    });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') sendBtn.click();
    });

    inputWrap.appendChild(input);
    inputWrap.appendChild(sendBtn);

    panel.appendChild(header);
    panel.appendChild(messages);
    panel.appendChild(inputWrap);

    document.body.appendChild(panel);

    // iniciar polling
    if (state.pollingInterval) clearInterval(state.pollingInterval);
    state.pollingInterval = setInterval(async function () {
      const newMsgs = await fetchMessages();
      newMsgs.forEach(function (m) {
        if (m.sender_type !== 'visitor') {
          appendMessage(m.content, m.sender_type);
        }
      });
    }, 2000);
  }

  function appendMessage(text, senderType) {
    const container = document.getElementById('chatbot-messages');
    if (!container) return;

    const msg = document.createElement('div');
    msg.style.margin = '8px 0';
    msg.style.display = 'flex';
    msg.style.justifyContent = senderType === 'visitor' ? 'flex-end' : 'flex-start';

    const bubble = document.createElement('div');
    bubble.textContent = text;
    bubble.style.maxWidth = '75%';
    bubble.style.padding = '8px 12px';
    bubble.style.borderRadius = '12px';
    bubble.style.background = senderType === 'visitor' ? (state.settings.primary_color || '#2f80ed') : '#e9ecef';
    bubble.style.color = senderType === 'visitor' ? (state.settings.secondary_color || '#ffffff') : '#333';

    msg.appendChild(bubble);
    container.appendChild(msg);
    container.scrollTop = container.scrollHeight;
  }

  // Bootstrap
  (async function init() {
    ensureVisitorId();
    try {
      await fetchSettings();
      createBubble();
      // mensagem automática de boas-vindas (local)
      setTimeout(function () {
        appendMessage(state.settings.welcome_message || 'Olá! Como posso ajudar?', 'system');
      }, 300);
    } catch (e) {
      console.error('[Chatbot] erro ao iniciar', e);
    }
  })();
})();